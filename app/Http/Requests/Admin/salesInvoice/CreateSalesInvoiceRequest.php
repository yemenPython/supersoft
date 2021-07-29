<?php

namespace App\Http\Requests\Admin\salesInvoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSalesInvoiceRequest extends FormRequest
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
//        $rules = [
//
//            'customer_id' => 'nullable|integer|exists:customers,id',
//            'date' => 'required|date',
//            'time' => 'required',
//            'part_ids' => 'required',
//            'part_ids.*' => 'required|integer|exists:parts,id',
//
////            'purchase_invoice_id' => 'required',
////            'purchase_invoice_id.*' => 'required|integer|exists:purchase_invoices,id',
//
//            'available_qy' => 'required',
//            'available_qy.*' => 'required|integer|min:1',
//
//            'sold_qty' => 'required',
//            'sold_qty.*' => 'required|integer|min:1',
//
//            'last_selling_price' => 'required',
//            'last_selling_price.*' => 'required|numeric|min:0',
//
//            'selling_price' => 'required',
//            'selling_price.*' => 'required|numeric|min:1',
//
//            'item_discount' => 'nullable',
//            'item_discount.*' => 'nullable|numeric|min:0',
//
//            'parts_count' => 'required|integer|min:1',
//            'invoice_tax' => 'required|numeric|min:0',
//
//            'discount_type' => 'required|string|in:amount,percent',
//            'discount' => 'nullable|numeric|min:0',
//            'type' => 'required|string|in:cash,credit'
//        ];
//
//        if($this->request->has('part_ids')) {
//            foreach (request()['part_ids'] as $index => $part_id) {
//
//                $part_index = request()['index'][$index];
//
//                $rules['item_discount_type_' . $part_index] = 'required|string|in:amount,percent';
//            }
//        }
//
//        if (authIsSuperAdmin()) {
//            $rules['branch_id'] = 'required|integer|exists:branches,id';
//        }


        $rules = [

            'date' => 'required|date',
            'time' => 'required',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|string|in:amount,percent',
            'type_for' => 'required|string|in:supplier,customer',
            'type' => 'required|string|in:cash,credit',
            'status' => 'required|string|in:pending,processing,finished',

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
                Rule::unique('sales_invoices')->where(function ($query) use($branch_id) {
                    return $query->where('number', request()->number)
                        ->where('branch_id', $branch_id);
//                        ->where('deleted_at', null)

                }),
            ];

        return $rules;
    }
}
