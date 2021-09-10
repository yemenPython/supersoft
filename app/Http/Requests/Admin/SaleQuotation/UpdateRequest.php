<?php

namespace App\Http\Requests\Admin\SaleQuotation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'time' => 'required',
            'type' => 'required|string|in:cash,credit',
            'supply_date_from' => 'nullable|date',
            'supply_date_to' => 'nullable|date|after_or_equal:date_from',
            'type_for' => 'required|string|in:supplier,customer',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|string|in:amount,percent',
            'customer_discount'=>'required|numeric|min:0',
            'customer_discount_type'=>'required|string|in:amount,percent',

            'items.*.part_id' => 'required|integer|exists:parts,id',
            'items.*.part_price_id' => 'required|integer|exists:part_prices,id',
            'items.*.part_price_segment_id' => 'nullable|integer|exists:part_price_segments,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.discount' => 'required|numeric|min:0',
            'items.*.discount_type' => 'required|string|in:amount,percent',
            'items.*.spare_part_id' => 'required|integer|exists:spare_parts,id',

            'taxes.*' => 'nullable|integer|exists:taxes_fees,id',
            'terms.*' => 'nullable|integer|exists:supply_terms,id',
        ];

        $branch_id = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = request()['branch_id'];
        }

//        $rules['number'] =
//            [
//                'required','string', 'max:50',
//                Rule::unique('sale_quotations')->ignore($this->sale_quotation->id)->where(function ($query) use($branch_id) {
//                    return $query->where('number', request()->number)
//                        ->where('branch_id', $branch_id)
//                        ;
//                }),
//            ];

        if (request()->type_for == 'supplier') {
            $rules['salesable_id'] = 'required|integer|exists:suppliers,id';
        }

        if (request()->type_for == 'customer') {
            $rules['salesable_id'] = 'required|integer|exists:customers,id';
        }

        return $rules;
    }
}
