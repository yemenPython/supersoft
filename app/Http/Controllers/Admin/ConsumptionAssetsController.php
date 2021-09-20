<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Asset\ConsumptionAssetRequest;
use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\AssetReplacementItem;
use App\Models\ConsumptionAsset;
use App\Models\ConsumptionAssetItem;
use App\Models\ConsumptionAssetItemExpense;
use App\Models\PurchaseAssetItem;
use App\Models\SaleAssetItem;
use App\Models\StopAndActivateAsset;
use Carbon\Carbon;
use Exception;
use App\Models\Branch;
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
                $diff = 0;
                $stop_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->first()->date : '';
                $activate_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->first()->date : '';
                if (!empty( $stop_date ) && !empty( $activate_date )) {
                    $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                    $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                    $diff = $activate_date->diffInDays( $stop_date );
                }
                $diff_in_days -= $diff;
                $consumption_amount = 0;
                if ($request->type != 'expenses') {
                    $age = (($asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion) / $item['annual_consumtion_rate']) / 100;
                    $months = $age * 12;

                    $asd = ($asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion) / $months;
                    $value = $asd * ($diff_in_days / 30);
                    $consumption_amount = number_format( $value, 2 );

                    $asset->increment( 'total_current_consumtion', $value );
                    $asset->increment( 'current_consumtion', $value );
                    $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion - $asset->past_consumtion;
                    $asset->update( ['book_value' => $book_value] );
                    $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                    $assetGroup->increment( 'total_consumtion', $consumption_amount );
                }
                $ConsumptionAssetItem = ConsumptionAssetItem::create( [
                    'consumption_asset_id' => $consumptionAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'consumption_amount' => $consumption_amount,
                ] );

                if (in_array( $request->type, ['expenses', 'both'] )) {
                    foreach ($asset->expenses as $expens) {
                        $to = Carbon::createFromFormat( 'Y-m-d', $request->date_to );
                        $from = Carbon::createFromFormat( 'Y-m-d', $request->date_from );
                        $diff = 0;
                        $stop_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->first()->date : '';
                        $activate_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->first()->date : '';
                        if (!empty( $stop_date ) && !empty( $activate_date )) {
                            $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                            $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                            $diff = $activate_date->diffInDays( $stop_date );;
                        }
                        $diff_in_days = $to->diffInDays( $from );
                        $diff_in_days -= $diff;

                        $age = (($expens->price - $expens->expenseConsumptions->sum( 'consumption_amount' )) / $expens->annual_consumtion_rate) / 100;
                        $months = $age * 12;
                        $asd = ($expens->price - $expens->expenseConsumptions->sum( 'consumption_amount' )) / $months;
                        $value = $asd * ($diff_in_days / 30);
                        $value = number_format( $value, 2 );
                        ConsumptionAssetItemExpense::create( [
                                'asset_id' => $asset->id,
                                'consumption_amount' => number_format( $value, 2 ),
                                'consumption_asset_item_id' => $ConsumptionAssetItem->id,
                                'expense_id' => $expens->id
                            ]
                        );

                        $asset->increment( 'total_current_consumtion', $value );
                        $asset->increment( 'current_consumtion', $value );
                        $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion - $asset->past_consumtion;
                        $asset->update( ['book_value' => $book_value] );
                        $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                        $assetGroup->increment( 'total_consumtion', $value );
                    }
                }

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
        foreach ($consumptionAsset->items as $item) {
            if (SaleAssetItem::where( 'asset_id', $item->asset->id )->exists()
                || AssetReplacementItem::where( 'asset_id', $item->asset->id )->exists()) {
                return redirect()->to( route( 'admin:consumption-assets.index' ) )
                    ->with( ['message' => __( 'words.can-not-update-this-data-cause-there-is-related-data' ), 'alert-type' => 'error'] );
            }
        }
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
                // decrement old values
                $asset = Asset::find( $one->asset_id );
                $asset->decrement( 'total_current_consumtion', $one->consumption_amount );
                $asset->decrement( 'current_consumtion', $one->consumption_amount );
                $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion - $asset->past_consumtion;
                $asset->update( ['book_value' => $book_value] );
                $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                $assetGroup->decrement( 'total_consumtion', $one->consumption_amount );
                if ($one->consumptionAssetItemExpenses) {
                    foreach ($one->consumptionAssetItemExpenses as $itemExpens) {
                        $asset = Asset::find( $itemExpens->asset_id );
                        $asset->decrement( 'total_current_consumtion', $itemExpens->consumption_amount );
                        $asset->decrement( 'current_consumtion', $itemExpens->consumption_amount );
                        $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                        $assetGroup->decrement( 'total_consumtion', $itemExpens->consumption_amount );
                    }
                    $one->consumptionAssetItemExpenses()->delete();
                }
            }
            $consumptionAsset->items()->delete();
            foreach ($data['items'] as $item) {
                $asset = Asset::find( $item['asset_id'] );
                $diff = 0;
                $stop_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->first()->date : '';
                $activate_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->first()->date : '';
                if (!empty( $stop_date ) && !empty( $activate_date )) {
                    $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                    $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                    $diff = $activate_date->diffInDays( $stop_date );
                }
                $diff_in_days -= $diff;
                $consumption_amount = 0;
                if ($consumptionAsset->type != 'expenses') {
                    $age = (($asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion) / $item['annual_consumtion_rate']) / 100;
                    $months = $age * 12;
                    $asd = ($asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion) / $months;
                    $value = $asd * ($diff_in_days / 30);
                    $consumption_amount = number_format( $value, 2 );

                    $asset->increment( 'total_current_consumtion', $value );
                    $asset->increment( 'current_consumtion', $value );
                    $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion - $asset->past_consumtion;
                    $asset->update( ['book_value' => $book_value] );
                    $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                    $assetGroup->increment( 'total_consumtion', $consumption_amount );
                }


                $ConsumptionAssetItem = ConsumptionAssetItem::create( [
                    'consumption_asset_id' => $consumptionAsset->id,
                    'asset_id' => $item['asset_id'],
                    'asset_group_id' => $asset->asset_group_id,
                    'consumption_amount' => $consumption_amount,
                ] );

                if (in_array( $consumptionAsset->type, ['expenses', 'both'] )) {
                    $ConsumptionAssetItem->consumptionAssetItemExpenses()->delete();
                    foreach ($asset->expenses as $expens) {
                        $to = Carbon::createFromFormat( 'Y-m-d', $request->date_to );
                        $from = Carbon::createFromFormat( 'Y-m-d', $request->date_from );

                        $diff = 0;
                        $stop_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->first()->date : '';
                        $activate_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->first()->date : '';
                        if (!empty( $stop_date ) && !empty( $activate_date )) {
                            $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                            $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                            $diff = $activate_date->diffInDays( $stop_date );;
                        }
                        $diff_in_days = $to->diffInDays( $from );
                        $diff_in_days -= $diff;
                        $age = (($expens->price - $expens->expenseConsumptions->sum( 'consumption_amount' )) / $expens->annual_consumtion_rate) / 100;
                        $months = $age * 12;
                        $asd = ($expens->price - $expens->expenseConsumptions->sum( 'consumption_amount' )) / $months;
                        $value = $asd * ($diff_in_days / 30);
                        $value = number_format( $value, 2 );
                        ConsumptionAssetItemExpense::create( [
                                'asset_id' => $asset->id,
                                'consumption_amount' => number_format( $value, 2 ),
                                'consumption_asset_item_id' => $ConsumptionAssetItem->id,
                                'expense_id' => $expens->id
                            ]
                        );
                        $asset->increment( 'total_current_consumtion', $value );
                        $asset->increment( 'current_consumtion', $value );
                        $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion - $asset->past_consumtion;
                        $asset->update( ['book_value' => $book_value] );
                        $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                        $assetGroup->increment( 'total_consumtion', $value );
                    }
                }

            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to( route( 'admin:consumption-assets.edit', $consumptionAsset->id ) )
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }

        return redirect()->to( route( 'admin:consumption-assets.index' ) )
            ->with( ['message' => __( 'words.consumption-assets-updated' ), 'alert-type' => 'success'] );

    }

    public function destroy(ConsumptionAsset $consumptionAsset)
    {
        foreach ($consumptionAsset->items as $item) {
            if (SaleAssetItem::where( 'asset_id', $item->asset->id )->exists() || AssetReplacementItem::where( 'asset_id', $item->asset->id )->exists()) {
                return redirect()->to( route( 'admin:consumption-assets.index' ) )
                    ->with( ['message' => __( 'words.can-not-delete-this-data-cause-there-is-related-data' ), 'alert-type' => 'error'] );
            }
        }
        DB::beginTransaction();
        try {
            foreach ($consumptionAsset->items as $item) {
                if ($item->consumptionAssetItemExpenses) {
                    foreach ($item->consumptionAssetItemExpenses as $itemExpens) {
                        $asset = Asset::find( $itemExpens->asset_id );
                        $assets[] = $itemExpens->asset_id;
                        $asset->decrement( 'total_current_consumtion', $itemExpens->consumption_amount );
                        $asset->decrement( 'current_consumtion', $itemExpens->consumption_amount );
                        $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                        $assetGroup->decrement( 'total_consumtion', $itemExpens->consumption_amount );
                    }
                    $item->consumptionAssetItemExpenses()->delete();
                }
                $item->asset->group()->decrement( 'total_consumtion', $item->consumption_amount );
                $item->asset()->decrement( 'total_current_consumtion', $item->consumption_amount );
                $item->asset()->decrement( 'current_consumtion', $item->consumption_amount );
                $assets[] = $item->asset->id;

            }
            $consumptionAsset->items()->delete();
            $consumptionAsset->delete();
            DB::commit();
            $asd = Asset::whereIn( 'id', array_unique( $assets ) )->get();
            foreach ($asd as $value) {
                $book_value = $value->purchase_cost + $value->total_replacements - $value->total_current_consumtion - $value->past_consumtion;
                $value->update( ['book_value' => $book_value] );
            }

        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to( route( 'admin:consumption-assets.index' ) )
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }
        return redirect()->back()
            ->with( ['message' => __( 'words.consumption-asset-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request)
    {
        if (isset( $request->ids )) {
            $assets = [];
            foreach (array_unique( $request->ids ) as $invoiceId) {

                $consumptionAsset = ConsumptionAsset::find( $invoiceId );

                foreach ($consumptionAsset->items as $item) {
                    if (SaleAssetItem::where( 'asset_id', $item->asset->id )->exists() || AssetReplacementItem::where( 'asset_id', $item->asset->id )->exists()) {
                        return redirect()->to( route( 'admin:consumption-assets.index' ) )
                            ->with( ['message' => __( 'words.can-not-delete-this-data-cause-there-is-related-data' ), 'alert-type' => 'error'] );
                    }
                }
                DB::beginTransaction();
                try {
                    foreach ($consumptionAsset->items as $item) {
                        if ($item->consumptionAssetItemExpenses) {
                            foreach ($item->consumptionAssetItemExpenses as $itemExpens) {
                                $asset = Asset::find( $itemExpens->asset_id );
                                $assets[] = $itemExpens->asset_id;
                                $asset->decrement( 'total_current_consumtion', $itemExpens->consumption_amount );
                                $asset->decrement( 'current_consumtion', $itemExpens->consumption_amount );
                                $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                                $assetGroup->decrement( 'total_consumtion', $itemExpens->consumption_amount );
                            }
                            $item->consumptionAssetItemExpenses()->delete();
                        }
                        $item->asset->group()->decrement( 'total_consumtion', $item->consumption_amount );
                        $item->asset()->decrement( 'total_current_consumtion', $item->consumption_amount );
                        $item->asset()->decrement( 'current_consumtion', $item->consumption_amount );
                        $assets[] = $item->asset->id;

                    }
                    $consumptionAsset->items()->delete();
                    $consumptionAsset->delete();
                    DB::commit();
                    $asd = Asset::whereIn( 'id', array_unique( $assets ) )->get();
                    foreach ($asd as $value) {
                        $book_value = $value->purchase_cost + $value->total_replacements - $value->total_current_consumtion - $value->past_consumtion;
                        $value->update( ['book_value' => $book_value] );
                    }

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
        $asset = Asset::with( 'group' )->find( $request->asset_id );
        if($asset->consumption_type =='automatic'){
            return response()->json( __( 'can not  consumption asset manual , asset consumption type is automatic' ), 400 );
        }
        if (SaleAssetItem::where( 'asset_id', $request->asset_id )->count()) {
            return response()->json( __( 'can not  consumption asset after sale' ), 400 );
        }
        if (is_null( $request->branch_id ) && authIsSuperAdmin()) {
            return response()->json( __( 'please select valid branch' ), 400 );
        }

        $index = $request['index'] + 1;

        if (empty( (int)$asset->purchase_cost ) && !PurchaseAssetItem::where( 'asset_id', $request->asset_id )->whereHas( 'purchaseAsset', function ($q) {
                $q->where( 'operation_type', '=', 'purchase' );
            } )->count()) {
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
        if ($request->type != 'expenses') {
            $consumption_asset = ConsumptionAsset::join( 'consumption_asset_items', 'consumption_assets.id', '=', 'consumption_asset_items.consumption_asset_id' )
                ->where( function ($q) use ($request) {
                    $q->whereBetween( 'consumption_assets.date_to', array($request->date_to, $request->date_from) )
                        ->orWhereBetween( 'consumption_assets.date_from', array($request->date_from, $request->date_to) );
                } )
                ->where( 'consumption_asset_items.asset_id', $request->asset_id )
                ->where( 'consumption_asset_items.consumption_amount', '>', 0 )
                ->count( 'consumption_assets.id' );

            if ($consumption_asset && $request->type != 'update') {
                return response()->json( __( 'can not create consumption for asset in same dates more than once' ), 400 );
            }
        } else {
            $consumption_asset = ConsumptionAsset::join( 'consumption_asset_items', 'consumption_assets.id', '=', 'consumption_asset_items.consumption_asset_id' )
                ->join( 'consumption_asset_item_expenses', 'consumption_asset_items.id', '=', 'consumption_asset_item_expenses.consumption_asset_item_id' )
                ->where( function ($q) use ($request) {
                    $q->whereBetween( 'consumption_assets.date_to', array($request->date_to, $request->date_from) )
                        ->orWhereBetween( 'consumption_assets.date_from', array($request->date_from, $request->date_to) )
                        ->orWhereBetween( 'consumption_assets.date_to', array($request->date_from, $request->date_to) );
                } )
                ->where( 'consumption_asset_item_expenses.asset_id', $request->asset_id )
                ->count();
            if ($consumption_asset && $request->type != 'update') {
                return response()->json( __( 'can not create consumption for expense in same dates more than once' ), 400 );
            }
        }

        $expenses_total = 0;
        foreach ($asset->expenses()->whereHas( 'assetExpense', function ($q) {
            $q->where( 'status', '=', 'accept' );
        } )->get() as $expens) {

            $to = Carbon::createFromFormat( 'Y-m-d', $request->date_to );
            $from = Carbon::createFromFormat( 'Y-m-d', $request->date_from );

            $diff = 0;
            $stop_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->first()->date : '';
            $activate_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->first()->date : '';
            if (!empty( $stop_date ) && !empty( $activate_date )) {
                $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                $diff = $activate_date->diffInDays( $stop_date );;
            }
            $diff_in_days = $to->diffInDays( $from );
            $diff_in_days -= $diff;

            $age = $expens->annual_consumtion_rate ? ($expens->price / $expens->annual_consumtion_rate) / 100 : 0;
            $months = $age * 12;
            $asd = $months ? $expens->price / $months : 0;
            $value = $asd * ($diff_in_days / 30);
            $expenses_total += number_format( $value, 2 );
        }
        $assetGroup = $asset->group;


        $diff = 0;
        $stop_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->first()->date : '';
        $activate_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->first()->date : '';

        if (!empty( $stop_date ) && !empty( $activate_date )) {
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
            'expenses_total' => $expenses_total, 'diff' => $diff
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

    public function expenseTotal(Request $request)
    {
        $expenses_total = 0;
        if (!empty( $request->asset_id )) {
            $asset = Asset::with( 'group' )->find( $request->asset_id );
            foreach ($asset->expenses()->whereHas( 'assetExpense', function ($q) {
                $q->where( 'status', '=', 'accept' );
            } )->get() as $expens) {

                $to = Carbon::createFromFormat( 'Y-m-d', $request->date_to );
                $from = Carbon::createFromFormat( 'Y-m-d', $request->date_from );

                $diff = 0;
                $stop_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->first()->date : '';
                $activate_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->first()->date : '';
                if (!empty( $stop_date ) && !empty( $activate_date )) {
                    $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                    $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                    $diff = $activate_date->diffInDays( $stop_date );;
                }
                $diff_in_days = $to->diffInDays( $from );
                $diff_in_days -= $diff;
                $age = $expens->annual_consumtion_rate ? (($expens->price - $expens->expenseConsumptions->sum( 'consumption_amount' )) / $expens->annual_consumtion_rate) / 100 : 0;
                $months = $age * 12;
                $asd = $months ? ($expens->price - $expens->expenseConsumptions->sum( 'consumption_amount' )) / $months : 0;
                $value = $asd * ($diff_in_days / 30);
                $expenses_total += number_format( $value, 2 );
            }
        }
        $index = $request['index'];
        return response()->json( [
            'expenses_total' => $expenses_total,
            'index' => $index,
        ] );
    }

    public function automaticConsumption(Request $request)
    {
        DB::beginTransaction();
        try {
//            $groups = AssetGroup::where( 'consumption_type', 'automatic' )->with( 'assets' )->get();
//            foreach ($groups as $group) {
            $lastNumber = ConsumptionAsset::where( 'branch_id', auth()->user()->branch_id )->orderBy( 'id', 'desc' )->first();
            $number = $lastNumber ? $lastNumber->number + 1 : 1;
            $invoice_data = [
                'number' => $number,
                'date' => date( 'Y-m-d' ),
                'time' => date( 'H:i:s' ),
                'note' => '',
                'date_from' => date( 'Y-m-d' ),
                'date_to' => date( 'Y-m-d' ),
                'total_purchase_cost' => 0,
                'total_past_consumtion' => 0,
                'total_replacement' => 0,
                'user_id' => auth()->id(),
                'type' => ''
            ];
            $invoice_data['branch_id'] = auth()->user()->branch_id;


            $assets = Asset::where( 'consumption_type', 'automatic' )->whereHas( 'group', function ($query) {
                $query->where( 'consumption_type', 'automatic' );
            } )->with( 'expenses' )->whereNotNull( 'date_of_work' )->get();
            $consumptionAsset = ConsumptionAsset::create( $invoice_data );
            $total_purchase_cost = 0;
            $total_past_consumtion = 0;
            $total_replacements = 0;
            foreach ($assets as $asset) {

                $item = ConsumptionAssetItem::where( 'asset_id', $asset->id )->with( 'consumptionAsset' )->latest()->first();

                if ($item) {
                    $from = Carbon::parse( $item->consumptionAsset->date_to )->addDay()->format( 'Y-m-d' );
                    $to = Carbon::parse( $from )->addDays( $asset->consumption_period * 30 )->format( 'Y-m-d' );
                } else {
                    $from = Carbon::createFromFormat( 'Y-m-d', $asset->date_of_work );
                    $to = Carbon::parse( $from )->addDays( $asset->consumption_period * 30 );
                    $from = $from->format( 'Y-m-d' );
                    $to = $to->format( 'Y-m-d' );
                }

                $consumption = ConsumptionAsset::where( 'date_to', '>=', date( 'Y-m-d' ) )->whereHas( 'items', function ($query) use ($asset) {
                    $query->where( 'asset_id', $asset->id );
                } )->first();

                if ($consumption) {
                    continue;
                }
                if (empty( (int)$asset->purchase_cost ) && !PurchaseAssetItem::where( 'asset_id', $asset->asset_id )->whereHas( 'purchaseAsset', function ($q) {
                        $q->where( 'operation_type', '=', 'purchase' );
                    } )->count()) {
                    continue;
                }

                if ($asset->group->consumption_for != 'expenses') {
                    $consumption_asset = ConsumptionAsset::join( 'consumption_asset_items', 'consumption_assets.id', '=', 'consumption_asset_items.consumption_asset_id' )
                        ->where( function ($q) use ($to, $from) {
                            $q->whereBetween( 'consumption_assets.date_to', array($to, $from) )
                                ->orWhereBetween( 'consumption_assets.date_from', array($from, $to) );
                        } )
                        ->where( 'consumption_asset_items.asset_id', $asset->id )
                        ->where( 'consumption_asset_items.consumption_amount', '>', 0 )
                        ->count();
                    if ($consumption_asset) {
                        continue;
                    }
                } else {
                    $consumption_asset = ConsumptionAsset::join( 'consumption_asset_items', 'consumption_assets.id', '=', 'consumption_asset_items.consumption_asset_id' )
                        ->join( 'consumption_asset_item_expenses', 'consumption_asset_items.id', '=', 'consumption_asset_item_expenses.consumption_asset_item_id' )
                        ->where( function ($q) use ($to, $from) {
                            $q->whereBetween( 'consumption_assets.date_to', array($to, $from) )
                                ->orWhereBetween( 'consumption_assets.date_from', array($from, $to) )
                                ->orWhereBetween( 'consumption_assets.date_to', array($from, $to) );
                        } )
                        ->where( 'consumption_asset_item_expenses.asset_id', '=', $asset->id )->count();
                    if ($consumption_asset) {
                        continue;
                    }
                }
                $diff_in_days = $asset->consumption_period * 30;

                $diff = 0;
                $stop_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->first()->date : '';
                $activate_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->first()->date : '';
                if (!empty( $stop_date ) && !empty( $activate_date )) {
                    $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                    $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                    $diff = $activate_date->diffInDays( $stop_date );
                }
                $diff_in_days -= $diff;
                $consumption_amount = 0;
                if ($asset->group->consumption_for != 'expenses') {
                    $age = $asset->age_years;
                    $months = ($age * 12) + (int)$asset->age_months;
                    $asd = ($asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion) / $months;
                    $value = $asd * ($diff_in_days / 30);
                    $consumption_amount = number_format( $value, 2 );

                    $asset->increment( 'total_current_consumtion', $value );
                    $asset->increment( 'current_consumtion', $value );
                    $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion - $asset->past_consumtion;
                    $asset->update( ['book_value' => $book_value] );
                    $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                    $assetGroup->increment( 'total_consumtion', $consumption_amount );
                }
                $total_purchase_cost += $asset->purchase_cost;
                $total_past_consumtion += $asset->past_consumtion;
                $total_replacements += $consumption_amount;
                $ConsumptionAssetItem = ConsumptionAssetItem::create( [
                    'consumption_asset_id' => $consumptionAsset->id,
                    'asset_id' => $asset->id,
                    'asset_group_id' => $asset->asset_group_id,
                    'consumption_amount' => $consumption_amount,
                ] );

                if (in_array( $asset->group->consumption_for, ['expenses', 'both'] )) {
                    foreach ($asset->expenses()->whereHas( 'assetExpense', function ($q) {
                        $q->where( 'status', '=', 'accept' );
                    } )->get() as $expens) {
                        $diff = 0;
                        $stop_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'stop' )->latest()->first()->date : '';
                        $activate_date = StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->exists() ? StopAndActivateAsset::where( 'asset_id', $asset->id )->where( 'status', '=', 'activate' )->latest()->first()->date : '';
                        if (!empty( $stop_date ) && !empty( $activate_date )) {
                            $activate_date = Carbon::createFromFormat( 'Y-m-d', $activate_date );
                            $stop_date = Carbon::createFromFormat( 'Y-m-d', $stop_date );
                            $diff = $activate_date->diffInDays( $stop_date );;
                        }
                        $diff_in_days = $asset->consumption_period * 30;
                        $diff_in_days -= $diff;
                        $age = $expens->age_years;
                        $months = ($age * 12) + (int)$expens->age_months;
                        $asd = ($expens->price - $expens->expenseConsumptions->sum( 'consumption_amount' )) / $months;
                        $value = $asd * ($diff_in_days / 30);

                        ConsumptionAssetItemExpense::create( [
                                'asset_id' => $asset->id,
                                'consumption_amount' => number_format( $value, 2 ),
                                'consumption_asset_item_id' => $ConsumptionAssetItem->id,
                                'expense_id' => $expens->id
                            ]
                        );
                        $asset->increment( 'total_current_consumtion', $value );
                        $asset->increment( 'current_consumtion', $value );
                        $book_value = $asset->purchase_cost + $asset->total_replacements - $asset->total_current_consumtion - $asset->past_consumtion;
                        $asset->update( ['book_value' => $book_value] );
                        $assetGroup = AssetGroup::where( 'id', $asset->asset_group_id )->first();
                        $assetGroup->increment( 'total_consumtion', $value );
                        $total_replacements += $value;
                    }
                }
//                }
                $consumptionAsset->update( [
                    'date_from' => $from,
                    'date_to' => $to,
                    'total_purchase_cost' => $total_purchase_cost,
                    'total_past_consumtion' => $total_past_consumtion,
                    'total_replacement' => $total_replacements
                ] );
            }
            if (!count( $consumptionAsset->items )) {
                DB::rollBack();
                return redirect()->to( route( 'admin:consumption-assets.index' ) )
                    ->with( ['message' => __( 'No assets Or Expenses to Create Consumption For them !' ), 'alert-type' => 'info'] );
            } else {
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            dd( $e->getMessage() . '-' . $e->getLine() );
            return redirect()->to( route( 'admin:consumption-assets.index' ) )
                ->with( ['message' => __( $e->getMessage() ), 'alert-type' => 'error'] );
        }
        return redirect()->to( route( 'admin:consumption-assets.index' ) )
            ->with( ['message' => __( 'words.consumption-assets-created' ), 'alert-type' => 'success'] );

    }
}
