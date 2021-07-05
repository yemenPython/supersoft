<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetRequest;
use App\Models\AssetExpense;
use App\Models\AssetExpenseItem;
use App\Models\AssetGroup;
use App\Models\AssetEmployee;
use App\Models\AssetInsurance;
use App\Models\AssetExamination;
use App\Models\AssetLicense;
use App\Models\AssetReplacementItem;
use App\Models\AssetType;
use App\Models\Asset;
use App\Models\Branch;
use App\Models\Car;
use App\Models\EmployeeData;
use App\Models\PurchaseAssetItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use \Yajra\DataTables\DataTables;

class AssetsController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_currencies');
//        $this->middleware('permission:create_currencies',['only'=>['create','store']]);
//        $this->middleware('permission:update_currencies',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_currencies',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if ($request->isDataTable) {
            $assets = Asset::select( [
                'id',
                'branch_id',
                'name_' . app()->getLocale(),
                'asset_group_id',
                'asset_status',
                'annual_consumtion_rate',
                'asset_age',
                'created_at',
                'updated_at',
            ] )->orderBy('id','desc');
            if ($request->has( 'name' ) && !empty( $request['name'] ))
                $assets->where( 'id', [$request->name] );

            if ($request->has( 'branch_id' ) && !empty( $request['branch_id'] ))
                $assets->where( 'branch_id', $request['branch_id'] );

            if ($request->has( 'asset_group_id' ) && !empty( $request->asset_group_id ))
                $assets->where( 'asset_group_id', $request['asset_group_id'] );

            if ($request->has( 'asset_type_id' ) && !empty( $request['asset_type_id'] ))
                $assets->where( 'asset_type_id', $request['asset_type_id'] );

            if ($request->has( 'annual_consumtion_rate' ) && !empty( $request['annual_consumtion_rate'] ))
                $assets->where( 'annual_consumtion_rate', $request['annual_consumtion_rate'] );

            if ($request->has( 'asset_age' ) && !empty( $request['asset_age'] ))
                $assets->where( 'asset_age', $request['asset_age'] );

            if ($request->has( 'purchase_cost' ) && !empty( $request['purchase_cost'] ))
                $assets->where( 'purchase_cost', $request['purchase_cost'] );

            if ($request->has( 'purchase_date' ) && !empty( $request['purchase_date'] ))
                $assets->where( 'purchase_date', $request['purchase_date'] );

            if ($request->has( 'asset_status' ) && !empty( $request['asset_status'] ))
                $assets->where( 'asset_status', $request['asset_status'] );

            if ($request->has( 'employee_id' ) && !empty( $request['employee_id'] )) {
//           $asset_ids = AssetEmployee::where('employee_id',$request->employee_id)->pluck('asset_id');
//            $assets->whereIn( 'id', $asset_ids );
                $assets->whereHas(
                    'asset_employees',
                    function ($query) use ($request) {
                        $query->where( 'employee_id', $request->employee_id );
                    }
                );
            }
            whereBetween( $assets, 'DATE(purchase_date)', $request->purchase_date1, $request->purchase_date2 );
            whereBetween( $assets, 'DATE(date_of_work)', $request->date_of_work1, $request->date_of_work2 );
            whereBetween( $assets, 'asset_age', $request->asset_age1, $request->asset_age2 );
            whereBetween( $assets, 'purchase_cost', $request->purchase_cost1, $request->purchase_cost2 );
            whereBetween( $assets, 'annual_consumtion_rate', $request->annual_consumtion_rate1, $request->annual_consumtion_rate2 );
            return DataTables::of( $assets )
                ->addIndexColumn()
                ->addColumn( 'branch_id', function ($asset) {
                    return '<span class="text-danger">' . optional( $asset->branch )->name . '</span>';

                } )
                ->addColumn( 'name', function ($asset) {
                    return $asset->{'name_' . app()->getLocale()};
                } )
                ->addColumn( 'asset_group_id', function ($asset) {
                    return optional( $asset->group )->name;
                } )
                ->editColumn( 'asset_status', function ($asset) {
                    if ($asset->asset_status == 1) {
                        return '<span class="label label-info wg-label">' . __( 'continues' ) . '</span>';
                    } elseif ($asset->asset_status == 1) {
                        return '<span class="label label-info wg-label">' . __( 'sell' ) . '</span>';
                    } else {
                        return '<span class="label label-info wg-label">' . __( 'ignore' ) . '</span>';
                    }
                } )
                ->editColumn( 'annual_consumtion_rate', function ($asset) {
                    return ' <span class="price-span">' . $asset->annual_consumtion_rate . '%' . '</span>';
                } )
                ->editColumn( 'asset_age', function ($asset) {
                    return '<span class="part-unit-span">' . $asset->asset_age . __( 'year' ) . '</span>';
                } )
                ->editColumn( 'created_at', '{{$created_at}}' )
                ->editColumn( 'updated_at', '{{$updated_at}}' )
                ->addColumn( 'action', function ($asset) {
                    return '
                      <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i> ' . __( "Options" ) . '<span class="caret"></span></button>
                                          <ul class="dropdown-menu dropdown-wg">
                                            <li> <a class="btn btn-wg-edit hvr-radial-out" href="' . route( "admin:assets.edit", $asset->id ) . '">
    <i class="fa fa-edit"></i>  ' . __( 'Edit' ) . '
        </a></li>
        <li class="btn-style-drop">
        <button type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete(' . $asset->id . ')">
            <i class="fa fa-trash"></i>  ' . __( 'Delete' ) . '
        </button>

        <form style="display: none" method="POST" id="confirmDelete'.$asset->id.'" action="' . route( 'admin:assets.destroy', $asset->id ) . '">
            <input type="hidden" name="_method" value="DELETE">
           ' . @csrf_field() . '
        </form>
        </li>
        <li>
        <a style="cursor:pointer" class="btn btn-print-wg text-white  "
           data-toggle="modal" onclick="getPrintData(' . $asset->id . ')"
           data-target="#boostrapModal" title="' . __( 'print' ) . '">
            <i class="fa fa-print"></i> ' . __( 'Print' ) . '</a>
        </li>
        <li><a class="btn btn-wg-show hvr-radial-out"z href="' . route( 'admin:assetsEmployees.index', $asset->id ) . '" >
            <i class="fa fa-eye"></i>' . __( 'employees history' ) . '
        </a></li>
        <li><a class="btn btn-wg-show hvr-radial-out"z href="' . route( 'admin:assetsInsurances.index', $asset->id ) . '" >
            <i class="fa fa-eye"></i>' . __( 'insurances' ) . '
        </a></li>
        <li><a class="btn btn-wg-show hvr-radial-out"z href="' . route( 'admin:assetsLicenses.index', $asset->id ) . '" >
            <i class="fa fa-eye"></i>' . __( 'licenses' ) . '
        </a></li>
        <li><a class="btn btn-wg-show hvr-radial-out"z href="' . route( 'admin:assetsExaminations.index', $asset->id ) . '" >
            <i class="fa fa-eye"></i>' . __( 'examinations' ) . '
        </a></li>
          </ul> </div>
                 ';
                } )->addColumn( 'options', function ($asset) {
                    return '
                    <form action=' . route( "admin:assets.deleteSelected" ) . ' method="post" id="deleteSelected">
                    ' . @csrf_field() . '
        <div class="checkbox danger">
        <input type="checkbox" name="ids[]" value="' . $asset->id . '" id="checkbox-' . $asset->id . '">
        <label for="checkbox-' . $asset->id . '"></label>
          </div>
            </form>
                    ';
                } )
                ->rawColumns( ['action'] )
                ->rawColumns( ['actions'] )
                ->escapeColumns( [] )
                ->make( true );
        } else {
            $js_columns = [
                'DT_RowIndex' => 'DT_RowIndex',
                'branch_id' => 'assets_tb.branch_id',
                'name' => 'assets_tb.name_' . app()->getLocale(),
                'asset_group_id' => 'assets_tb.asset_group_id',
                'asset_status' => 'assets_tb.asset_status',
                'annual_consumtion_rate' => 'assets_tb.annual_consumtion_rate',
                'asset_age' => 'assets_tb.asset_age',
                'created_at' => 'assets_tb.created_at',
                'updated_at' => 'assets_tb.updated_at',
                'action' => 'action',
                'options' => 'options'
            ];


            $assets = Asset::all();
            $branches = Branch::all()->pluck( 'name', 'id' );
            $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
            $assetsTypes = AssetType::select( ['id', 'name_ar', 'name_en'] )->get();
            $assetEmployees = EmployeeData::select( ['id', 'name_ar', 'name_en'] )
                ->whereIn( 'id', AssetEmployee::pluck( 'employee_id' ) )
                ->get();
            return view( 'admin.assets.index', compact( 'assets', 'branches', 'assetsGroups', 'assetsTypes', 'assetEmployees', 'js_columns' ) );
        }
    }

    public function indexold(Request $request)
    {
        $assets = Asset::orderBy( 'id', 'desc' );
//        dd( $request->all() );
        if ($request->has( 'name' ) && !empty( $request['name'] ))
            $assets->where( 'id', [$request->name] );

        if ($request->has( 'branch_id' ) && !empty( $request['branch_id'] ))
            $assets->where( 'branch_id', $request['branch_id'] );

        if ($request->has( 'asset_group_id' ) && !empty( $request->asset_group_id ))
            $assets->where( 'asset_group_id', $request['asset_group_id'] );

        if ($request->has( 'asset_type_id' ) && !empty( $request['asset_type_id'] ))
            $assets->where( 'asset_type_id', $request['asset_type_id'] );

        if ($request->has( 'annual_consumtion_rate' ) && !empty( $request['annual_consumtion_rate'] ))
            $assets->where( 'annual_consumtion_rate', $request['annual_consumtion_rate'] );

        if ($request->has( 'asset_age' ) && !empty( $request['asset_age'] ))
            $assets->where( 'asset_age', $request['asset_age'] );

        if ($request->has( 'purchase_cost' ) && !empty( $request['purchase_cost'] ))
            $assets->where( 'purchase_cost', $request['purchase_cost'] );

        if ($request->has( 'purchase_date' ) && !empty( $request['purchase_date'] ))
            $assets->where( 'purchase_date', $request['purchase_date'] );

        if ($request->has( 'asset_status' ) && !empty( $request['asset_status'] ))
            $assets->where( 'asset_status', $request['asset_status'] );

        if ($request->has( 'employee_id' ) && !empty( $request['employee_id'] )) {
//           $asset_ids = AssetEmployee::where('employee_id',$request->employee_id)->pluck('asset_id');
//            $assets->whereIn( 'id', $asset_ids );
            $assets->whereHas(
                'asset_employees',
                function ($query) use ($request) {
                    $query->where( 'employee_id', $request->employee_id );
                }
            );
        }

        $branches = Branch::all()->pluck( 'name', 'id' );

        $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsTypes = AssetType::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetEmployees = EmployeeData::select( ['id', 'name_ar', 'name_en'] )
            ->whereIn( 'id', AssetEmployee::pluck( 'employee_id' ) )
            ->get();
        whereBetween( $assets, 'DATE(purchase_date)', $request->purchase_date1, $request->purchase_date2 );
        whereBetween( $assets, 'DATE(date_of_work)', $request->date_of_work1, $request->date_of_work2 );
        whereBetween( $assets, 'asset_age', $request->asset_age1, $request->asset_age2 );
        whereBetween( $assets, 'purchase_cost', $request->purchase_cost1, $request->purchase_cost2 );
        whereBetween( $assets, 'annual_consumtion_rate', $request->annual_consumtion_rate1, $request->annual_consumtion_rate2 );

        $assets = $assets->get();

        return view( 'admin.assets.index', compact( 'assets', 'branches', 'assetsGroups', 'assetsTypes', 'assetEmployees' ) );
    }

    public function show(Request $request)
    {

        $asset = Asset::where( "id", $request['asset_id'] )->first();
        $assetEmployees = AssetEmployee::where( "asset_id", $asset->id )->get();
        $assetInsurances = AssetInsurance::where( "asset_id", $asset->id )->get();
        $assetExaminations = AssetExamination::where( "asset_id", $asset->id )->get();
        $assetLicenses = AssetLicense::where( "asset_id", $asset->id )->get();
        $assetType = AssetType::where( "id", $asset->asset_type_id )->first();
        $view = view( 'admin.assets.show', compact( "asset", "assetType", "assetEmployees", "assetInsurances", "assetExaminations", "assetLicenses" ) )->render();

        return response()->json( ['view' => $view] );
    }

    public function create()
    {
        $branches = Branch::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsTypes = AssetType::select( ['id', 'name_ar', 'name_en'] )->get();
        return view( 'admin.assets.create', compact( "assetsGroups", "branches", "assetsTypes" ) );
    }

    public function store(AssetRequest $request)
    {
        $asset_group = AssetGroup::find( $request->asset_group_id );
        if ($request->purchase_cost > 0 && $request->annual_consumtion_rate > 0 && ($request->purchase_cost / $request->annual_consumtion_rate) > 0) {
            $asset_age = ($request->purchase_cost / $request->annual_consumtion_rate) / 100;
        } else {
            $asset_age = 0;
        }
        Asset::create( [
            'asset_age' => $asset_age,
            'branch_id' => $request->branch_id,
            'annual_consumtion_rate' => $request->annual_consumtion_rate,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'asset_group_id' => $request->asset_group_id,
            'asset_type_id' => $request->asset_type_id,
            'asset_status' => $request->asset_status,
            'asset_details' => $request->asset_details,
            'purchase_date' => $request->purchase_date,
            'date_of_work' => $request->date_of_work,
            'purchase_cost' => $request->purchase_cost,
            'user_id' => Auth::id(),
        ] );
        return redirect()->to( 'admin/assets' )
            ->with( ['message' => __( 'words.asset-created' ), 'alert-type' => 'success'] );
    }

    public function edit(asset $asset)
    {
        $branches = Branch::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
        $assetsTypes = AssetType::select( ['id', 'name_ar', 'name_en'] )->get();
        return view( 'admin.assets.edit', compact( 'asset', "assetsGroups", "branches", "assetsTypes" ) );
    }

    public function update(AssetRequest $request, asset $asset)
    {
        // dd($request->all(),$asset);
        $asset_group = AssetGroup::find( $request->asset_group_id );
        if ($request->purchase_cost > 0 && $request->annual_consumtion_rate > 0 && ($request->purchase_cost / $request->annual_consumtion_rate) > 0) {
            $asset_age = ($request->purchase_cost / $request->annual_consumtion_rate) / 100;
        } else {
            $asset_age = 0;
        }
   
        $asset->update( [
            'asset_age' => $asset_age,
            'branch_id' => $request->branch_id,
            'annual_consumtion_rate' => $request->annual_consumtion_rate,
            'name_ar' => $request->name_ar,
            'name_en' => $request->name_en,
            'asset_group_id' => $request->asset_group_id,
            'asset_type_id' => $request->asset_type_id,
            'asset_status' => $request->asset_status,
            'asset_details' => $request->asset_details,
            'purchase_date' => $request->purchase_date,
            'date_of_work' => $request->date_of_work,
            'purchase_cost' => $request->purchase_cost,
            'user_id' => auth()->id(),
        ] );
        return redirect()->to( 'admin/assets' )
            ->with( ['message' => __( 'words.asset-updated' ), 'alert-type' => 'success'] );
        // return redirect()->to('admin/assets-groups')
        //     ->with(['message' => __('words.asset-group-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Asset $asset)
    {
        $purchase_assets = PurchaseAssetItem::where( 'asset_id', $asset->id )->count();
        $purchase_expenses = AssetExpenseItem::where( 'asset_id', $asset->id )->count();
        $purchase_replacemens = AssetReplacementItem::where( 'asset_id', $asset->id )->count();
        if ($purchase_assets || $purchase_expenses || $purchase_replacemens) {
            return redirect()->to( 'admin/assets' )
                ->with( ['message' => __( 'words.can-not-delete-this-data-cause-there-is-related-data' ), 'alert-type' => 'error'] );
        }
        // delete Asset Insurances
        AssetInsurance::where( "asset_id", $asset->id )->delete();
        // delete Asset Examinations
        AssetExamination::where( "asset_id", $asset->id )->delete();
        // delete Asset Licenses
        AssetLicense::where( "asset_id", $asset->id )->delete();

        // delete Asset Employees
        AssetEmployee::where( "asset_id", $asset->id )->delete();
        // delete Asset
        $asset->delete();

        return redirect()->to( 'admin/assets' )
            ->with( ['message' => __( 'words.asset-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {

        $request->ids = array_unique( $request->ids );
        if (isset( $request->ids )) {
            // delete Asset Insurances
            foreach ($request->ids as $id) {
                $purchase_assets = PurchaseAssetItem::where( 'asset_id', $id )->count();
                $purchase_expenses = AssetExpenseItem::where( 'asset_id', $id )->count();
                $purchase_replacemens = AssetReplacementItem::where( 'asset_id', $id )->count();
                if ($purchase_assets || $purchase_expenses || $purchase_replacemens) {
                    return redirect()->to( 'admin/assets' )
                        ->with( ['message' => __( 'words.can-not-delete-this-data-cause-there-is-related-data' ), 'alert-type' => 'error'] );
                } else {
                    AssetInsurance::where( "asset_id", $id )->delete();
                    // delete Asset Examinations
                    AssetExamination::where( "asset_id", $id )->delete();
                    // delete Asset Licenses
                    AssetLicense::where( "asset_id", $id )->delete();

                    // delete Asset Employees
                    AssetEmployee::where( "asset_id", $id )->delete();
                    // delete Asset
                    Asset::where( "id", $id )->delete();

                }
                return redirect()->to( 'admin/assets' )
                    ->with( ['message' => __( 'words.selected-rows-delete' ), 'alert-type' => 'success'] );
            }
        }
        return redirect()->to( 'admin/assets' )
            ->with( ['message' => __( 'words.no-data-delete' ), 'alert-type' => 'error'] );
    }

    public function getAssetsGroupsByBranchId(Request $request): JsonResponse
    {
        if (is_null( $request->branch_id )) {
            return response()->json( __( 'please select valid Branch' ), 400 );
        }
        if ($assets_groups = AssetGroup::where( 'branch_id', $request->branch_id )->get()) {
            $assets_groups_data = view( 'admin.assets.asset_groups_by_branch_id', compact( 'assets_groups' ) )->render();
            return response()->json( [
                'data' => $assets_groups_data,
            ] );
        }
    }

    public function getAssetsTypesByBranchId(Request $request): JsonResponse
    {
        if (is_null( $request->branch_id )) {
            return response()->json( __( 'please select valid Branch' ), 400 );
        }
        if ($assets_types = AssetType::where( 'branch_id', $request->branch_id )->get()) {
            $assets_types_data = view( 'admin.assets.asset_types_by_branch_id', compact( 'assets_types' ) )->render();
            return response()->json( [
                'data' => $assets_types_data,
            ] );
        }
    }

    public function getEmployeesByBranchId(Request $request): JsonResponse
    {
//        if (is_null( $request->branch_id )) {
//            return response()->json( __( 'please select valid Branch' ), 400 );
//        }
        if ($assetEmployees = EmployeeData::where( 'branch_id', $request->branch_id )->get()) {
            $employees_data = view( 'admin.assets.employees_by_branch_id', compact( 'assetEmployees' ) )->render();
            return response()->json( [
                'data' => $employees_data,
            ] );
        }
    }

    public function getAssetsByBranchId(Request $request): JsonResponse
    {
        if ($assets = Asset::where( 'branch_id', $request->branch_id )->get()) {
            $assets_data = view( 'admin.assets.asset_by_branch_id', compact( 'assets' ) )->render();
            return response()->json( [
                'data' => $assets_data,
            ] );
        }
    }

    public function getAssetsByAssetsType(Request $request): JsonResponse
    {
        if (!empty( $request->asset_type_id )) {
            $assets = Asset::where( 'asset_type_id', $request->asset_type_id )->get();
        } else {
            $assets = Asset::all();
        }
        if ($assets) {
            $assets_data = view( 'admin.assets.asset_by_branch_id', compact( 'assets' ) )->render();
            return response()->json( [
                'data' => $assets_data,
            ] );
        }
    }

    public function getAssetsByAssetsGroup(Request $request): JsonResponse
    {
        if (!empty( $request->asset_group_id )) {
            $assets = Asset::where( 'asset_group_id', $request->asset_group_id )->get();
        } else {
            $assets = Asset::all();
        }
        if ($assets) {
            $assets_data = view( 'admin.assets.asset_by_branch_id', compact( 'assets' ) )->render();
            return response()->json( [
                'data' => $assets_data,
            ] );
        }
    }


    public function getAssetsGroupsAnnualConsumtionRate(Request $request)
    {
        if (is_null( $request->asset_group_id )) {
            return response()->json( __( 'please select valid assets group type' ), 400 );
        }
        if ($annual_consumtion_rate = AssetGroup::find( $request->asset_group_id )->annual_consumtion_rate) {
            return ['status' => true, 'annual_consumtion_rate' => $annual_consumtion_rate];
        }
    }

}
