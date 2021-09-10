<?php

namespace App\Http\Requests\Admin\SalesInvoicesReturn;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSalesInvoiceReturnRequest extends FormRequest
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
            'type' => 'required|string|in:cash,credit',
            'status' => 'required|string|in:pending,processing,finished',
            'invoice_type'=>'required|string|in:normal,direct_invoice,direct_sale_quotations,from_sale_supply_order,from_sale_quotations',

            'items.*.part_id' => 'required|integer|exists:parts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount' => 'required|numeric|min:0',
            'items.*.discount_type' => 'required|string|in:amount,percent',

            'items.*.taxes.*' => 'required|integer|exists:taxes_fees,id',

            'taxes.*' => 'nullable|integer|exists:taxes_fees,id',
            'additional_payments.*' => 'nullable|integer|exists:taxes_fees,id',

            'invoiceable_id'=>'required|integer'
        ];

        $branch_id = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = request()['branch_id'];
        }

        if (in_array(request()['invoice_type'], ['direct_invoice', 'direct_sale_quotations'])) {

            $rules['invoiceable_id'] = 'required|integer|exists:sales_invoices,id';
            $rules['items.*.item_id'] = 'required|integer|exists:sales_invoice_items,id';
        }else {

            $rules['invoiceable_id'] = 'required|integer|exists:returned_sale_receipts,id';
            $rules['items.*.item_id'] = 'required|integer|exists:returned_sale_receipt_items,id';
        }

//        $rules['number'] =
//            [
//                'required','string', 'max:50',
//                Rule::unique('sales_invoice_returns')->ignore($this->salesInvoiceReturn->id)->where(function ($query) use($branch_id) {
//                    return $query->where('number', request()->number)
//                        ->where('branch_id', $branch_id);
////                        ->where('deleted_at', null)
//
//                }),
//            ];

        return $rules;
    }
}
