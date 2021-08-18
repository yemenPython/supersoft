<?php

namespace App\Http\Requests\Admin\ReturnSaleReceipt;

use Illuminate\Foundation\Http\FormRequest;

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
            'date' => 'required|date',
            'time' => 'required',
            'notes' => 'nullable|string',
            'type'=>'required|string|in:from_invoice,from_sale_quotation,from_sale_supply_order',

            'items.*.store_id' => 'required|integer|exists:stores,id',
            'items.*.refused_quantity' => 'required|integer|min:0',
            'items.*.accepted_quantity' => 'required|integer|min:0',
            'salesable_id'=>'required|integer'
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        if (request()['type'] == 'from_invoice') {
            $rules['salesable_id'] = 'required|integer|exists:sales_invoices,id';

        }elseif (request()['type'] == 'from_sale_quotation'){
            $rules['salesable_id'] = 'required|integer|exists:sale_quotations,id';

        }else {
            $rules['salesable_id'] = 'required|integer|exists:sale_supply_orders,id';
        }

        return $rules;
    }
}
