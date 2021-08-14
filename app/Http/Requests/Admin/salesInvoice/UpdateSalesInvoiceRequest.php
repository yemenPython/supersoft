<?php

namespace App\Http\Requests\Admin\salesInvoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSalesInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [

            'date' => 'required|date',
            'time' => 'required',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|string|in:amount,percent',
            'type_for' => 'required|string|in:supplier,customer',
            'type' => 'required|string|in:cash,credit',
            'status' => 'required|string|in:pending,processing,finished',
            'invoice_type'=>'required|string|in:normal,direct_invoice,direct_sale_quotations,from_sale_supply_order,from_sale_quotations',

            'items.*.part_id' => 'required|integer|exists:parts,id',
            'items.*.part_price_id' => 'required|integer|exists:part_prices,id',
            'items.*.part_price_segment_id' => 'nullable|integer|exists:part_price_segments,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'required|numeric|min:0',
            'items.*.discount_type' => 'required|string|in:amount,percent',
            'items.*.taxes.*' => 'required|integer|exists:taxes_fees,id',
            'items.*.store_id' => 'required|integer|exists:stores,id',
            'items.*.spare_part_id' => 'required|integer|exists:spare_parts,id',

            'taxes.*' => 'nullable|integer|exists:taxes_fees,id',

            'additional_payments.*' => 'nullable|integer|exists:taxes_fees,id',

            'sale_quotation_ids.*'=>'nullable|integer|exists:sale_quotations,id'
        ];

        $branch_id = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = request()['branch_id'];
        }

        if (request()->type_for == 'supplier') {
            $rules['salesable_id'] = 'required|integer|exists:suppliers,id';
        }

        if (request()->type_for == 'customer') {
            $rules['salesable_id'] = 'required|integer|exists:customers,id';
        }

        $rules['number'] =
            [
                'required','string', 'max:50',
                Rule::unique('sales_invoices')->ignore($this->salesInvoice->id)->where(function ($query) use($branch_id) {
                    return $query->where('number', request()->number)
                        ->where('branch_id', $branch_id);
//                        ->where('deleted_at', null)

                }),
            ];

        return $rules;
    }
}
