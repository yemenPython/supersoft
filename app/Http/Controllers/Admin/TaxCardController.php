<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\EgyptianFederationofConstruction\EgyptianFederationofConstructionRequest;
use App\Http\Requests\Admin\TaxCard\TaxCardRequest;
use App\Models\Branch;
use App\Models\EgyptianFederationLibrary;
use App\Models\EgyptianFederationOfConstructionAndBuildingContractors;
use App\Models\Supplier;
use App\Models\TaxCard;
use App\Models\TaxCardLibrary;
use App\Services\LibraryServices;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TaxCardController extends Controller
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
            $suppliers = TaxCard::orderBy( $sort_fields[$sort_by], $sort_method );
        } else {
            $suppliers = TaxCard::orderBy( 'id', 'DESC' );
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

        return view( 'admin.tax_card.index',
            compact( 'data', 'branches' ) );
    }

    public function create(Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $branch = Branch::where( 'id', $branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address'] )->first();
        $branches = Branch::all()->pluck( 'name', 'id' );
        $last_created = TaxCard::latest()->first();
        if (!empty( $last_created ) && !$request->has( 'branch_id' )) {
            $branch = Branch::where( 'id', $last_created->branch_id )->get( ['id', 'name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address'] )->first();
        }
        return view( 'admin.tax_card.create', compact( 'branches', 'branch', 'last_created' ) );
    }

    public function store(TaxCardRequest $request)
    {
        try {
            $data = $request->validated();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            TaxCard::create( $data );
        } catch (Exception $e) {
            return redirect()->back()
                ->with( ['message' => __( 'words.back-tax_card' ), 'alert-type' => 'error'] );
        }
        return redirect( route( 'admin:tax_card.index' ) )
            ->with( ['message' => __( 'words.tax_card-created' ), 'alert-type' => 'success'] );
    }

    public function show()
    {
        return back();
    }

    public function edit(TaxCard $tax_card, Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : $tax_card->branch_id;
        $branches = Branch::all()->pluck( 'name', 'id' );
        $branch = Branch::where( 'id', $branch_id )->get( ['name_' . app()->getLocale() . ' as company_name', 'address_' . app()->getLocale() . ' as address'] )->first();
        return view( 'admin.tax_card.edit', compact( 'branches', 'branch', 'tax_card' ) );
    }

    public function update(TaxCardRequest $request, TaxCard $tax_card)
    {
        try {

            $data = $request->validated();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $tax_card->update( $data );
        } catch (Exception $e) {
            return redirect()->back()
                ->with( ['message' => __( 'words.back-tax_card' ), 'alert-type' => 'error'] );
        }

        return redirect( route( 'admin:tax_card.index' ) )
            ->with( ['message' => __( 'words.tax_card-updated' ), 'alert-type' => 'success'] );
    }

    public function destroy(TaxCard $tax_card)
    {
        $tax_card->delete();
        return redirect( route( 'admin:tax_card.index' ) )
            ->with( ['message' => __( 'words.tax_card-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {
            TaxCard::whereIn( 'id', $request->ids )->delete();
            return redirect( route( 'admin:tax_card.index' ) )
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        return redirect( route( 'admin:tax_card.index' ) )
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function uploadLibrary(Request $request)
    {
        $validator = Validator::make( $request->all(), [
            'title_ar' => 'required|string|max:100',
            'title_en' => 'nullable|string|max:100',
            'tax_card' => 'required|integer|exists:tax_cards,id',
            'files' => 'required',
            'files.*' => 'required|mimes:jpeg,jpg,png,gif,pdf,xlsx,xlsm,xls,xls,docx,docm,dotx,txt|required|max:6000',
        ] );

        if ($validator->fails()) {
            return response()->json( $validator->errors()->first(), 400 );
        }

        try {
            $tax_card = TaxCard::find( $request['tax_card'] );
            $library_path = $this->libraryPath( $tax_card, 'tax_card' );
            $director = 'tax_card_library/' . $library_path;
            $files = [];
            foreach ($request['files'] as $index => $file) {

                $fileData = $this->uploadFiles( $file, $director );
                $fileName = $fileData['file_name'];
                $extension = Str::lower( $fileData['extension'] );
                $name = $fileData['name'];
                $title_en= $request->title_en??$request->title_ar;
                $files[$index] = $this->createTaxCardLibrary( $tax_card->id, $fileName, $extension, $name, $request->title_ar, $title_en);
            }
            $view = view( 'admin.tax_card.library', compact( 'files', 'library_path' ) )->render();
        } catch (Exception $e) {
            return response()->json( __( 'words.back-tax_card', 400 ) );
        }
        return response()->json( ['view' => $view, 'message' => __( 'upload successfully' )], 200 );
    }

    public function getFiles(Request $request)
    {

        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:tax_cards,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $supplier = TaxCard::find( $request['id'] );
            if (!$supplier) {
                return response( 'egyptian_federation not valid', 400 );
            }
            $library_path = $supplier->library_path;
            $files = $supplier->files;
            $view = view( 'admin.tax_card.library', compact( 'files', 'library_path' ) )->render();

        } catch (Exception $e) {
            dd($e->getMessage());
            return response( 'sorry, please try later', 400 );
        }

        return response( ['view' => $view], 200 );
    }

    public function destroyFile(Request $request)
    {
//        dd($request->all());
        $validator = Validator::make( $request->all(), [
            'id' => 'required|integer|exists:tax_card_libraries,id',
        ] );

        if ($validator->fails()) {
            return response( $validator->errors()->first(), 400 );
        }

        try {
            $file = TaxCardLibrary::find( $request['id'] );
            $supplier = $file->tax_card;
            $filePath = storage_path( 'app/public/tax_card_library/' . $supplier->library_path . '/' . $file->file_name );
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
