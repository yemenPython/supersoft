<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SecurityApproval\SecurityApprovalRequest;
use App\Models\Branch;
use App\Models\CompanyContract;
use App\Models\EmployeeData;
use App\Models\SecurityApproval;
use App\Models\SecurityApprovalLibrary;
use App\Services\LibraryServices;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SecurityApprovalController extends Controller
{
    use LibraryServices;

    public function index(Request $request)
    {
        if ($request->has( 'sort_by' ) && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has( 'sort_method' ) ? $request->sort_method : 'asc';
            if (!in_array( $sort_method, ['asc', 'desc'] )) {
                $sort_method = 'desc';
            }
            $lang = app()->getLocale() == 'ar' ? 'ar' : 'en';
            $sort_fields = [
                'id' => 'id',
                'supplier_name' => 'name_' . $lang,
                'supplier_group' => 'group_id',
                'supplier_type' => 'type',
                'funds_for' => 'funds_for',
                'funds_on' => 'funds_on',
                'status' => 'status',
                'created_at' => 'created_at',
                'updated_at' => 'updated_at'
            ];
            $suppliers = SecurityApproval::orderBy( $sort_fields[$sort_by], $sort_method );
        } else {
            $suppliers = SecurityApproval::orderBy( 'id', 'DESC' );
        }
        if ($request->has( 'name' ) && $request['name'] != '') {
            $suppliers->where( 'id', $request['name'] );
        }
        if ($request->has( 'branch_id' ) && $request['branch_id'] != '') {
            $suppliers->where( 'branch_id', $request['branch_id'] );
        }
        whereBetween( $suppliers, 'expiration_date', $request->expiration_date_from, $request->expiration_date_to );

        $rows = $request->has( 'rows' ) ? $request->rows : 25;
        $data = $suppliers->paginate( $rows )->appends( request()->query() );
        $branches = filterSetting() ? Branch::all()->pluck( 'name', 'id' ) : null;

        return view( 'admin.security_approval.index',
            compact( 'data', 'branches' ) );
    }

    public function create(Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $branch = Branch::where( 'id', $branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card','phone1','phone2','fax'] )->first();
        $branches = Branch::all()->pluck( 'name', 'id' );
        $last_created = SecurityApproval::latest()->first();
        if (!empty( $last_created ) && !$request->has( 'branch_id' )) {
            $branch = Branch::where( 'id', $last_created->branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card','phone1','phone2','fax'] )->first();
        }
//        dd($last_created);
        $employees = EmployeeData::all(['id','name_ar','name_en']);
        return view( 'admin.security_approval.create', compact( 'branches', 'branch', 'last_created','employees' ) );
    }

    public function store(SecurityApprovalRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $data['user_id'] = auth()->id();
           $security=  SecurityApproval::create( $data );

          if (count(array_filter($request->all()['owners']))) {
              foreach (($request->all()['owners']) as $owner) {
                  if (!empty($owner)) {
                      $security->owners()->create( ['owner' => $owner] );
                  }
              }
          }
          if (count(array_filter($request->all()['representatives']))) {
              foreach (($request->all()['representatives']) as $item) {
                  if (!empty($item)) {
                      $security->representatives()->create( ['employee_id' => $item] );
                  }
              }
          }
          if (count(array_filter($request->all()['phones']))) {
              foreach (($request->all()['phones']) as $phone) {
                  if (!empty($phone)) {
                      $security->phones()->create( ['phone' => $phone] );
                  }
              }
          }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()
                ->with( ['message' => __( 'words.back-security_approval' ), 'alert-type' => 'error'] );
        }
        return redirect( route( 'admin:security_approval.index' ) )
            ->with( ['message' => __( 'words.security_approval-created' ), 'alert-type' => 'success'] );
    }

    public function show(SecurityApproval $security_approval)
    {
        $item = $security_approval;
        return response()->json([
            'data' => view('admin.security_approval.show', compact('item'))->render()
        ]);
    }

    public function edit(SecurityApproval $security_approval, Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : $security_approval->branch_id;
        $branches = Branch::all()->pluck( 'name', 'id' );
        $branch = Branch::where( 'id', $branch_id )->get( ['name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card','phone1','phone2','fax'] )->first();
        $employees = EmployeeData::all(['id','name_ar','name_en']);
        return view( 'admin.security_approval.edit', compact( 'branches', 'branch', 'security_approval','employees' ) );
    }

    public function update(SecurityApprovalRequest $request, SecurityApproval $security_approval)
    {
        DB::beginTransaction();
        try {

            $data = $request->validated();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $security_approval->update( $data );
            $security_approval->owners()->delete();
            $security_approval->representatives()->delete();
            $security_approval->phones()->delete();

            if (count(array_filter($request->all()['owners']))) {
                foreach (($request->all()['owners']) as $owner) {
                    if (!empty( $owner )) {
                        $security_approval->owners()->create( ['owner' => $owner] );
                    }
                }
            }
            if (count(array_filter($request->all()['representatives']))) {
                foreach (($request->all()['representatives']) as $item) {
                    if (!empty( $item )) {
                        $security_approval->representatives()->create( ['employee_id' => $item] );
                    }
                }
            }
            if (count(array_filter($request->all()['phones']))) {
                foreach (($request->all()['phones']) as $phone) {
                    if (!empty( $phone )) {
                        $security_approval->phones()->create( ['phone' => $phone] );
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with( ['message' => __( 'words.back-security_approval' ), 'alert-type' => 'error'] );
        }

        return redirect( route( 'admin:security_approval.index' ) )
            ->with( ['message' => __( 'words.security_approval-updated' ), 'alert-type' => 'success'] );
    }

    public function destroy(SecurityApproval $security_approval)
    {
        $security_approval->delete();
        return redirect( route( 'admin:security_approval.index' ) )
            ->with( ['message' => __( 'words.security_approval-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {
            SecurityApproval::whereIn( 'id', $request->ids )->delete();
            return redirect( route( 'admin:security_approval.index' ) )
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        return redirect( route( 'admin:security_approval.index' ) )
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'title_ar' => 'required|string|max:100',
            'title_en' => 'nullable|string|max:100',
            'security_approval' => 'required|integer|exists:security_approval,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ] );

        if ($validator->fails()) {
            return response()->json( $validator->errors()->first(), 400 );
        }

        try {
            $security_approval = SecurityApproval::find( $request['security_approval'] );
            $library_path = $this->libraryPath( $security_approval, 'security_approval' );
            $director = 'security_approval_library/' . $library_path;
            $files = [];
            foreach ($request['files'] as $index => $file) {

                $fileData = $this->uploadFiles( $file, $director );
                $fileName = $fileData['file_name'];
                $extension = Str::lower( $fileData['extension'] );
                $name = $fileData['name'];
                $title_en= $request->title_en??$request->title_ar;
                $files[$index] = $this->createSecurityApprovalLibrary( $security_approval->id, $fileName, $extension, $name, $request->title_ar, $title_en);
            }
            $view = view( 'admin.security_approval.library', compact( 'files', 'library_path' ) )->render();
        } catch (Exception $e) {
            return response()->json( __( 'words.back-security_approval', 400 ) );
        }
        return response()->json( ['view' => $view, 'message' => __( 'upload successfully' )], 200 );
    }

    public function getFiles(Request $request)
    {

        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:security_approval,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $supplier = SecurityApproval::find( $request['id'] );
            if (!$supplier) {
                return response( 'security_approval not valid', 400 );
            }
            $library_path = $supplier->library_path;
            $files = $supplier->files;
            $view = view( 'admin.security_approval.library', compact( 'files', 'library_path' ) )->render();

        } catch (Exception $e) {
            dd($e->getMessage());
            return response( 'sorry, please try later', 400 );
        }

        return response( ['view' => $view], 200 );
    }

    public function destroyFile(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:security_approval_libraries,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $file = SecurityApprovalLibrary::find( $request['id'] );
            $supplier = $file->security_approval;
            $filePath = storage_path( 'app/public/security_approval_library/' . $supplier->library_path . '/' . $file->file_name );
            if (File::exists( $filePath )) {
                File::delete( $filePath );
            }
            $file->delete();
        } catch (Exception $e) {
            return response( 'sorry, please try later', 200 );
        }
        return response( ['id' => $request['id']], 200 );
    }
}
