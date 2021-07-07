<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Asset\PurchaseAssetRequest;
use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\PurchaseAsset;
use App\Models\PurchaseAssetItem;
use Exception;
use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PurchaseAssetsController extends Controller
{

    protected $purchaseInvoiceItemsController;
    public $lang;


    public function __construct()
    {

        $this->lang = App::getLocale();
    }

    public function index(Request $request)
    {
        $invoices = PurchaseAsset::query();

        $invoices = $invoices->orderBy( 'id', 'DESC' );


        $rows = $request->has( 'rows' ) ? $request->rows : 10;

        $invoices = $invoices->paginate( $rows )->appends( request()->query() );
        return view( 'admin.purchase-assets.index', compact( 'invoices' ) );
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
        return view( 'admin.purchase-assets.create', compact( 'data', 'assetsGroups', 'assets' ) );
    }

    public function store(PurchaseAssetRequest $request)
    {
        if (!isset($request->items)) {
            return redirect()->to( route( 'admin:purchase-assets.create' ) )
                ->withInput($request->all())
                ->with( ['message' => __( 'items is required'), 'alert-type' => 'error'] );
        }
        DB::beginTransaction();
        try {
            $data = $request->all();
//
            $invoice_data = [
                'invoice_number' => $data['invoice_number'],
                'date' => $data['date'],
                'time' => $data['time'],
                'supplier_id' => $data['supplier_id'],
                'paid_amount' => $data['paid_amount'],
                'remaining_amount' => $data['remaining_amount'],
                'note' => $data['note'],
                'total_purchase_cost'=>$request->total_purchase_cost,
                'total_past_consumtion'=>$request->total_past_consumtion,
                'net_total'=>$request->net_total
            ];
            $invoice_data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $purchaseAsset = PurchaseAsset::create( $invoice_data );

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
                PurchaseAssetItem::create( [
                    'purchase_asset_id' => $purchaseAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'purchase_cost' => $item['purchase_cost'],
                    'past_consumtion' => $item['past_consumtion'],
                    'annual_consumtion_rate' => $item['annual_consumtion_rate'],
                    'asset_age' => $item['asset_age'],
                    'total_purchase_cost'=>$request->total_purchase_cost,
                    'total_past_consumtion'=>$request->total_past_consumtion,
                    'net_total'=>$request->net_total
                ] );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

//            dd( $e->getMessage() );

            return redirect()->to( route( 'admin:purchase-assets.create' ))
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }

        return redirect()->to( route( 'admin:purchase-assets.index' ) )
            ->with( ['message' => __( 'words.purchase-assets-created' ), 'alert-type' => 'success'] );

    }

    public function show(PurchaseAsset $purchaseAsset)
    {
        $asset = $purchaseAsset;
        $invoice = view( 'admin.purchase-assets.show', compact( 'asset' ) )->render();

        return response()->json( ['invoice' => $invoice] );
    }

    public function edit(PurchaseAsset $purchaseAsset)
    {

        $data['branches'] = Branch::where( 'status', 1 )->select( 'id', 'name_' . $this->lang )->get();

        $branch_id = $purchaseAsset->branch_id;


        $data['suppliers'] = Supplier::where( 'status', 1 )
            ->where( 'branch_id', $branch_id )
            ->select( 'id', 'name_' . $this->lang, 'group_id', 'sub_group_id' )
            ->get();
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        return view( 'admin.purchase-assets.edit', compact( 'data', 'purchaseAsset', 'assets', 'assetsGroups' ) );
    }

    public function update(PurchaseAssetRequest $request, PurchaseAsset $purchaseAsset)
    {
        if (!isset($request->items)) {
            return redirect()->to( route( 'admin:purchase-assets.edit',$purchaseAsset ) )
                ->withInput($request->all())
                ->with( ['message' => __( 'items is required'), 'alert-type' => 'error'] );
        }
        try {
            DB::beginTransaction();
            $data = $request->all();
            $invoice_data = [
                'invoice_number' => $data['invoice_number'],
                'date' => $data['date'],
                'time' => $data['time'],
                'supplier_id' => $data['supplier_id'],
                'paid_amount' => $data['paid_amount'],
                'remaining_amount' => $data['remaining_amount'],
                'note' => $data['note'],
                'total_purchase_cost'=>$request->total_purchase_cost,
                'total_past_consumtion'=>$request->total_past_consumtion,
                'net_total'=>$request->net_total
            ];
            $purchaseAsset->update( $invoice_data );
            $purchaseAsset->items()->delete();
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
                PurchaseAssetItem::create( [
                    'purchase_asset_id' => $purchaseAsset->id,
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

            dd( $e->getMessage() );

            return redirect()->to( 'admin/purchase-invoices' )
                ->with( ['message' => __( 'words.purchase-invoice-cant-created' ), 'alert-type' => 'error'] );
        }

        return redirect()->to( route( 'admin:purchase-assets.index' ) )
            ->with( ['message' => __( 'words.purchase-assets-created' ), 'alert-type' => 'success'] );

    }

    public function destroy(PurchaseAsset $purchaseAsset)
    {

        $purchaseAsset->delete();
        $purchaseAsset->items()->delete();

        return redirect()->back()
            ->with( ['message' => __( 'words.purchase-asset-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {

            foreach (array_unique( $request->ids ) as $invoiceId) {

                $purchaseAsset = PurchaseAsset::find( $invoiceId );
                $purchaseAsset->delete();
                $purchaseAsset->items()->delete();

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
        $view = view( 'admin.purchase-assets.row',
            compact( 'asset', 'index', 'assetGroup' )
        )->render();
        return response()->json( [
            'items' => $view,
            'index' => $index
        ] );
    }
}
