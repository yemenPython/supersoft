<?php

namespace App\Filters;

use App\Model\PurchaseReturn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PurchaseReturnInvoiceFilter
{
    public function filter(Request $request): Builder
    {
        return PurchaseReturn::where(function ($query) use ($request) {
            if ($request->filled('branchId')) {
                $query->where('branch_id', $request->branchId);
            }

            if ($request->filled('invoice_type')) {
                $query->where('invoice_type', $request->invoice_type);
            }

            if ($request->filled('invoice_number_return')) {
                $query->where('id', $request->invoice_number_return);
            }

            if ($request->filled('invoice_number')) {
                $query->where('purchase_invoice_id', $request->invoice_number);
            }

            if ($request->filled('supply_order_number')) {
                $query->whereHas('supplyOrders', function ($q) use ($request) {
                    $q->where('supply_order_id', $request->supply_order_number);
                });
            }

            if ($request->has('invoice_number') && $request->invoice_number != '' && $request->invoice_number != null) {
                $query->where('invoice_number', $request->invoice_number);
            }

            if ($request->has('purchase_invoice_id') && $request->purchase_invoice_id != '' && $request->purchase_invoice_id != null) {
                $query->where('purchase_invoice_id', $request->purchase_invoice_id);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
//            if ($request->has('supplier_id') && $request->supplier_id != '' && $request->supplier_id != null) {
//                $query->where('supplier_id', $request->supplier_id);
//            }
            if ($request->filled('date_add_from') && $request->filled('date_add_to')) {
                $query->whereBetween('date', [$request->date_add_from, $request->date_add_to]);
            }
        });
    }
}
