<?php

namespace App\Http\Requests\Admin\PurchaseQuotation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
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

            'number' => 'required|string|max:50',
            'date' => 'required|date',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'time' => 'required',
            'quotation_type' => 'required|string|in:cash,credit',
            'supply_date_from' => 'nullable|date',
            'supply_date_to' => 'nullable|date|after_or_equal:date_from',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|string|in:amount,percent',
            'supplier_discount' => 'required|numeric|min:0',
            'supplier_discount_type' => 'required|string|in:amount,percent',

            'items' => 'array',

            'items.*.part_id' => 'required|integer|exists:parts,id',
            'items.*.part_price_id' => 'required|integer|exists:part_prices,id',
            'items.*.part_price_segment_id' => 'nullable|integer|exists:part_price_segments,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'required|numeric|min:0',
            'items.*.discount_type' => 'required|string|in:amount,percent',
            'items.*.spare_part_id' => 'required|integer|exists:spare_parts,id',

            'taxes.*' => 'nullable|integer|exists:taxes_fees,id',
        ];

        $branch_id = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = request()['branch_id'];
        }

        if (request()->has('type') && request()->type == 'from_purchase_request') {
            $rules['purchase_request_id'] = 'required|integer|exists:purchase_requests,id';
        }

        $rules['number'] =
            [
                'required', 'string', 'max:50',
                Rule::unique('purchase_quotations')->where(function ($query) use ($branch_id) {
                    return $query->where('number', request()->number)
                        ->where('branch_id', $branch_id);
                }),
            ];

        for ($i = 1; $i < 50; $i++) {

            $rules['items[' . $i . '][part_id]'] = 'nullable|integer|min:1';
            $rules['items[' . $i . '][part_price_id]'] = 'nullable|integer|exists:part_prices,id';
            $rules['items[' . $i . '][part_price_segment_id]'] = 'nullable|integer|exists:part_price_segments,id';
            $rules['items[' . $i . '][quantity]'] = 'nullable|integer|min:1';
            $rules['items[' . $i . '][price]'] = 'nullable|numeric|min:1';
            $rules['items[' . $i . '][discount]'] = 'nullable|numeric|min:0';
            $rules['items[' . $i . '][discount_type]'] = 'nullable|string|in:amount,percent';
            $rules['items[' . $i . '][spare_part_id]'] = 'nullable|integer|exists:spare_parts,id';
        }

        return $rules;
    }
}
