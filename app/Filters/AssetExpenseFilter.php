<?php

namespace App\Filters;

use App\Models\Asset;
use App\Models\AssetExpense;
use App\Models\AssetsItemExpense;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Class AssetExpenseFilter
 * @package App\Filters
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetExpenseFilter
{
    public function filter(Request $request): Builder
    {
        return AssetExpense::where(function ($query) use ($request) {
            if ($request->has('number') && $request->number != '' && $request->number != null) {
                $query->where('id', $request->number);
            }

            if ($request->has('status') && $request->status != '' && $request->status != null) {
                $query->where('status', $request->status);
            }


            if ($request->has('asset_name') && $request->asset_name != '' && $request->asset_name != null) {
                $query->whereHas('assetExpensesItems', function ($q) use ($request) {
                    $q->where('asset_id', $request->asset_name);
                });
            }

            if ($request->has('asset_group_name') && $request->asset_group_name != '' && $request->asset_group_name != null) {
                $query->whereHas('assetExpensesItems', function ($q) use ($request) {
                    $asset = Asset::where('asset_group_id', $request->asset_group_name)->first();
                    if ($asset) {
                        $q->where('asset_id', $asset->id);
                    }
                });
            }

            if ($request->has('asset_expense_item') && $request->asset_expense_item != '' && $request->asset_expense_item != null) {
                $query->whereHas('assetExpensesItems', function ($q) use ($request) {
                    $q->where('asset_expense_item_id', $request->asset_expense_item);
                });
            }

            if ($request->has('asset_expense_type') && $request->asset_expense_type != '' && $request->asset_expense_type != null) {
                $query->whereHas('assetExpensesItems', function ($q) use ($request) {
                    $assetExpenseItem = AssetsItemExpense::where('assets_type_expenses_id', $request->asset_expense_type)->first();
                    if ($assetExpenseItem) {
                        $q->where('asset_expense_item_id', $assetExpenseItem->id);
                    }
                });
            }

            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('dateFrom') && $request->has('dateTo')
                && $request->dateFrom != '' && $request->dateTo != ''
                && $request->dateFrom != null && $request->dateTo != null) {
                $query->whereBetween('date', [$request->dateFrom, $request->dateTo]);
            }
        });
    }
}
