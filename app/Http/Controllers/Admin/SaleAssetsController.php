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
            $saleAssets = SaleAsset::select( ['*'] );
            return DataTables::of( $saleAssets )
                ->addIndexColumn()
                ->addColumn( 'number', function ($saleAsset) {
                    return $saleAsset->number;

                } )
                ->addColumn( 'type', function ($saleAsset) {
                    return $saleAsset->type;

                } )
                ->addColumn('date',function ($saleAsset){
                    return $saleAsset->date .' '. $saleAsset->time;
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
                'number' => 'sale_assets.number',
                'type' => 'sale_assets.type',
                'date' => 'sale_assets.date',
                'action' => 'action',
                'options' => 'options'
            ];
            return view( 'admin.sale-assets.index', compact( 'js_columns' ) );
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
}
