<?php

namespace App\Http\Requests\Admin\PurchaseInvoice;

use App\Models\PurchaseInvoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [

//            'number' => 'required|string|max:50',
            'date' => 'required|date',
            'time' => 'required',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|string|in:amount,percent',
            'invoice_type' => 'required|string|in:normal,from_supply_order',
            'type' => 'required|string|in:cash,credit',

            'items.*.part_id' => 'required|integer|exists:parts,id',
            'items.*.part_price_id' => 'required|integer|exists:part_prices,id',
            'items.*.part_price_segment_id' => 'nullable|integer|exists:part_price_segments,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'required|numeric|min:0',
            'items.*.discount_type' => 'required|string|in:amount,percent',
            'items.*.taxes.*' => 'required|integer|exists:taxes_fees,id',
            'items.*.store_id' => 'required|integer|exists:stores,id',


            'taxes.*' => 'nullable|integer|exists:taxes_fees,id',
            'additional_payments.*' => 'nullable|integer|exists:taxes_fees,id',

            'purchase_receipts.*' => 'nullable|integer|exists:purchase_receipts,id',
        ];

        $branch_id = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = request()['branch_id'];
        }

        if (request()->invoice_type == 'normal') {
            $rules['items.*.spare_part_id'] = 'required|integer|exists:spare_parts,id';
        }

        $rules['invoice_number'] =
            [
                'required','string', 'max:50',
                Rule::unique('purchase_invoices')->where(function ($query) use($branch_id) {
                    return $query->where('invoice_number', request()->invoice_number)
                        ->where('branch_id', $branch_id)
//                        ->where('deleted_at', null)
                        ;
                }),
            ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'invoice_number' => __('Invoice Number')
        ];
    }
}
