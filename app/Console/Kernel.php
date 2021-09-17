<?php

namespace App\Console;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\ConsumptionAsset;
use App\Models\ConsumptionAssetItem;
use App\Models\ConsumptionAssetItemExpense;
use App\Models\PurchaseAssetItem;
use App\Models\StopAndActivateAsset;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;
use Exception;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AppNotificationClear::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command( 'notification:clear' )->monthly();
        $schedule->call( function () {
            DB::beginTransaction();
            try {
                $invoice_data = [
                    'number' => 1,
                    'date' => date( 'Y-m-d' ),
                    'time' => date( 'H:i:s' ),
                    'note' => '',
                    'date_from' => date( 'Y-m-d' ),
                    'date_to' => date( 'Y-m-d' ),
                    'total_purchase_cost' => 0,
                    'total_past_consumtion' => 0,
                    'total_replacement' => 0,
                    'user_id' => 1,
                    'type' => ''
                ];
                $invoice_data['branch_id'] = 1;
//                 $invoice_data['branch_id'] = auth()->user()->branch_id;
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
                    $lastNumber = ConsumptionAsset::where( 'branch_id', $consumption->branch_id )->orderBy( 'id', 'desc' )->first();
                    $number = $lastNumber ? $lastNumber->number + 1 : 1;
                    $consumptionAsset->update( [
                        'date_from' => $from,
                        'date_to' => $to,
                        'total_purchase_cost' => $total_purchase_cost,
                        'total_past_consumtion' => $total_past_consumtion,
                        'total_replacement' => $total_replacements,
                        'number' => $number,
                        'branch_id' => $consumption->branch_id
                    ] );
                }
                if (!count( $consumptionAsset->items )) {
                    DB::rollBack();
                } else {
                    DB::commit();
                }
            } catch (Exception $e) {
                DB::rollBack();
            }
        } )->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load( __DIR__ . '/Commands' );

        require base_path( 'routes/console.php' );
    }
}
