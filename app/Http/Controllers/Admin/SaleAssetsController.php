<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Asset\ConsumptionAssetRequest;
use App\Http\Requests\Admin\Asset\PurchaseAssetRequest;
use App\Http\Requests\Admin\Asset\SaleAssetRequest;
use App\Models\Asset;
use App\Models\AssetEmployee;
use App\Models\AssetGroup;
use App\Models\AssetType;
use App\Models\ConsumptionAsset;
use App\Models\ConsumptionAssetItem;
use App\Models\EmployeeData;
use App\Models\PurchaseAsset;
use App\Models\PurchaseAssetItem;
use App\Models\SaleAsset;
use App\Models\SaleAssetItem;
use Carbon\Carbon;
use Exception;
use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class SaleAssetsController extends Controller
{

    public $lang;


    public function __construct()
    {

        $this->lang = App::getLocale();
    }

    public function index(Request $request)
    {
        if ($request->isDataTable) {
            $saleAssets = SaleAsset::with('items')->select( ['*'] );
//            $saleAssets = SaleAsset::select( [
//                'sale_assets.id',
//                'number',
//                'branch_id',
//                'date',
//                'time',
//                'type',
//                'total_sale_amount',
//                'created_at',
//                'updated_at',
//            ] )->leftjoin( 'sale_asset_items', 'sale_assets.id', '=', 'sale_asset_items.sale_asset_id' );

            if ($request->has( 'branch_id' ) && !empty( $request['branch_id'] ))
                $saleAssets->where( 'sale_assets.branch_id', $request['branch_id'] );
            if ($request->has( 'number' ) && !empty( $request['number'] ))
                $saleAssets->where( 'sale_assets.number', $request['number'] );

            if ($request->has( 'type' ) && !empty( $request['type'] ))
                $saleAssets->where( 'sale_assets.type', $request['type'] );

            if ($request->has( 'asset_group_id' ) && !empty( $request->asset_group_id )) {
                $saleAssets->whereHas('items', function ($query) use($request) {
                    $query->where( 'asset_group_id', $request['asset_group_id'] );
                });
            }

            if ($request->has( 'asset_id' ) && !empty( $request->asset_id)) {
                $saleAssets->whereHas('items', function ($query) use($request) {
                    $query->where( 'asset_id', $request['asset_group_id'] );
                });
            }

            whereBetween($saleAssets,'DATE(date)',$request->date_from,$request->date_to);
            whereBetween( $saleAssets, 'sale_asset_items.sale_amount', $request->sale_amount_from, $request->sale_amount_to );
            return DataTables::of( $saleAssets )
                ->addIndexColumn()
                ->addColumn( 'branch_id', function ($asset) {
                    return '<span class="text-danger">' . optional( $asset->branch )->name . '</span>';

                } )
                ->addColumn('date',function ($saleAsset){
                    return '<span class="text-danger">' .$saleAsset->date .' '. $saleAsset->time . '</span>';
                })

                ->addColumn( 'number', function ($saleAsset) {
                    return $saleAsset->number;

                } )
                ->addColumn( 'type', function ($saleAsset) {
                    return ($saleAsset->type === 'sale' ?  __('Sale') : __('exclusion'));
                } )
                ->addColumn( 'total_sale_amount', function ($saleAsset) {
                    return $saleAsset->total_sale_amount;

                } )
                ->addColumn('date',function ($saleAsset){
                    return $saleAsset->date .' '. $saleAsset->time;
                })
                ->addColumn('created_at',function ($saleAsset){
                    return $saleAsset->created_at;
                })
                ->addColumn('updated_at',function ($saleAsset){
                    return $saleAsset->updated_at;
                })
                ->addColumn( 'action', function ($saleAsset) {
                    return '
                      <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i> ' . __( "Options" ) . '<span class="caret"></span></button>
                                          <ul class="dropdown-menu dropdown-wg">
                                            <li> <a class="btn btn-wg-edit hvr-radial-out" href="' . route( "admin:sale-assets.edit", $saleAsset->id ) . '">
    <i class="fa fa-edit"></i>  ' . __( 'Edit' ) . '
        </a></li>
        <li class="btn-style-drop">
        <button type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete(' . $saleAsset->id . ')">
            <i class="fa fa-trash"></i>  ' . __( 'Delete' ) . '
        </button>

        <form style="display: none" method="POST" id="confirmDelete' . $saleAsset->id . '" action="' . route( 'admin:sale-assets.destroy', $saleAsset->id ) . '">
            <input type="hidden" name="_method" value="DELETE">
           ' . @csrf_field() . '
        </form>
        </li>
        <li>
        <a style="cursor:pointer" class="btn btn-print-wg text-white  "
           data-toggle="modal" onclick="getPrintData(' . $saleAsset->id . ')"
           data-target="#boostrapModal" title="' . __( 'print' ) . '">
            <i class="fa fa-print"></i> ' . __( 'Print' ) . '</a>
        </li>
          </ul> </div>
                 ';
                } )->addColumn( 'options', function ($saleAsset) {
                    return '
                    <form action=' . route( "admin:sale-assets.deleteSelected" ) . ' method="post" id="deleteSelected">
                    ' . @csrf_field() . '
        <div class="checkbox danger">
        <input type="checkbox" name="ids[]" value="' . $saleAsset->id . '" id="checkbox-' . $saleAsset->id . '">
        <label for="checkbox-' . $saleAsset->id . '"></label>
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
                'branch_id' => 'sale_assets.branch_id',
                'number' => 'sale_assets.number',
                'type' => 'sale_assets.type',
                'total_sale_amount' => 'sale_assets.total_sale_amount',
                'date' => 'sale_assets.date',
                'created_at' => 'sale_assets.created_at',
                'updated_at' => 'sale_assets.updated_at',
                'action' => 'action',
                'options' => 'options'
            ];
//=======
//            if(authIsSuperAdmin()) {
//                $js_columns = [
//                    'DT_RowIndex' => 'DT_RowIndex',
//                    'branch_id' => 'sale_assets.branch_id',
//                    'date' => 'sale_assets.date',
//                    'number' => 'sale_assets.number',
//                    'type' => 'sale_assets.type',
//
//                    'action' => 'action',
//                    'options' => 'options'
//                ];
//            }else{
//                $js_columns = [
//                    'DT_RowIndex' => 'DT_RowIndex',
//                    'date' => 'sale_assets.date',
//                    'number' => 'sale_assets.number',
//                    'type' => 'sale_assets.type',
//
//                    'action' => 'action',
//                    'options' => 'options'
//                ];
//            }
//>>>>>>> 882dcff785aabd81208032c13827138a732a5689
            $assets = Asset::all();
            $branches = Branch::all()->pluck( 'name', 'id' );
            $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
            $numbers = SaleAsset::select( 'number' )->orderBy( 'number', 'asc' )->get();
            return view( 'admin.sale-assets.index', compact( 'js_columns' ,'assets','assetsGroups','numbers','branches') );
        }

    }

    public function create(Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        $data['branches'] = Branch::where( 'status', 1 )->select( 'id', 'name_' . $this->lang )->get();
        $lastNumber = SaleAsset::where( 'branch_id', $branch_id )->orderBy( 'id', 'desc' )->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        return view( 'admin.sale-assets.create', compact( 'data', 'number', 'assetsGroups', 'assets' ) );
    }

    public function store(SaleAssetRequest $request)
    {

        if (!isset( $request->items )) {
            return redirect()->to( route( 'admin:sale-assets.create' ) )
                ->withInput( $request->all() )
                ->with( ['message' => __( 'items is required' ), 'alert-type' => 'error'] );
        }
        DB::beginTransaction();
        try {
            $data = $request->all();
            $invoice_data = [
                'number' => $data['number'],
                'date' => $data['date'],
                'time' => $data['time'],
                'note' => $data['note'],
                'type' => $data['type'],
                'total_purchase_cost' => $request->total_purchase_cost,
                'total_past_consumtion' => $request->total_past_consumtion,
                'total_replacement' => $request->total_replacement,
                'total_current_consumtion' => $request->total_current_consumtion,
                'final_total_consumtion' => $request->final_total_consumtion,
                'total_sale_amount' => $request->total_sale_amount
            ];
            $invoice_data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $saleAsset = SaleAsset::create( $invoice_data );

            foreach ($data['items'] as $item) {
                $total = $item['purchase_cost'] + $item['replacement_cost'];
                if ($total >= $item['sale_amount']) {
                    $status = 'lost';
                } else {
                    $status = 'gain';
                }
                $asset = Asset::find( $item['asset_id'] );
                if ($data['type'] =='sale') {
                    $asset->update( ['asset_status' =>2] );
                    }elseif ($data['type'] =='exclusion'){
                    $asset->update( ['asset_status' =>3] );
                }

                SaleAssetItem::create( [
                    'sale_asset_id' => $saleAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'sale_amount' => $item['sale_amount'],
                    'sale_status' => $status
                ] );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to( route( 'admin:sale-assets.create' ) )
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }

        return redirect()->to( route( 'admin:sale-assets.index' ) )
            ->with( ['message' => __( 'words.sale-assets-created' ), 'alert-type' => 'success'] );

    }

    public function show(SaleAsset $saleAsset, Request $request)
    {
        $asset = SaleAsset::find( $request->id );
        $view = view( 'admin.sale-assets.show', compact( 'asset' ) )->render();

        return response()->json( ['view' => $view] );
    }

    public function edit(SaleAsset $saleAsset)
    {
        $data['branches'] = Branch::where( 'status', 1 )->select( 'id', 'name_' . $this->lang )->get();
        $branch_id = $saleAsset->branch_id;
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        return view( 'admin.sale-assets.edit', compact( 'data', 'saleAsset', 'assets', 'assetsGroups' ) );
    }

    public function update(SaleAssetRequest $request, SaleAsset $saleAsset)
    {
        if (!isset( $request->items )) {
            return redirect()->to( route( 'admin:sale-assets.edit', $saleAsset->id ) )
                ->withInput( $request->all() )
                ->with( ['message' => __( 'items is required' ), 'alert-type' => 'error'] );
        }
        DB::beginTransaction();
        try {
            $data = $request->all();
            $invoice_data = [
                'number' => $data['number'],
                'date' => $data['date'],
                'time' => $data['time'],
                'note' => $data['note'],
                'type' => $data['type'],
                'total_purchase_cost' => $request->total_purchase_cost,
                'total_past_consumtion' => $request->total_past_consumtion,
                'total_replacement' => $request->total_replacement,
                'total_current_consumtion' => $request->total_current_consumtion,
                'final_total_consumtion' => $request->final_total_consumtion,
                'total_sale_amount' => $request->total_sale_amount
            ];

            $saleAsset->update( $invoice_data );
            $saleAsset->items()->delete();
            foreach ($data['items'] as $item) {
                $asset = Asset::find( $item['asset_id'] );

                $total = $item['purchase_cost'] + $item['replacement_cost'];
                if ($total >= $item['sale_amount']) {
                    $status = 'lost';
                } else {
                    $status = 'gain';
                }
                if ($data['type'] =='sale') {
                    $asset->update( ['asset_status' =>2] );
                }elseif ($data['type'] =='exclusion'){
                    $asset->update( ['asset_status' =>3] );
                }

                SaleAssetItem::create( [
                    'sale_asset_id' => $saleAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'sale_amount' => $item['sale_amount'],
                    'sale_status' => $status
                ] );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to( route( 'admin:sale-assets.edit', $saleAsset->id ) )
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }

        return redirect()->to( route( 'admin:sale-assets.index' ) )
            ->with( ['message' => __( 'words.sale-assets-created' ), 'alert-type' => 'success'] );

    }

    public function destroy(SaleAsset $saleAsset)
    {

        $saleAsset->delete();
        $saleAsset->items()->delete();

        return redirect()->back()
            ->with( ['message' => __( 'words.sale-asset-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {

            foreach (array_unique( $request->ids ) as $invoiceId) {

                $saleAsset = SaleAsset::find( $invoiceId );
                $saleAsset->delete();
                $saleAsset->items()->delete();

            }

            return redirect()->back()
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }

        return redirect()->back()
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function getAssetsByAssetId(Request $request): JsonResponse
    {
        if (is_null( $request->asset_id )) {
            return response()->json( __( 'please select valid Asset' ), 400 );
        }
        if (is_null( $request->branch_id ) && authIsSuperAdmin()) {
            return response()->json( __( 'please select valid branch' ), 400 );
        }
        $index = $request['index'] + 1;

        $asset = Asset::with( 'group' )->find( $request->asset_id );
        $assetGroup = $asset->group;
        $view = view( 'admin.sale-assets.row',
            compact( 'asset', 'index', 'assetGroup' )
        )->render();
        return response()->json( [
            'items' => $view,
            'index' => $index
        ] );
    }
    public function getNumbersByBranchId(Request $request): JsonResponse
    {
        if (!empty( $request->branch_id )) {
            $numbers = SaleAsset::where( 'branch_id', $request->branch_id )->pluck( 'number' )->unique();
        } else {
            $numbers = SaleAsset::pluck( 'number' )->unique();
        }
        if ($numbers) {
            $numbers_data = view( 'admin.sale-assets.invoice_number_by_branch_id', compact( 'numbers' ) )->render();
            return response()->json( [
                'data' => $numbers_data,
            ] );
        }
    }
}
