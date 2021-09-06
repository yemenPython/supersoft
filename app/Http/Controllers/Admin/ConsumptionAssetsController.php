<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Asset\ConsumptionAssetRequest;
use App\Http\Requests\Admin\Asset\PurchaseAssetRequest;
use App\Models\Asset;
use App\Models\AssetEmployee;
use App\Models\AssetGroup;
use App\Models\AssetReplacementItem;
use App\Models\AssetType;
use App\Models\ConsumptionAsset;
use App\Models\ConsumptionAssetItem;
use App\Models\ConsumptionAssetItemExpense;
use App\Models\EmployeeData;
use App\Models\PurchaseAsset;
use App\Models\PurchaseAssetItem;
use App\Models\SaleAssetItem;
use App\Models\StopAndActivateAsset;
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
            $consumptionAssets = ConsumptionAsset::select( [
                'consumption_assets.id',
                'consumption_assets.number',
                'consumption_assets.branch_id',
                'consumption_assets.date',
                'consumption_assets.time',
                'consumption_assets.note',
                'consumption_assets.date_from',
                'consumption_assets.date_to',
                'consumption_assets.created_at',
                'consumption_assets.updated_at',
                'consumption_assets.total_replacement'
            ] )
                ->leftjoin( 'consumption_asset_items', 'consumption_assets.id', '=', 'consumption_asset_items.consumption_asset_id' )->latest();

            if ($request->has( 'branch_id' ) && !empty( $request['branch_id'] ))
                $consumptionAssets->where( 'consumption_assets.branch_id', $request['branch_id'] );
            if ($request->has( 'number' ) && !empty( $request['number'] ))
                $consumptionAssets->where( 'consumption_assets.number', $request['number'] );

            if ($request->has( 'asset_group_id' ) && !empty( $request->asset_group_id ))
                $consumptionAssets->where( 'consumption_asset_items.asset_group_id', $request['asset_group_id'] );
            if ($request->has( 'asset_id' ) && !empty( $request->asset_id ))
                $consumptionAssets->where( 'consumption_asset_items.asset_id', $request['asset_id'] );


            if ($request->has( 'date_from' ) && !empty( $request['date_from'] ))
                $consumptionAssets->where( 'consumption_assets.date_from', $request['date_from'] );

            if ($request->has( 'date_to' ) && !empty( $request['date_to'] ))
                $consumptionAssets->where( 'consumption_assets.date_to', $request['date_to'] );

            whereBetween( $consumptionAssets, 'consumption_asset_items.consumption_amount', $request->consumption_amount_from, $request->consumption_amount_to );
            return DataTables::of( $consumptionAssets->groupBy( 'consumption_assets.id' ) )
                ->addIndexColumn()
                ->addColumn( 'branch_id', function ($asset) {
                    return '<span class="text-danger">' . optional( $asset->branch )->name . '</span>';

                } )
                ->addColumn( 'date', function ($consumptionAsset) {
                    return '<span class="text-danger">' . $consumptionAsset->date;
                } )
                ->addColumn( 'number', function ($consumptionAsset) {
                    return $consumptionAsset->number;

                } )
                ->addColumn( 'date_from', function ($consumptionAsset) {
                    return '<span class="label wg-label"
                    style="background: rgb(113, 101, 218) !important;"
                    >' . $consumptionAsset->date_from . '</span>';
                } )
                ->addColumn( 'date_to', function ($consumptionAsset) {
                    return '<span class="label wg-label"
                    style="background: rgb(113, 101, 218) !important;"
                    >' . $consumptionAsset->date_to . '</span>';
                } )
                ->addColumn( 'total_replacement', function ($consumptionAsset) {
                    return '<span style="background:#F7F8CC !important">' . number_format( $consumptionAsset->total_replacement, 2 ) . '</span>';
                } )
                ->addColumn( 'created_at', function ($consumptionAsset) {
                    return $consumptionAsset->created_at;
                } )
                ->addColumn( 'updated_at', function ($consumptionAsset) {
                    return $consumptionAsset->updated_at;
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
                'date' => 'consumption_assets.date',
                'number' => 'consumption_assets.number',
                'date_from' => 'consumption_assets.date_from',
                'date_to' => 'consumption_assets.date_to',
                'total_replacement' => 'consumption_assets.total_replacement',
                'created_at' => 'consumption_assets.created_at',
                'updated_at' => 'consumption_assets.updated_at',
                'action' => 'action',
                'options' => 'options'
            ];

            if (authIsSuperAdmin()) {

                $js_columns = [
                    'DT_RowIndex' => 'DT_RowIndex',
                    'branch_id' => 'consumption_assets.branch_id',
                    'date' => 'consumption_assets.date',
                    'number' => 'consumption_assets.number',
                    'date_from' => 'consumption_assets.date_from',
                    'date_to' => 'consumption_assets.date_to',
                    'total_replacement' => 'consumption_assets.total_replacement',
                    'created_at' => 'consumption_assets.created_at',
                    'updated_at' => 'consumption_assets.updated_at',
                    'action' => 'action',
                    'options' => 'options'
                ];
            }

            $assets = Asset::all();
            $branches = Branch::all()->pluck( 'name', 'id' );
            $assetsGroups = AssetGroup::select( ['id', 'name_ar', 'name_en'] )->get();
            $numbers = ConsumptionAsset::select( 'number' )->distinct()->orderBy( 'number', 'asc' )->get();
            return view( 'admin.consumption-assets.index', compact( 'js_columns', 'assets', 'branches', 'assetsGroups', 'numbers' ) );
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
            $to = Carbon::createFromFormat( 'Y-m-d', $request->date_to );
            $from = Carbon::createFromFormat( 'Y-m-d', $request->date_from );
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
                'total_replacement' => number_format( $request->total_replacement, 2 ),
                'user_id' => auth()->id(),
                'type' => $data['type']
            ];
            $invoice_data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $consumptionAsset = ConsumptionAsset::create( $invoice_data );

            foreach ($data['items'] as $item) {
                $asset = Asset::find( $item['asset_id'] );
                $diff =0;
                $stop_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->first()->date:'';
                $activate_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->first()->date:'';
                if (!empty($stop_date) && !empty($activate_date)){
                    $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                    $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                    $diff = $activate_date->diffInDays( $stop_date );
                }
                $diff_in_days -=$diff;
                $consumption_amount = 0;
                if ($request->type != 'expenses') {
                    $age = ($item['net_purchase_cost'] / $item['annual_consumtion_rate']) / 100;
                    $months = $age * 12;
                    $asd = $item['net_purchase_cost'] / $months;
                    $value = $asd * ($diff_in_days / 30);
                    $consumption_amount = number_format( $value, 2 );
                }
                $ConsumptionAssetItem = ConsumptionAssetItem::create( [
                    'consumption_asset_id' => $consumptionAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'consumption_amount' => $consumption_amount,
                ] );

                $expenses_total = 0;
                if (in_array( $request->type, ['expenses', 'both'] )) {
                    foreach ($asset->expenses as $expens) {
                        $to = Carbon::createFromFormat( 'Y-m-d', $request->date_to );
                        $from = Carbon::createFromFormat( 'Y-m-d', $request->date_from );
                        $diff =0;
                        $stop_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->first()->date:'';
                        $activate_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->first()->date:'';
                        if (!empty($stop_date) && !empty($activate_date)){
                            $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                            $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                            $diff = $activate_date->diffInDays( $stop_date );;
                        }
                        $diff_in_days = $to->diffInDays( $from );
                        $diff_in_days -=$diff;
                        $age = ($expens->price / $expens->annual_consumtion_rate) / 100;
                        $months = $age * 12;
                        $asd = $expens->price / $months;
                        $value = $asd * ($diff_in_days / 30);
                        $expenses_total += number_format( $value, 2 );
                        ConsumptionAssetItemExpense::create( [
                                'asset_id' => $asset->id,
                                'consumption_amount' => number_format( $value, 2 ),
                                'consumption_asset_item_id' => $ConsumptionAssetItem->id,
                                'expense_id' => $expens->id
                            ]
                        );
                    }
                }
                $total_current_consumtion = $consumption_amount + (float)$asset->past_consumtion;
                $asset->update( [
                    'current_consumtion' => $consumption_amount,
                    'total_current_consumtion' => $total_current_consumtion
                ] );
                $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                $sum_total_current_consumtion_for_group = $assetGroup->assets()->sum( 'total_current_consumtion' );
                $assetGroup->update( ['total_consumtion' => $sum_total_current_consumtion_for_group] );

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
        $isOnlyShow = $request->show;
        if ($isOnlyShow) {
            $view = view( 'admin.consumption-assets.onlyShow', compact( 'asset' ) )->render();

        } else {
            $view = view( 'admin.consumption-assets.show', compact( 'asset' ) )->render();
        }
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
            $to = Carbon::createFromFormat( 'Y-m-d', $request->date_to );
            $from = Carbon::createFromFormat( 'Y-m-d', $request->date_from );
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
                'total_replacement' => number_format( $request->total_replacement, 2 ),
                'user_id' => auth()->id()
            ];

            $consumptionAsset->update( $invoice_data );
            foreach ($consumptionAsset->items as $one) {
                if ($one->consumptionAssetItemExpenses)
                    $one->consumptionAssetItemExpenses()->delete();
            }
            $consumptionAsset->items()->delete();
            foreach ($data['items'] as $item) {
                $asset = Asset::find( $item['asset_id'] );
                $diff =0;
                $stop_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->first()->date:'';
                $activate_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->first()->date:'';
                if (!empty($stop_date) && !empty($activate_date)){
                    $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                    $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                    $diff = $activate_date->diffInDays( $stop_date );
                }
                $diff_in_days -=$diff;
                $consumption_amount = 0;
                if ($consumptionAsset->type != 'expenses') {
                    $age = ($item['net_purchase_cost'] / $item['annual_consumtion_rate']) / 100;
                    $months = $age * 12;
                    $asd = $item['net_purchase_cost'] / $months;
                    $value = $asd * ($diff_in_days / 30);
                    $consumption_amount = number_format( $value, 2 );
                }


                $ConsumptionAssetItem = ConsumptionAssetItem::create( [
                    'consumption_asset_id' => $consumptionAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'consumption_amount' => $consumption_amount,
                ] );

                $expenses_total = 0;
                if (in_array( $consumptionAsset->type, ['expenses', 'both'] )) {
                    $ConsumptionAssetItem->consumptionAssetItemExpenses()->delete();
                    foreach ($asset->expenses as $expens) {
                        $to = Carbon::createFromFormat( 'Y-m-d', $request->date_to );
                        $from = Carbon::createFromFormat( 'Y-m-d', $request->date_from );

                        $diff =0;
                        $stop_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->first()->date:'';
                        $activate_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->first()->date:'';
                        if (!empty($stop_date) && !empty($activate_date)){
                            $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                            $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                            $diff = $activate_date->diffInDays( $stop_date );;
                        }
                        $diff_in_days = $to->diffInDays( $from );
                        $diff_in_days -=$diff;

                        $age = ($expens->price / $expens->annual_consumtion_rate) / 100;
                        $months = $age * 12;
                        $asd = $expens->price / $months;
                        $value = $asd * ($diff_in_days / 30);
                        $expenses_total += number_format( $value, 2 );
                        ConsumptionAssetItemExpense::create( [
                                'asset_id' => $asset->id,
                                'consumption_amount' => number_format( $value, 2 ),
                                'consumption_asset_item_id' => $ConsumptionAssetItem->id,
                                'expense_id' => $expens->id
                            ]
                        );
                    }
                }
                $total_current_consumtion = $consumption_amount + (float)$asset->past_consumtion;
                $asset->update( [
                    'current_consumtion' => $consumption_amount,
                    'total_current_consumtion' => $total_current_consumtion
                ] );
                $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                $sum_total_current_consumtion_for_group = $assetGroup->assets()->sum( 'total_current_consumtion' );
                $assetGroup->update( ['total_consumtion' => $sum_total_current_consumtion_for_group] );

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
        foreach ($consumptionAsset->items as $item) {
            if (SaleAssetItem::where( 'asset_id', $item->asset->id )->exists() || AssetReplacementItem::where( 'asset_id', $item->asset->id )->exists()) {
                return redirect()->to( route( 'admin:consumption-assets.index' ) )
                    ->with( ['message' => __( 'words.Can not delete this consumption asset' ), 'alert-type' => 'error'] );
            }
        }
        foreach ($consumptionAsset->items as $item) {
            $item->asset->group()->decrement( 'total_consumtion', $item->consumption_amount );
            $item->asset()->decrement( 'total_current_consumtion', $item->consumption_amount );
        }
        foreach ($consumptionAsset->items as $one) {
            if ($one->consumptionAssetItemExpenses)
                $one->consumptionAssetItemExpenses()->delete();
        }
        $consumptionAsset->items()->delete();
        $consumptionAsset->delete();
        return redirect()->back()
            ->with( ['message' => __( 'words.consumption-asset-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {

            foreach (array_unique( $request->ids ) as $invoiceId) {

                $consumptionAsset = ConsumptionAsset::find( $invoiceId );

                foreach ($consumptionAsset->items as $item) {
                    if (SaleAssetItem::where( 'asset_id', $item->asset->id )->exists() || AssetReplacementItem::where( 'asset_id', $item->asset->id )->exists()) {
                        return redirect()->to( route( 'admin:consumption-assets.index' ) )
                            ->with( ['message' => __( 'words.Can not delete this consumption asset' ), 'alert-type' => 'error'] );
                    }
                }

                foreach ($consumptionAsset->items as $one) {
                    if ($one->consumptionAssetItemExpenses)
                        $one->consumptionAssetItemExpenses()->delete();
                }
                foreach ($consumptionAsset->items as $item) {
                    $item->asset->group()->decrement( 'total_consumtion', $item->consumption_amount );
                    $item->asset()->decrement( 'total_current_consumtion', $item->consumption_amount );
                }

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
        if (is_null( $request->asset_id )) {
            return response()->json( __( 'please select valid Asset' ), 400 );
        }
        $asset = Asset::with( 'group' )->find( $request->asset_id );
        if (SaleAssetItem::where( 'asset_id', $request->asset_id )->count()) {
            return response()->json( __( 'can not  consumption asset after sale' ), 400 );
        }
        if (is_null( $request->branch_id ) && authIsSuperAdmin()) {
            return response()->json( __( 'please select valid branch' ), 400 );
        }

        $index = $request['index'] + 1;

        if (empty( (int)$asset->purchase_cost ) && !PurchaseAssetItem::where('asset_id',$request->asset_id)->whereHas('purchaseAsset',function ($q){
                $q->where('operation_type','=','purchase');
            })->count()) {
            return response()->json( __( 'Please add Purchase  for this asset before consumption' ), 400 );
        }

        if (empty( $asset->date_of_work )) {
            return response()->json( __( 'please update date of work for this asset, or select another asset' ), 400 );
        }

        $datef = Carbon::createFromFormat( 'Y-m-d', $request->date_from );
        $datew = Carbon::createFromFormat( 'Y-m-d', $asset->date_of_work );
        if ($datew->gt( $datef )) {
            return response()->json( __( 'can not choice date to consumption before  date of work' ), 400 );
        }
        $consumption_asset = ConsumptionAsset::join( 'consumption_asset_items', 'consumption_assets.id', '=', 'consumption_asset_items.consumption_asset_id' )
            ->where(
                ['consumption_assets.date_from' => $request->date_from,
                    'consumption_assets.date_to' => $request->date_to,
                    'consumption_asset_items.asset_id' => $request->asset_id,
                ] )->count();

        if ($consumption_asset > 1 && $request->type != 'update') {
            return response()->json( __( 'can not create consumption for asset in same dates more than once' ), 400 );
        }
        $expenses_total = 0;
        foreach ($asset->expenses as $expens) {
            $to = Carbon::createFromFormat( 'Y-m-d', $request->date_to );
            $from = Carbon::createFromFormat( 'Y-m-d', $request->date_from );

            $diff =0;
            $stop_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->first()->date:'';
            $activate_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->first()->date:'';
            if (!empty($stop_date) && !empty($activate_date)){
                $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                $diff = $activate_date->diffInDays( $stop_date );;
            }
            $diff_in_days = $to->diffInDays( $from );
            $diff_in_days -=$diff;

            $age = $expens->annual_consumtion_rate ? ($expens->price / $expens->annual_consumtion_rate) / 100 : 0;
            $months = $age * 12;
            $asd = $months ? $expens->price / $months : 0;
            $value = $asd * ($diff_in_days / 30);
            $expenses_total += number_format( $value, 2 );
        }

        $assetGroup = $asset->group;


        $diff =0;
        $stop_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','stop')->latest()->first()->date:'';
        $activate_date = StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->exists() ?StopAndActivateAsset::where( 'asset_id',$asset->id )->where('status','=','activate')->latest()->first()->date:'';
        if (!empty($stop_date) && !empty($activate_date)){
            $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
            $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
            $diff = $activate_date->diffInDays( $stop_date );;
        }
        $view = view( 'admin.consumption-assets.row',
            compact( 'asset', 'index', 'assetGroup' )
        )->render();
        return response()->json( [
            'items' => $view,
            'index' => $index,
            'expenses_total' => $expenses_total,'diff'=>$diff
        ] );
    }

    public function getNumbersByBranchId(Request $request): JsonResponse
    {
        if (!empty( $request->branch_id )) {
            $numbers = ConsumptionAsset::where( 'branch_id', $request->branch_id )->pluck( 'number' )->unique();
        } else {
            $numbers = ConsumptionAsset::pluck( 'number' )->unique();
        }
        if ($numbers) {
            $numbers_data = view( 'admin.consumption-assets.invoice_number_by_branch_id', compact( 'numbers' ) )->render();
            return response()->json( [
                'data' => $numbers_data,
            ] );
        }
    }
}
