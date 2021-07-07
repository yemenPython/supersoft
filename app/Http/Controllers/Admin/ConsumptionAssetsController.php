<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Asset\ConsumptionAssetRequest;
use App\Http\Requests\Admin\Asset\PurchaseAssetRequest;
use App\Models\Asset;
use App\Models\AssetEmployee;
use App\Models\AssetGroup;
use App\Models\AssetType;
use App\Models\ConsumptionAsset;
use App\Models\ConsumptionAssetItem;
use App\Models\EmployeeData;
use App\Models\PurchaseAsset;
use App\Models\PurchaseAssetItem;
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

class ConsumptionAssetsController extends Controller
{

    public $lang;


    public function __construct()
    {

        $this->lang = App::getLocale();
    }

    public function index(Request $request)
    {
        if ($request->isDataTable) {
            $consumptionAssets = ConsumptionAsset::select( ['*'] );
            return DataTables::of( $consumptionAssets )
                ->addIndexColumn()
                ->addColumn( 'number', function ($consumptionAsset) {
                    return $consumptionAsset->number;

                } )
                ->addColumn( 'date_from', function ($consumptionAsset) {
                    return $consumptionAsset->date_from;
                } )
                ->addColumn( 'date_to', function ($consumptionAsset) {
                    return $consumptionAsset->date_to;
                } )
                ->addColumn( 'action', function ($consumptionAsset) {
                    return '
                      <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i> ' . __( "Options" ) . '<span class="caret"></span></button>
                                          <ul class="dropdown-menu dropdown-wg">
                                            <li> <a class="btn btn-wg-edit hvr-radial-out" href="' . route( "admin:consumption-assets.edit", $consumptionAsset->id ) . '">
    <i class="fa fa-edit"></i>  ' . __( 'Edit' ) . '
        </a></li>
        <li class="btn-style-drop">
        <button type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete(' . $consumptionAsset->id . ')">
            <i class="fa fa-trash"></i>  ' . __( 'Delete' ) . '
        </button>

        <form style="display: none" method="POST" id="confirmDelete' . $consumptionAsset->id . '" action="' . route( 'admin:consumption-assets.destroy', $consumptionAsset->id ) . '">
            <input type="hidden" name="_method" value="DELETE">
           ' . @csrf_field() . '
        </form>
        </li>
        <li>
        <a style="cursor:pointer" class="btn btn-print-wg text-white  "
           data-toggle="modal" onclick="getPrintData(' . $consumptionAsset->id . ')"
           data-target="#boostrapModal" title="' . __( 'print' ) . '">
            <i class="fa fa-print"></i> ' . __( 'Print' ) . '</a>
        </li>
          </ul> </div>
                 ';
                } )->addColumn( 'options', function ($consumptionAsset) {
                    return '
                    <form action=' . route( "admin:consumption-assets.deleteSelected" ) . ' method="post" id="deleteSelected">
                    ' . @csrf_field() . '
        <div class="checkbox danger">
        <input type="checkbox" name="ids[]" value="' . $consumptionAsset->id . '" id="checkbox-' . $consumptionAsset->id . '">
        <label for="checkbox-' . $consumptionAsset->id . '"></label>
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
                'number' => 'consumption_assets.number',
                'date' => 'consumption_assets.date',
                'date_from' => 'consumption_assets.date_from',
                'date_to' => 'consumption_assets.date_to',
                'action' => 'action',
                'options' => 'options'
            ];
            return view( 'admin.consumption-assets.index', compact( 'js_columns' ) );
        }

    }

    public function create(Request $request)
    {

        $branch_id = $request->has( 'branch_id' ) ? $request['branch_id'] : auth()->user()->branch_id;
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        $data['branches'] = Branch::where( 'status', 1 )->select( 'id', 'name_' . $this->lang )->get();
        $lastNumber = ConsumptionAsset::where( 'branch_id', $branch_id )->orderBy( 'id', 'desc' )->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        return view( 'admin.consumption-assets.create', compact( 'data', 'number', 'assetsGroups', 'assets' ) );
    }

    public function store(ConsumptionAssetRequest $request)
    {
        if (!isset( $request->items )) {
            return redirect()->to( route( 'admin:consumption-assets.create' ) )
                ->withInput( $request->all() )
                ->with( ['message' => __( 'items is required' ), 'alert-type' => 'error'] );
        }
        DB::beginTransaction();
        try {
            $data = $request->all();
            $to = \Carbon\Carbon::createFromFormat( 'Y-m-d', $request->date_to );
            $from = \Carbon\Carbon::createFromFormat( 'Y-m-d', $request->date_from );
            $diff_in_days = $to->diffInDays( $from );

            $invoice_data = [
                'number' => $data['number'],
                'date' => $data['date'],
                'time' => $data['time'],
                'note' => $data['note'],
                'date_from' => $data['date_from'],
                'date_to' => $data['date_to'],
                'total_purchase_cost' => $request->total_purchase_cost,
                'total_past_consumtion' => $request->total_past_consumtion,
                'total_replacement' => 0
            ];
            $invoice_data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $consumptionAsset = ConsumptionAsset::create( $invoice_data );

            foreach ($data['items'] as $item) {


                $age = ($item['net_purchase_cost'] / $item['annual_consumtion_rate']) / 100;
                $months = $age * 12;
                $asd = $item['net_purchase_cost'] / $months;
                $value = $asd * ($diff_in_days / 30);
                $consumption_amount = number_format( $value, 2 );

                $asset = Asset::find( $item['asset_id'] );
                $total_current_consumtion = $consumption_amount + (float)$asset->past_consumtion;
                $asset->update( [
                    'current_consumtion' => $consumption_amount,
                    'total_current_consumtion' => $total_current_consumtion
                ] );
                ConsumptionAssetItem::create( [
                    'consumption_asset_id' => $consumptionAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'consumption_amount' => $consumption_amount,
                ] );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to( route( 'admin:consumption-assets.create' ) )
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }

        return redirect()->to( route( 'admin:consumption-assets.index' ) )
            ->with( ['message' => __( 'words.consumption-assets-created' ), 'alert-type' => 'success'] );

    }

    public function show(ConsumptionAsset $consumptionAsset, Request $request)
    {
        $asset = ConsumptionAsset::find( $request->id );
        $view = view( 'admin.consumption-assets.show', compact( 'asset' ) )->render();

        return response()->json( ['view' => $view] );
    }

    public function edit(ConsumptionAsset $consumptionAsset)
    {
        $data['branches'] = Branch::where( 'status', 1 )->select( 'id', 'name_' . $this->lang )->get();
        $branch_id = $consumptionAsset->branch_id;
        $assetsGroups = AssetGroup::where( 'branch_id', $branch_id )->get();
        $assets = Asset::where( 'branch_id', $branch_id )->get();
        return view( 'admin.consumption-assets.edit', compact( 'data', 'consumptionAsset', 'assets', 'assetsGroups' ) );
    }

    public function update(ConsumptionAssetRequest $request, ConsumptionAsset $consumptionAsset)
    {
        if (!isset( $request->items )) {
            return redirect()->to( route( 'admin:consumption-assets.edit', $consumptionAsset->id ) )
                ->withInput( $request->all() )
                ->with( ['message' => __( 'items is required' ), 'alert-type' => 'error'] );
        }
        DB::beginTransaction();
        try {
            $data = $request->all();
            $to = \Carbon\Carbon::createFromFormat( 'Y-m-d', $request->date_to );
            $from = \Carbon\Carbon::createFromFormat( 'Y-m-d', $request->date_from );
            $diff_in_days = $to->diffInDays( $from );

            $invoice_data = [
                'number' => $data['number'],
                'date' => $data['date'],
                'time' => $data['time'],
                'note' => $data['note'],
                'date_from' => $data['date_from'],
                'date_to' => $data['date_to'],
                'total_purchase_cost' => $request->total_purchase_cost,
                'total_past_consumtion' => $request->total_past_consumtion,
                'total_replacement' => 0
            ];

            $consumptionAsset->update( $invoice_data );
            foreach ($data['items'] as $item) {
                $age = ($item['net_purchase_cost'] / $item['annual_consumtion_rate']) / 100;
                $months = $age * 12;
                $asd = $item['net_purchase_cost'] / $months;
                $value = $asd * ($diff_in_days / 30);
                $consumption_amount = number_format( $value, 2 );

                $asset = Asset::find( $item['asset_id'] );

                if ($item['consumption_asset_item_id'] != 'new') {
                    $consumptionAssetItem = ConsumptionAssetItem::where( 'id', $item['consumption_asset_item_id'] )->first();
                    $new_consumption_amount = $asset->current_consumtion - $consumptionAssetItem->consumption_amount + $consumption_amount;

                    $total_current_consumtion = $new_consumption_amount + (float)$asset->past_consumtion;

                    $asset->update( [
                        'current_consumtion' => $new_consumption_amount,
                        'total_current_consumtion' => $total_current_consumtion
                    ] );

                    $consumptionAssetItem->update( [
                        'consumption_asset_id' => $consumptionAsset->id,
                        'asset_id' => $item['asset_id'],
                        'asset_group_id' => $asset->asset_group_id,
                        'consumption_amount' => $consumption_amount,
                    ] );
                }else{
                    $total_current_consumtion = $consumption_amount + (float)$asset->past_consumtion;
                    $asset->update( [
                        'current_consumtion' => $consumption_amount,
                        'total_current_consumtion' => $total_current_consumtion
                    ] );
                    ConsumptionAssetItem::create( [
                        'consumption_asset_id' => $consumptionAsset->id,
                        'asset_id' => $item['asset_id'],
                        'asset_group_id' => $asset->asset_group_id,
                        'consumption_amount' => $consumption_amount,
                    ] );

                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to( route( 'admin:consumption-assets.edit', $consumptionAsset->id ) )
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }

        return redirect()->to( route( 'admin:consumption-assets.index' ) )
            ->with( ['message' => __( 'words.consumption-assets-created' ), 'alert-type' => 'success'] );

    }

    public function destroy(ConsumptionAsset $consumptionAsset)
    {

        $consumptionAsset->delete();
        $consumptionAsset->items()->delete();

        return redirect()->back()
            ->with( ['message' => __( 'words.consumption-asset-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {

            foreach (array_unique( $request->ids ) as $invoiceId) {

                $consumptionAsset = ConsumptionAsset::find( $invoiceId );
                $consumptionAsset->delete();
                $consumptionAsset->items()->delete();

            }

            return redirect()->back()
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }

        return redirect()->back()
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    public function getAssetsByAssetId(Request $request): JsonResponse
    {
//        dd($request->all());

        if (is_null( $request->asset_id )) {
            return response()->json( __( 'please select valid Asset' ), 400 );
        }
        if (is_null( $request->branch_id ) && authIsSuperAdmin()) {
            return response()->json( __( 'please select valid branch' ), 400 );
        }
        $index = $request['index'] + 1;

        $asset = Asset::with( 'group' )->find( $request->asset_id );
        if (empty( $asset->date_of_work )) {
            return response()->json( __( 'please update date of work for this asset, or select another asset' ), 400 );
        }

        $datef = Carbon::createFromFormat('Y-m-d',$request->date_from);
        $datew = Carbon::createFromFormat('Y-m-d',$asset->date_of_work);
        if ( $datew->gt($datef)) {
            return response()->json( __( 'can not choice date to consumption before  date of work' ), 400 );
        }
        $consumption_asset = ConsumptionAsset::join( 'consumption_asset_items', 'consumption_assets.id', '=', 'consumption_asset_items.consumption_asset_id' )
            ->where(
                ['consumption_assets.date_from' => $request->date_from,
                    'consumption_assets.date_to' => $request->date_to,
                    'consumption_asset_items.asset_id' => $request->asset_id,
                ] )->count();
        if ($consumption_asset) {
            return response()->json( __( 'can not create consumption for asset in same dates more than once' ), 400 );
        }
        $assetGroup = $asset->group;
        $view = view( 'admin.consumption-assets.row',
            compact( 'asset', 'index', 'assetGroup' )
        )->render();
        return response()->json( [
            'items' => $view,
            'index' => $index
        ] );
    }
}