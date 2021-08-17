<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\RegisterAddedValue\RegisterAddedValueRequest;
use App\Models\Branch;
use App\Models\RegisterAddedValue;
use App\Models\RegisterAddedValueLibrary;
use App\Services\LibraryServices;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterAddedValueController extends Controller
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
            $suppliers = RegisterAddedValue::orderBy( $sort_fields[$sort_by], $sort_method );
        } else {
            $suppliers = RegisterAddedValue::orderBy( 'id', 'DESC' );
        }
        if ($request->has( 'name' ) && $request['name'] != '') {
            $suppliers->where( 'id', $request['name'] );
        }
        if ($request->has( 'branch_id' ) && $request['branch_id'] != '') {
            $suppliers->where( 'branch_id', $request['branch_id'] );
        }
        whereBetween( $suppliers, 'register_date', $request->register_date_from, $request->register_date_to );
        $rows = $request->has( 'rows' ) ? $request->rows : 25;
        $data = $suppliers->paginate( $rows )->appends( request()->query() );

        $branches = filterSetting() ? Branch::all()->pluck( 'name', 'id' ) : null;

        return view( 'admin.register_added_value.index',
            compact( 'data', 'branches' ) );
    }

    public function create(Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $branch = Branch::where( 'id', $branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card'] )->first();
        $branches = Branch::all()->pluck( 'name', 'id' );
        $last_created = RegisterAddedValue::latest()->first();
        if (!empty( $last_created ) && !$request->has( 'branch_id' )) {
            $branch = Branch::where( 'id', $last_created->branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card'] )->first();
        }
        return view( 'admin.register_added_value.create', compact( 'branches', 'branch', 'last_created' ) );
    }

    public function store(RegisterAddedValueRequest $request)
    {
        try {
            $data = $request->validated();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            RegisterAddedValue::create( $data );
        } catch (Exception $e) {
            return redirect()->back()
                ->with( ['message' => __( 'words.back-register_added_value' ), 'alert-type' => 'error'] );
        }
        return redirect( route( 'admin:register_added_value.index' ) )
            ->with( ['message' => __( 'words.register_added_value-created' ), 'alert-type' => 'success'] );
    }

    public function show()
    {
        return back();
    }

    public function edit(RegisterAddedValue $register_added_value, Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : $register_added_value->branch_id;
        $branches = Branch::all()->pluck( 'name', 'id' );
        $branch = Branch::where( 'id', $branch_id )->get( ['name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address','tax_card'] )->first();
        return view( 'admin.register_added_value.edit', compact( 'branches', 'branch', 'register_added_value' ) );
    }

    public function update(RegisterAddedValueRequest $request, RegisterAddedValue $register_added_value)
    {
        try {

            $data = $request->validated();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $register_added_value->update( $data );
        } catch (Exception $e) {
            return redirect()->back()
                ->with( ['message' => __( 'words.back-register_added_value' ), 'alert-type' => 'error'] );
        }

        return redirect( route( 'admin:register_added_value.index' ) )
            ->with( ['message' => __( 'words.register_added_value-updated' ), 'alert-type' => 'success'] );
    }

    public function destroy(RegisterAddedValue $register_added_value)
    {
        $register_added_value->delete();
        return redirect( route( 'admin:register_added_value.index' ) )
            ->with( ['message' => __( 'words.register_added_value-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {
            RegisterAddedValue::whereIn( 'id', $request->ids )->delete();
            return redirect( route( 'admin:register_added_value.index' ) )
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        return redirect( route( 'admin:register_added_value.index' ) )
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'title_ar' => 'required|string|max:100',
            'title_en' => 'nullable|string|max:100',
            'register_added_value' => 'required|integer|exists:register_added_value,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ] );

        if ($validator->fails()) {
            return response()->json( $validator->errors()->first(), 400 );
        }

        try {
            $register_added_value = RegisterAddedValue::find( $request['register_added_value'] );
            $library_path = $this->libraryPath( $register_added_value, 'register_added_value' );
            $director = 'register_added_value_library/' . $library_path;
            $files = [];
            foreach ($request['files'] as $index => $file) {

                $fileData = $this->uploadFiles( $file, $director );
                $fileName = $fileData['file_name'];
                $extension = Str::lower( $fileData['extension'] );
                $name = $fileData['name'];
                $title_en= $request->title_en??$request->title_ar;
                $files[$index] = $this->createRegisterAddedValueLibrary( $register_added_value->id, $fileName, $extension, $name, $request->title_ar, $title_en);
            }
            $view = view( 'admin.register_added_value.library', compact( 'files', 'library_path' ) )->render();
        } catch (Exception $e) {
            return response()->json( __( 'words.back-register_added_value', 400 ) );
        }
        return response()->json( ['view' => $view, 'message' => __( 'upload successfully' )], 200 );
    }

    public function getFiles(Request $request)
    {

        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:register_added_value,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $supplier = RegisterAddedValue::find( $request['id'] );
            if (!$supplier) {
                return response( 'register_added_value not valid', 400 );
            }
            $library_path = $supplier->library_path;
            $files = $supplier->files;
            $view = view( 'admin.register_added_value.library', compact( 'files', 'library_path' ) )->render();

        } catch (Exception $e) {
            dd($e->getMessage());
            return response( 'sorry, please try later', 400 );
        }

        return response( ['view' => $view], 200 );
    }

    public function destroyFile(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:register_added_value_libraries,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $file = RegisterAddedValueLibrary::find( $request['id'] );
            $supplier = $file->register_added_value;
            $filePath = storage_path( 'app/public/register_added_value_library/' . $supplier->library_path . '/' . $file->file_name );
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
