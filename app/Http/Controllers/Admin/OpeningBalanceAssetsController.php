<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Asset\OpeningBalanceAssetRequest;
use App\Http\Requests\Admin\Asset\PurchaseAssetRequest;
use App\Models\Asset;
use App\Models\AssetExpenseItem;
use App\Models\AssetGroup;
use App\Models\AssetReplacementItem;
use App\Models\ConsumptionAsset;
use App\Models\ConsumptionAssetItem;
use App\Models\PurchaseAsset;
use App\Models\PurchaseAssetItem;
use App\Models\SaleAssetItem;
use App\Models\StopAndActivateAsset;
use Exception;
use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class OpeningBalanceAssetsController extends Controller
{

    protected $purchaseInvoiceItemsController;
    public $lang;


    public function __construct()
    {

        $this->lang = App::getLocale();
    }

    public function index(Request $request)
    {
        if ($request->isDataTable) {
            $purchaseAssets = PurchaseAsset::select( [
                'purchase_assets.id',
                'purchase_assets.invoice_number',
                'purchase_assets.supplier_id',
                'purchase_assets.branch_id',
                'purchase_assets.date',
                'purchase_assets.time',
                'purchase_assets.created_at',
                'purchase_assets.updated_at',
                'purchase_assets.remaining_amount',
                'purchase_assets.paid_amount',
                'purchase_assets.total_purchase_cost',
                'purchase_assets.total_past_consumtion',
                'purchase_assets.type',
                'purchase_assets.operation_type',
            ] )->where( 'operation_type', '=', 'opening_balance' )
                ->leftjoin( 'purchase_asset_items', 'purchase_assets.id', '=', 'purchase_asset_items.purchase_asset_id' )->latest();

            if ($request->has( 'branch_id' ) && !empty( $request['branch_id'] ))
                $purchaseAssets->where( 'purchase_assets.branch_id', $request['branch_id'] );

            if ($request->has( 'supplier_id' ) && !empty( $request['supplier_id'] ))
                $purchaseAssets->where( 'purchase_assets.supplier_id', $request['supplier_id'] );

            if ($request->has( 'type' ) && !empty( $request['type'] ))
                $purchaseAssets->where( 'purchase_assets.type', $request['type'] );

            if ($request->has( 'invoice_number' ) && !empty( $request['invoice_number'] ))
                $purchaseAssets->where( 'purchase_assets.invoice_number', $request['invoice_number'] );


            if ($request->has( 'asset_group_id' ) && !empty( $request->asset_group_id ))
                $purchaseAssets->where( 'purchase_asset_items.asset_group_id', $request['asset_group_id'] );

            if ($request->has( 'asset_id' ) && !empty( $request->asset_id ))
                $purchaseAssets->where( 'purchase_asset_items.asset_id', $request['asset_id'] );

            if ($request->filled( 'operation_type' ) && $request->operation_type != 'together') {
                $purchaseAssets->where( 'purchase_assets.operation_type', $request['operation_type'] );
            }

            if ($request->filled( 'operation_type' ) && $request->operation_type == 'together') {
                $purchaseAssets->whereIn( 'purchase_assets.operation_type', ['purchase', 'opening_balance'] );
            }

            whereBetween( $purchaseAssets, 'DATE(purchase_assets.date)', $request->date_from, $request->date_to );
            whereBetween( $purchaseAssets, 'purchase_asset_items.sale_amount', $request->sale_amount_from, $request->sale_amount_to );

            return DataTables::of( $purchaseAssets->groupBy( 'purchase_assets.id' ) )
                ->addIndexColumn()
                ->addColumn( 'branch_id', function ($asset) {
                    return '<span class="text-danger">' . optional( $asset->branch )->name . '</span>';
                } )
                ->addColumn( 'date', function ($saleAsset) {
                    return '<span class="text-danger">' . $saleAsset->date . ' ' . $saleAsset->time . '</span>';
                } )
                ->addColumn( 'invoice_number', function ($saleAsset) {
                    return $saleAsset->invoice_number;

                } )
                ->addColumn( 'supplier_id', function ($purchaseAsset) {
                    return optional( $purchaseAsset->supplier )->name;
                } )
                ->addColumn( 'type', function ($purchaseAsset) {

                    if ($purchaseAsset->type == 'cash') {
                        return '<span class="label label-primary wg-label">' . __( 'Cash' ) . '</span>';
                    } else {
                        return '<span class="label label-info wg-label">' . __( 'Credit' ) . '</span>';
                    }

                } )
                ->addColumn( 'total_purchase_cost', function ($purchaseAsset) {
                    return '<span style="background:#F7F8CC !important">' . number_format( $purchaseAsset->total_purchase_cost, 2 ) . '</span>';
                } )
                ->addColumn( 'total_past_consumtion', function ($purchaseAsset) {
                    return '<span style="background:#F7F8CC !important">' . number_format( $purchaseAsset->total_past_consumtion, 2 ) . '</span>';
                } )
                ->addColumn( 'paid_amount', function ($purchaseAsset) {
                    return '<span style="background:#D7FDF9 !important">' . number_format( $purchaseAsset->paid_amount, 2 ) . '</span>';
                } )
                ->addColumn( 'remaining_amount', function ($purchaseAsset) {
                    return '<span style="background:#D7FDF9 !important">' . number_format( $purchaseAsset->remaining_amount, 2 ) . '</span>';
                } )
                ->addColumn( 'created_at', function ($purchaseAsset) {
                    return $purchaseAsset->created_at;
                } )
                ->addColumn( 'updated_at', function ($purchaseAsset) {
                    return $purchaseAsset->updated_at;
                } )
                ->addColumn( 'action', function ($purchaseAsset) {
                    return '
                      <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i> ' . __( "Options" ) . '<span class="caret"></span></button>
                                          <ul class="dropdown-menu dropdown-wg">
                                            <li> <a class="btn btn-wg-edit hvr-radial-out" href="' . route( "admin:opening-balance-assets.edit", $purchaseAsset->id ) . '">
    <i class="fa fa-edit"></i>  ' . __( 'Edit' ) . '
        </a></li>
        <li class="btn-style-drop">
        <button type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete(' . $purchaseAsset->id . ')">
            <i class="fa fa-trash"></i>  ' . __( 'Delete' ) . '
        </button>

        <form style="display: none" method="POST" id="confirmDelete' . $purchaseAsset->id . '" action="' . route( 'admin:opening-balance-assets.destroy', $purchaseAsset->id ) . '">
            <input type="hidden" name="_method" value="DELETE">
           ' . @csrf_field() . '
        </form>
        </li>
        <li>
        <a style="cursor:pointer" class="btn btn-print-wg text-white  "
           data-toggle="modal" onclick="getPrintData(' . $purchaseAsset->id . ')"
           data-target="#boostrapModal" title="' . __( 'print' ) . '">
            <i class="fa fa-print"></i> ' . __( 'Print' ) . '</a>
        </li>
          </ul> </div>
                 ';
                } )->addColumn( 'options', function ($saleAsset) {
                    return '
                    <form action=' . route( "admin:opening-balance-assets.deleteSelected" ) . ' method="post" id="deleteSelected">
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

            if (authIsSuperAdmin()) {
                $js_columns = [
                    'DT_RowIndex' => 'DT_RowIndex',
                    'branch_id' => 'purchase_assets.branch_id',
                    'date' => 'date',
                    'invoice_number' => 'purchase_assets.invoice_number',

//                    'supplier_id' => 'purchase_assets.supplier_id',
//                    'type' => 'purchase_assets.type',
                    'total_purchase_cost' => 'purchase_assets.total_purchase_cost',
                    'total_past_consumtion' => 'purchase_assets.total_past_consumtion',
                    'paid_amount' => 'purchase_assets.paid_amount',
                    'remaining_amount' => 'purchase_assets.remaining_amount',
                    'created_at' => 'purchase_assets.created_at',
                    'updated_at' => 'purchase_assets.updated_at',
                    'action' => 'action',
                    'options' => 'options'
                ];
            } else {
                $js_columns = [
                    'DT_RowIndex' => 'DT_RowIndex',
                    'date' => 'date',
                    'invoice_number' => 'purchase_assets.invoice_number',

//                    'supplier_id' => 'purchase_assets.supplier_id',
//                    'type' => 'purchase_assets.type',
                    'total_purchase_cost' => 'purchase_assets.total_purchase_cost',
                    'total_past_consumtion' => 'purchase_assets.total_past_consumtion',
                    'paid_amount' => 'purchase_assets.paid_amount',
                    'remaining_amount' => 'purchase_assets.remaining_amount',
                    'created_at' => 'purchase_assets.created_at',
                    'updated_at' => 'purchase_assets.updated_at',
                    'action' => 'action',
                    'options' => 'options'
                ];
            }


            $assets = Asset::all();
            $branches = Branch::all()->pluck( 'name', 'id' );
            $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
            $numbers = PurchaseAsset::where( 'operation_type', '=', 'opening_balance' )->pluck( 'invoice_number' )->unique();
            return view( 'admin.opening-balance-assets.index', compact( 'numbers', 'assets', 'assetsGroups', 'js_columns' ) );
        }
    }

    public function create(Request $request)
    {
        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;

        $data['branches'] = Branch::where( 'status', 1 )->select( 'id', 'name_' . $this->lang )->get();


        $data['suppliers'] = Supplier::where( 'status', 1 )
            ->where( 'branch_id', $branch_id )
            ->select( 'id', 'name_' . $this->lang, 'group_id', 'sub_group_id' )
            ->get();
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        $lastNumber = PurchaseAsset::where( 'branch_id', $branch_id )->where( 'operation_type', '=', 'opening_balance' )->orderBy( 'id', 'desc' )->first();
        $number = $lastNumber ? $lastNumber->invoice_number + 1 : 1;
        return view( 'admin.opening-balance-assets.create', compact( 'data', 'assetsGroups', 'assets', 'number' ) );
    }

    public function store(OpeningBalanceAssetRequest $request)
    {
        if (!isset( $request->items )) {
            return redirect()->to( route( 'admin:opening-balance-assets.create' ) )
                ->withInput( $request->all() )
                ->with( ['message' => __( 'items is required' ), 'alert-type' => 'error'] );
        }
        DB::beginTransaction();
        try {
            $data = $request->all();
            $invoice_data = [
                'invoice_number' => $data['invoice_number'],
                'date' => $data['date'],
                'time' => $data['time'],
                'paid_amount' => $data['paid_amount'],
                'remaining_amount' => $data['remaining_amount'],
                'note' => $data['note'],
                'total_purchase_cost' => $request->total_purchase_cost,
                'total_past_consumtion' => $request->total_past_consumtion,
                'user_id' => auth()->id(),
                'type' => 0,
                'operation_type' => 'opening_balance'
            ];
            $invoice_data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $purchaseAsset = PurchaseAsset::create( $invoice_data );

            foreach ($data['items'] as $item) {
                $asset = Asset::find( $item['asset_id'] );
                $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion - $item['past_consumtion'];
                $asset->update( [
                    'purchase_date' => $item['purchase_date'],
                    'date_of_work' => $item['date_of_work'],
                    'purchase_cost' => $item['purchase_cost'],
                    'past_consumtion' => $item['past_consumtion'],
                    'annual_consumtion_rate' => $item['annual_consumtion_rate'],
                    'asset_age' => $item['asset_age'],
                    'book_value' => $book_value
                ] );

                $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                $assetGroup->increment( 'total_consumtion', $item['past_consumtion'] );

                PurchaseAssetItem::create( [
                    'purchase_asset_id' => $purchaseAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'purchase_cost' => $item['purchase_cost'],
                    'past_consumtion' => $item['past_consumtion'],
                    'annual_consumtion_rate' => $item['annual_consumtion_rate'],
                    'asset_age' => $item['asset_age'],
                    'total_purchase_cost' => $request->total_purchase_cost,
                    'total_past_consumtion' => $request->total_past_consumtion,
                    'net_total' => $request->net_total
                ] );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to( route( 'admin:opening-balance-assets.create' ) )
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }

        return redirect()->to( route( 'admin:opening-balance-assets.index' ) )
            ->with( ['message' => __( 'words.purchase-assets-created' ), 'alert-type' => 'success'] );

    }

    public function show(Request $request)
    {
        $asset = PurchaseAsset::find( $request->id );
        $isOnlyShow = $request->show;
        if ($isOnlyShow) {
            $invoice = view( 'admin.opening-balance-assets.onlyShow', compact( 'asset' ) )->render();

        } else {
            $invoice = view( 'admin.opening-balance-assets.show', compact( 'asset' ) )->render();
        }

        return response()->json( ['invoice' => $invoice] );
    }

    public function edit(PurchaseAsset $openingBalanceAsset)
    {
        foreach ($openingBalanceAsset->items as $item) {
            if (SaleAssetItem::where( 'asset_id', $item->asset->id )->exists()
                || AssetReplacementItem::where( 'asset_id', $item->asset->id )->exists()
                || AssetExpenseItem::where( 'asset_id', $item->asset->id )->exists()
                || ConsumptionAssetItem::where( 'asset_id', $item->asset->id )->exists()) {
                return redirect()->to( route( 'admin:opening-balance-assets.index' ) )
                    ->with( ['message' => __( 'words.can-not-update-this-data-cause-there-is-related-data' ), 'alert-type' => 'error'] );
            }
        }
        $data['branches'] = Branch::where( 'status', 1 )->select( 'id', 'name_' . $this->lang )->get();

        $branch_id = $openingBalanceAsset->branch_id;


        $data['suppliers'] = Supplier::where( 'status', 1 )
            ->where( 'branch_id', $branch_id )
            ->select( 'id', 'name_' . $this->lang, 'group_id', 'sub_group_id' )
            ->get();
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        return view( 'admin.opening-balance-assets.edit', compact( 'data', 'openingBalanceAsset', 'assets', 'assetsGroups' ) );
    }

    public function update(OpeningBalanceAssetRequest $request, PurchaseAsset $openingBalanceAsset)
    {
        if (!isset( $request->items )) {
            return redirect()->to( route( 'admin:opening-balance-assets.edit', $openingBalanceAsset ) )
                ->withInput( $request->all() )
                ->with( ['message' => __( 'items is required' ), 'alert-type' => 'error'] );
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $invoice_data = [
                'invoice_number' => $data['invoice_number'],
                'date' => $data['date'],
                'time' => $data['time'],
                'paid_amount' => $data['paid_amount'],
                'remaining_amount' => $data['remaining_amount'],
                'note' => $data['note'],
                'total_purchase_cost' => $request->total_purchase_cost,
                'total_past_consumtion' => $request->total_past_consumtion,
                'type' => 0,
                'operation_type' => 'opening_balance'
            ];
            $openingBalanceAsset->update( $invoice_data );
            foreach ($openingBalanceAsset->items as $one) {
                $asset = Asset::find( $one->asset_id );
                $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                $assetGroup->decrement( 'total_consumtion', $one->past_consumtion );
            }
            $openingBalanceAsset->items()->delete();
            foreach ($data['items'] as $item) {
                $asset = Asset::find( $item['asset_id'] );
                $asset->update( [
                    'purchase_date' => $item['purchase_date'],
                    'date_of_work' => $item['date_of_work'],
                    'purchase_cost' => $item['purchase_cost'],
                    'past_consumtion' => $item['past_consumtion'],
                    'annual_consumtion_rate' => $item['annual_consumtion_rate'],
                    'asset_age' => $item['asset_age'],
                ] );
                $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                $assetGroup->increment( 'total_consumtion', $item['past_consumtion'] );
                PurchaseAssetItem::create( [
                    'purchase_asset_id' => $openingBalanceAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'purchase_cost' => $item['purchase_cost'],
                    'past_consumtion' => $item['past_consumtion'],
                    'annual_consumtion_rate' => $item['annual_consumtion_rate'],
                    'asset_age' => $item['asset_age'],
                ] );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to( route( 'admin:opening-balance-assets.index' ) )
                ->with( ['message' => __( 'words.opening-balance-asset-cant-created' ), 'alert-type' => 'error'] );
        }

        return redirect()->to( route( 'admin:opening-balance-assets.index' ) )
            ->with( ['message' => __( 'words.opening-balance-asset-updated' ), 'alert-type' => 'success'] );

    }

    public function destroy(PurchaseAsset $openingBalanceAsset)
    {
        foreach ($openingBalanceAsset->items as $item) {
            if (SaleAssetItem::where( 'asset_id', $item->asset->id )->exists() || AssetReplacementItem::where( 'asset_id', $item->asset->id )->exists() || AssetExpenseItem::where( 'asset_id', $item->asset->id )->exists() || ConsumptionAssetItem::where( 'asset_id', $item->asset->id )->exists()) {
                return redirect()->to( route( 'admin:opening-balance-assets.index' ) )
                    ->with( ['message' => __( 'words.can-not-delete-this-data-cause-there-is-related-data' ), 'alert-type' => 'error'] );
            }
        }
        DB::beginTransaction();
        try {
            foreach ($openingBalanceAsset->items as $item) {
                $asset = Asset::find( $item->asset_id );
                $asset->decrement( 'past_consumtion', $item->past_consumtion );
                $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                $assetGroup->decrement( 'total_consumtion', $item->past_consumtion );

                $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion;
                $asset->update( ['book_value' => $book_value] );
            }
            $openingBalanceAsset->items()->delete();
            $openingBalanceAsset->delete();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to( route( 'admin:consumption-assets.index' ) )
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }

        return redirect()->back()
            ->with( ['message' => __( 'words.purchase-asset-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {

            foreach (array_unique( $request->ids ) as $invoiceId) {

                $openingBalanceAsset = PurchaseAsset::find( $invoiceId );

                foreach ($openingBalanceAsset->items as $item) {
                    if (SaleAssetItem::where( 'asset_id', $item->asset->id )->exists() || AssetReplacementItem::where( 'asset_id', $item->asset->id )->exists() || AssetExpenseItem::where( 'asset_id', $item->asset->id )->exists() || ConsumptionAssetItem::where( 'asset_id', $item->asset->id )->exists()) {
                        return redirect()->to( route( 'admin:opening-balance-assets.index' ) )
                            ->with( ['message' => __( 'words.can-not-delete-this-data-cause-there-is-related-data' ), 'alert-type' => 'error'] );
                    }
                }
                DB::beginTransaction();
                try {
                    foreach ($openingBalanceAsset->items as $item) {
                        $asset = Asset::find( $item->asset_id );
                        $asset->decrement( 'past_consumtion', $item->past_consumtion );
                        $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                        $assetGroup->decrement( 'total_consumtion', $item->past_consumtion );

                        $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion;
                        $asset->update( ['book_value' => $book_value] );
                    }
                    $openingBalanceAsset->items()->delete();
                    $openingBalanceAsset->delete();
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    return redirect()->to( route( 'admin:consumption-assets.index' ) )
                        ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
                }

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
        $asset = Asset::with( 'group' )->find( $request->asset_id );
        if (PurchaseAssetItem::where( 'asset_id', $request->asset_id )->whereHas( 'purchaseAsset', function ($q) {
            $q->where( 'operation_type', '=', 'opening_balance' );
        } )->count()) {
            return response()->json( __( 'opening balance added before for this asset' ), 400 );
        }
        if (empty( (int)$asset->purchase_cost ) && !PurchaseAssetItem::where( 'asset_id', $request->asset_id )->whereHas( 'purchaseAsset', function ($q) {
                $q->where( 'operation_type', '=', 'purchase' );
            } )->count()) {
            return response()->json( __( 'Please add Purchase for or add purchase cost this asset before adding opening balance' ), 400 );
        }
        if (SaleAssetItem::where( 'asset_id', $request->asset_id )->exists() || AssetReplacementItem::where( 'asset_id', $request->asset_id )->exists() || AssetExpenseItem::where( 'asset_id', $request->asset_id )->exists() || ConsumptionAssetItem::where( 'asset_id', $request->asset_id )->exists() || StopAndActivateAsset::where( 'asset_id', $request->asset_id )->exists()) {
            return response()->json( __( 'Can not add opening balance for this asset !' ), 400 );
        }
        $index = $request['index'] + 1;


        $assetGroup = $asset->group;
        $view = view( 'admin.opening-balance-assets.row',
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
            $numbers = PurchaseAsset::where( 'branch_id', $request->branch_id )->where( 'operation_type', '=', 'opening_balance' )->pluck( 'invoice_number' )->unique();
        } else {
            $numbers = PurchaseAsset::where( 'operation_type', '=', 'opening_balance' )->pluck( 'invoice_number' )->unique();
        }
        if ($numbers) {
            $numbers_data = view( 'admin.opening-balance-assets.invoice_number_by_branch_id', compact( 'numbers' ) )->render();
            return response()->json( [
                'data' => $numbers_data,
            ] );
        }
    }
}
