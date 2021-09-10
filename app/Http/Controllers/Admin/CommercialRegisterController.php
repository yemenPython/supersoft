<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CommercialRegister\CommercialRegisterRequest;
use App\Models\Branch;
use App\Models\CommercialRegister;
use App\Models\CommercialRegisterLibrary;
use App\Models\EgyptianFederationLibrary;
use App\Models\Supplier;
use App\Services\LibraryServices;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CommercialRegisterController extends Controller
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
            $suppliers = CommercialRegister::orderBy( $sort_fields[$sort_by], $sort_method );
        } else {
            $suppliers = CommercialRegister::orderBy( 'id', 'DESC' );
        }
        if ($request->has( 'name' ) && $request['name'] != '') {
            $suppliers->where( 'id', $request['name'] );
        }
        if ($request->has( 'branch_id' ) && $request['branch_id'] != '') {
            $suppliers->where( 'branch_id', $request['branch_id'] );
        }
        whereBetween( $suppliers, 'valid_until', $request->valid_until_from, $request->valid_until_to );
        $rows = $request->has( 'rows' ) ? $request->rows : 25;
        $data = $suppliers->paginate( $rows )->appends( request()->query() );
        $branches = filterSetting() ? Branch::all()->pluck( 'name', 'id' ) : null;

        return view( 'admin.commercial_register.index',
            compact( 'data', 'branches' ) );
    }

    public function create(Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $branch = Branch::where( 'id', $branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card'] )->first();
        $branches = Branch::all()->pluck( 'name', 'id' );
        $last_created = CommercialRegister::latest()->first();
        if (!empty( $last_created ) && !$request->has( 'branch_id' )) {
            $branch = Branch::where( 'id', $last_created->branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card'] )->first();
        }
        return view( 'admin.commercial_register.create', compact( 'branches', 'branch', 'last_created' ) );
    }

    public function store(CommercialRegisterRequest $request)
    {
        try {
            $data = $request->validated();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            if ($request->has('renewable')) {
                $data['renewable'] = 1;
            }
            CommercialRegister::create( $data );
        } catch (Exception $e) {
            return redirect()->back()
                ->with( ['message' => __( 'words.back-commercial_register' ), 'alert-type' => 'error'] );
        }
        return redirect( route( 'admin:commercial_register.index' ) )
            ->with( ['message' => __( 'words.commercial_register-created' ), 'alert-type' => 'success'] );
    }

    public function show(CommercialRegister $commercial_register)
    {
        $item = $commercial_register;
        return response()->json([
            'data' => view('admin.commercial_register.show', compact('item'))->render()
        ]);
    }

    public function edit(CommercialRegister $commercial_register, Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : $commercial_register->branch_id;
        $branches = Branch::all()->pluck( 'name', 'id' );
        $branch = Branch::where( 'id', $branch_id )->get( ['name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card'] )->first();
        return view( 'admin.commercial_register.edit', compact( 'branches', 'branch', 'commercial_register' ) );
    }

    public function update(CommercialRegisterRequest $request, CommercialRegister $commercial_register)
    {
        try {

            $data = $request->validated();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            if ($request->has('renewable')) {
                $data['renewable'] = 1;
            }else{
                $data['renewable'] = 0;
            }
            $commercial_register->update( $data );
        } catch (Exception $e) {
            return redirect()->back()
                ->with( ['message' => __( 'words.back-commercial_register' ), 'alert-type' => 'error'] );
        }

        return redirect( route( 'admin:commercial_register.index' ) )
            ->with( ['message' => __( 'words.commercial_register-updated' ), 'alert-type' => 'success'] );
    }

    public function destroy(CommercialRegister $commercial_register)
    {
        $commercial_register->delete();
        return redirect( route( 'admin:commercial_register.index' ) )
            ->with( ['message' => __( 'words.commercial_register-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {
            CommercialRegister::whereIn( 'id', $request->ids )->delete();
            return redirect( route( 'admin:commercial_register.index' ) )
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        return redirect( route( 'admin:commercial_register.index' ) )
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'title_ar' => 'required|string|max:100',
            'title_en' => 'nullable|string|max:100',
            'commercial_register' => 'required|integer|exists:commercial_register,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ] );

        if ($validator->fails()) {
            return response()->json( $validator->errors()->first(), 400 );
        }

        try {
            $commercial_register = CommercialRegister::find( $request['commercial_register'] );
            $library_path = $this->libraryPath( $commercial_register, 'commercial_register' );
            $director = 'commercial_register_library/' . $library_path;
            $files = [];
            foreach ($request['files'] as $index => $file) {

                $fileData = $this->uploadFiles( $file, $director );
                $fileName = $fileData['file_name'];
                $extension = Str::lower( $fileData['extension'] );
                $name = $fileData['name'];
                $title_en= $request->title_en??$request->title_ar;
                $files[$index] = $this->createCommercialRegisterLibrary( $commercial_register->id, $fileName, $extension, $name, $request->title_ar, $title_en);
            }
            $view = view( 'admin.commercial_register.library', compact( 'files', 'library_path' ) )->render();
        } catch (Exception $e) {
            return response()->json( __( 'words.back-commercial_register', 400 ) );
        }
        return response()->json( ['view' => $view, 'message' => __( 'upload successfully' )], 200 );
    }

    public function getFiles(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:commercial_register,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $supplier = CommercialRegister::find( $request['id'] );
            if (!$supplier) {
                return response( 'commercial_register not valid', 400 );
            }
            $library_path = $supplier->library_path;
            $files = $supplier->files;
            $view = view( 'admin.commercial_register.library', compact( 'files', 'library_path' ) )->render();

        } catch (Exception $e) {
            return response( 'sorry, please try later', 400 );
        }

        return response( ['view' => $view], 200 );
    }

    public function destroyFile(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:commercial_register_libraries,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $file = CommercialRegisterLibrary::find( $request['id'] );
            $supplier = $file->commercial_register;
            $filePath = storage_path( 'app/public/commercial_register_library/' . $supplier->library_path . '/' . $file->file_name );
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
