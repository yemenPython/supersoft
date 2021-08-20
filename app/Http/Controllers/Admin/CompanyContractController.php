<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CompanyContract\CompanyContractRequest;
use App\Models\Branch;
use App\Models\CompanyContract;
use App\Models\CompanyContractLibrary;
use App\Models\RegisterAddedValue;
use App\Services\LibraryServices;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CompanyContractController extends Controller
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
            $suppliers = CompanyContract::orderBy( $sort_fields[$sort_by], $sort_method );
        } else {
            $suppliers = CompanyContract::orderBy( 'id', 'DESC' );
        }
        if ($request->has( 'name' ) && $request['name'] != '') {
            $suppliers->where( 'id', $request['name'] );
        }
        if ($request->has( 'branch_id' ) && $request['branch_id'] != '') {
            $suppliers->where( 'branch_id', $request['branch_id'] );
        }
        whereBetween( $suppliers, 'end_at', $request->end_at_from, $request->end_at_to );

        $rows = $request->has( 'rows' ) ? $request->rows : 25;
        $data = $suppliers->paginate( $rows )->appends( request()->query() );

        $branches = filterSetting() ? Branch::all()->pluck( 'name', 'id' ) : null;

        return view( 'admin.company_contract.index',
            compact( 'data', 'branches' ) );
    }

    public function create(Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $branch = Branch::where( 'id', $branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card'] )->first();
        $branches = Branch::all()->pluck( 'name', 'id' );
        $last_created = CompanyContract::latest()->first();
        if (!empty( $last_created ) && !$request->has( 'branch_id' )) {
            $branch = Branch::where( 'id', $last_created->branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card'] )->first();
        }
        return view( 'admin.company_contract.create', compact( 'branches', 'branch', 'last_created' ) );
    }

    public function store(CompanyContractRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $data['user_id'] = auth()->id();
           $contact=  CompanyContract::create( $data );

          if (count(array_filter($request->all()['partners']))) {
              foreach (($request->all()['partners']) as $partner) {
                  $contact->partners()->create(['partner'=>$partner]);
              }
          }
          if (count(array_filter($request->all()['company_share']))) {
              foreach (($request->all()['company_share']) as $item) {
                  $contact->company_shares()->create(['company_share'=>$item]);
              }
          }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with( ['message' => __( 'words.back-company_contract' ), 'alert-type' => 'error'] );
        }
        return redirect( route( 'admin:company_contract.index' ) )
            ->with( ['message' => __( 'words.company_contract-created' ), 'alert-type' => 'success'] );
    }

    public function show()
    {
        return back();
    }

    public function edit(CompanyContract $company_contract, Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : $company_contract->branch_id;
        $branches = Branch::all()->pluck( 'name', 'id' );
        $branch = Branch::where( 'id', $branch_id )->get( ['name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card'] )->first();
        return view( 'admin.company_contract.edit', compact( 'branches', 'branch', 'company_contract' ) );
    }

    public function update(CompanyContractRequest $request, CompanyContract $company_contract)
    {
        DB::beginTransaction();
        try {

            $data = $request->validated();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $company_contract->update( $data );
            $company_contract->partners()->delete();
            $company_contract->company_shares()->delete();
            if (count(array_filter($request->all()['partners']))) {

                foreach (($request->all()['partners']) as $partner) {
                    if (!empty($partner)){
                    $company_contract->partners()->create( ['partner' => $partner] );
                }
                }
            }
            if (count(array_filter($request->all()['company_share']))) {
                foreach (($request->all()['company_share']) as $item) {
                    if (!empty( $item )) {
                        $company_contract->company_shares()->create( ['company_share' => $item] );
                    }
                }
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with( ['message' => __( 'words.back-company_contract' ), 'alert-type' => 'error'] );
        }

        return redirect( route( 'admin:company_contract.index' ) )
            ->with( ['message' => __( 'words.company_contract-updated' ), 'alert-type' => 'success'] );
    }

    public function destroy(CompanyContract $company_contract)
    {
        $company_contract->delete();
        return redirect( route( 'admin:company_contract.index' ) )
            ->with( ['message' => __( 'words.company_contract-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {
            CompanyContract::whereIn( 'id', $request->ids )->delete();
            return redirect( route( 'admin:company_contract.index' ) )
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        return redirect( route( 'admin:company_contract.index' ) )
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'title_ar' => 'required|string|max:100',
            'title_en' => 'nullable|string|max:100',
            'company_contract' => 'required|integer|exists:company_contract,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ] );

        if ($validator->fails()) {
            return response()->json( $validator->errors()->first(), 400 );
        }

        try {
            $company_contract = CompanyContract::find( $request['company_contract'] );
            $library_path = $this->libraryPath( $company_contract, 'company_contract' );
            $director = 'company_contract_library/' . $library_path;
            $files = [];
            foreach ($request['files'] as $index => $file) {

                $fileData = $this->uploadFiles( $file, $director );
                $fileName = $fileData['file_name'];
                $extension = Str::lower( $fileData['extension'] );
                $name = $fileData['name'];
                $title_en= $request->title_en??$request->title_ar;
                $files[$index] = $this->createCompanyContractibrary( $company_contract->id, $fileName, $extension, $name, $request->title_ar, $title_en);
            }
            $view = view( 'admin.company_contract.library', compact( 'files', 'library_path' ) )->render();
        } catch (Exception $e) {
            return response()->json( __( 'words.back-company_contract', 400 ) );
        }
        return response()->json( ['view' => $view, 'message' => __( 'upload successfully' )], 200 );
    }

    public function getFiles(Request $request)
    {

        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:company_contract,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $supplier = CompanyContract::find( $request['id'] );
            if (!$supplier) {
                return response( 'company_contract not valid', 400 );
            }
            $library_path = $supplier->library_path;
            $files = $supplier->files;
            $view = view( 'admin.company_contract.library', compact( 'files', 'library_path' ) )->render();

        } catch (Exception $e) {
            dd($e->getMessage());
            return response( 'sorry, please try later', 400 );
        }

        return response( ['view' => $view], 200 );
    }

    public function destroyFile(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:company_contract_libraries,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $file = CompanyContractLibrary::find( $request['id'] );
            $supplier = $file->company_contract;
            $filePath = storage_path( 'app/public/company_contract_library/' . $supplier->library_path . '/' . $file->file_name );
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
