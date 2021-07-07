<?php

namespace App\Http\Requests\Admin\PurchaseReturn;

use App\Model\PurchaseReturn;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseReturnRequest extends FormRequest
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
        $id = request()->segment(5);

        $invoice = PurchaseReturn::find($id);

        $branch_id = authIsSuperAdmin() ? request()['branch_id'] : auth()->user()->branch_id;

        if ($invoice) {
            $validationForInvoiceN = ['required',
                Rule::unique('purchase_returns', 'invoice_number')->ignore($invoice)
                    ->where('deleted_at', null)->where('branch_id', $branch_id)];
        } else {
            $validationForInvoiceN = "required|unique:purchase_returns,invoice_number,NULL,id,deleted_at,NULL";
        }

        $rules = [

            'invoice_number' => $validationForInvoiceN,
            'date' => 'required|date',
            'time' => 'required',
            'discount' => 'required|numeric|min:0',
            'discount_type' => 'required|string|in:amount,percent',
            'status' => 'required|string|in:pending,processing,finished',
            'invoice_type' => 'required|string|in:from_supply_order,normal',

            'items.*.part_id' => 'required|integer|exists:parts,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:1',
            'items.*.discount' => 'required|numeric|min:0',
            'items.*.discount_type' => 'required|string|in:amount,percent',
            'items.*.taxes.*' => 'nullable|integer|exists:taxes_fees,id',

            'taxes.*' => 'nullable|integer|exists:taxes_fees,id',
            'additional_payments.*' => 'nullable|integer|exists:taxes_fees,id',
        ];

        if (request()['invoice_type'] == 'normal') {

            $rules['purchase_invoice_id'] = 'required|exists:purchase_invoices,id';

        }else {

            $rules['supply_order_ids'] = 'required';
            $rules['supply_order_ids.*'] = 'required|integer|exists:supply_orders,id';

            $rules['purchase_receipts'] = 'required';
            $rules['purchase_receipts.*'] = 'required|integer|exists:purchase_receipts,id';
        }

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'invoice_number' => __('Invoice Number')
        ];
    }
}
