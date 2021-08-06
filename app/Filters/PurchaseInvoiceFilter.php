<?php

namespace App\Filters;

use App\Models\PurchaseInvoice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PurchaseInvoiceFilter
{
    public function filter(Request $request): Builder
    {
        return PurchaseInvoice::where(function ($query) use ($request) {
            if ($request->filled('invoice_number')) {
                $query->where('id', $request->invoice_number);
            }

            if ($request->filled('branchId')) {
                $query->where('branch_id', $request->branchId);
            }

            if ($request->filled('invoice_type')) {
                $query->where('invoice_type', $request->invoice_type);
            }

            if ($request->filled('type') && $request->type != 'together') {
                $query->where('type', $request->type);
            }

            if ($request->filled('type') && $request->type == 'together') {
                $query->whereIn('type', ['credit', 'cash']);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('supply_order_number')) {
                $query->where('supply_order_id', $request->supply_order_number);
            }

            if ($request->filled('date_add_from') && $request->filled('date_add_to')) {

                $query->whereBetween('date', [$request->date_add_from, $request->date_add_to]);
            }

        });
    }
}
