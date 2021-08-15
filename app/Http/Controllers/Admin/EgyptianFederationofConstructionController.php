<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EgyptianFederationofConstruction\EgyptianFederationofConstructionRequest;
use App\Models\Branch;
use App\Models\EgyptianFederationLibrary;
use App\Models\EgyptianFederationOfConstructionAndBuildingContractors;
use App\Models\Supplier;
use App\Services\LibraryServices;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EgyptianFederationofConstructionController extends Controller
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
            $suppliers = EgyptianFederationOfConstructionAndBuildingContractors::orderBy( $sort_fields[$sort_by], $sort_method );
        } else {
            $suppliers = EgyptianFederationOfConstructionAndBuildingContractors::orderBy( 'id', 'DESC' );
        }
        if ($request->has( 'name' ) && $request['name'] != '') {
            $suppliers->where( 'id', $request['name'] );
        }
        if ($request->has( 'branch_id' ) && $request['branch_id'] != '') {
            $suppliers->where( 'branch_id', $request['branch_id'] );
        }
        whereBetween( $suppliers, 'end_date', $request->end_date_from, $request->end_date_to );
        $rows = $request->has( 'rows' ) ? $request->rows : 25;
        $data = $suppliers->paginate( $rows )->appends( request()->query() );

        $branches = filterSetting() ? Branch::all()->pluck( 'name', 'id' ) : null;

        return view( 'admin.egyptian_federation_of_construction_and_building_contractors.index',
            compact( 'data', 'branches' ) );
    }

    public function create(Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $branch = Branch::where( 'id', $branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address'] )->first();
        $branches = Branch::all()->pluck( 'name', 'id' );
        $last_created = EgyptianFederationOfConstructionAndBuildingContractors::latest()->first();
        if (!empty( $last_created ) && !$request->has( 'branch_id' )) {
            $branch = Branch::where( 'id', $last_created->branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address'] )->first();
        }
        return view( 'admin.egyptian_federation_of_construction_and_building_contractors.create', compact( 'branches', 'branch', 'last_created' ) );
    }

    public function store(EgyptianFederationofConstructionRequest $request)
    {
        try {
            $data = $request->validated();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            EgyptianFederationOfConstructionAndBuildingContractors::create( $data );
        } catch (Exception $e) {
            return redirect()->back()
                ->with( ['message' => __( 'words.back-egyptian_federation_of_construction_and_building_contractors' ), 'alert-type' => 'error'] );
        }
        return redirect( route( 'admin:egyptian_federation.index' ) )
            ->with( ['message' => __( 'words.egyptian_federation_of_construction_and_building_contractors-created' ), 'alert-type' => 'success'] );
    }

    public function show(Supplier $supplier)
    {
        if (!auth()->user()->can( 'view_suppliers' )) {
            return redirect()->back()->with( ['authorization' => 'error'] );
        }
        $supplierGroupsTreeMain = $this->getMainSupplierGroupsAsTree( $supplier->main_groups_id );
        $supplierGroupsTreeSub = $this->getSubSupplierGroupsAsTree( $supplier->sub_groups_id );
        return view( 'admin.suppliers.show', compact( 'supplier', 'supplierGroupsTreeMain', 'supplierGroupsTreeSub' ) );
    }

    public function edit(EgyptianFederationOfConstructionAndBuildingContractors $egyptian_federation, Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : $egyptian_federation->branch_id;
        $branches = Branch::all()->pluck( 'name', 'id' );
        $branch = Branch::where( 'id', $branch_id )->get( ['name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address'] )->first();
        return view( 'admin.egyptian_federation_of_construction_and_building_contractors.edit', compact( 'branches', 'branch', 'egyptian_federation' ) );
    }

    public function update(EgyptianFederationofConstructionRequest $request, EgyptianFederationOfConstructionAndBuildingContractors $egyptian_federation)
    {
        try {

            $data = $request->validated();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $egyptian_federation->update( $data );
        } catch (Exception $e) {
            return redirect()->back()
                ->with( ['message' => __( 'words.back-egyptian_federation_of_construction_and_building_contractors' ), 'alert-type' => 'error'] );
        }

        return redirect( route( 'admin:egyptian_federation.index' ) )
            ->with( ['message' => __( 'words.egyptian_federation_of_construction_and_building_contractors-updated' ), 'alert-type' => 'success'] );
    }

    public function destroy(EgyptianFederationOfConstructionAndBuildingContractors $egyptian_federation)
    {
        $egyptian_federation->delete();
        return redirect( route( 'admin:egyptian_federation.index' ) )
            ->with( ['message' => __( 'words.egyptian_federation_of_construction_and_building_contractors-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {
            EgyptianFederationOfConstructionAndBuildingContractors::whereIn( 'id', $request->ids )->delete();
            return redirect( route( 'admin:egyptian_federation.index' ) )
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        return redirect( route( 'admin:egyptian_federation.index' ) )
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'title_ar' => 'required|string|max:100',
            'title_en' => 'nullable|string|max:100',
            'egyptian_federation' => 'required|integer|exists:egyptian_federation_of_construction_and_building_contractors,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ] );

        if ($validator->fails()) {
            return response()->json( $validator->errors()->first(), 400 );
        }

        try {
            $egyptian_federation = EgyptianFederationOfConstructionAndBuildingContractors::find( $request['egyptian_federation'] );
            $library_path = $this->libraryPath( $egyptian_federation, 'egyptian_federation' );
            $director = 'egyptian_federation_library/' . $library_path;
            $files = [];
            foreach ($request['files'] as $index => $file) {

                $fileData = $this->uploadFiles( $file, $director );
                $fileName = $fileData['file_name'];
                $extension = Str::lower( $fileData['extension'] );
                $name = $fileData['name'];
                $title_en= $request->title_en??$request->title_ar;
                $files[$index] = $this->createEgyptianFederationLibrary( $egyptian_federation->id, $fileName, $extension, $name, $request->title_ar, $title_en);
            }
            $view = view( 'admin.egyptian_federation_of_construction_and_building_contractors.library', compact( 'files', 'library_path' ) )->render();
        } catch (Exception $e) {
            return response()->json( __( 'words.back-egyptian_federation_of_construction_and_building_contractors', 400 ) );
        }
        return response()->json( ['view' => $view, 'message' => __( 'upload successfully' )], 200 );
    }

    public function getFiles(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:egyptian_federation_of_construction_and_building_contractors,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $supplier = EgyptianFederationOfConstructionAndBuildingContractors::find( $request['id'] );
            if (!$supplier) {
                return response( 'egyptian_federation not valid', 400 );
            }
            $library_path = $supplier->library_path;
            $files = $supplier->files;
            $view = view( 'admin.egyptian_federation_of_construction_and_building_contractors.library', compact( 'files', 'library_path' ) )->render();

        } catch (Exception $e) {
            return response( 'sorry, please try later', 400 );
        }

        return response( ['view' => $view], 200 );
    }

    public function destroyFile(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:egyptian_federation_libraries,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $file = EgyptianFederationLibrary::find( $request['id'] );
            $supplier = $file->egyptian_federation;
            $filePath = storage_path( 'app/public/egyptian_federation_library/' . $supplier->library_path . '/' . $file->file_name );
            if (File::exists( $filePath )) {
                File::delete( $filePath );
            }
            $file->delete();
        } catch (Exception $e) {
            dd( $e->getMessage() );
            return response( 'sorry, please try later', 200 );
        }
        return response( ['id' => $request['id']], 200 );
    }
}
