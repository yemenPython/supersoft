<?php

namespace App\Http\Requests\Admin\Asset;

use App\Models\PurchaseInvoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PurchaseAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $branch_id = authIsSuperAdmin() ?  $this->branch_id : auth()->user()->branch_id;
        $rules = [
            'invoice_number' => ['required', 'max:50', 'string',
                Rule::unique( 'purchase_assets' )
                    ->ignore( $this->purchase_asset )
                    ->whereNull( 'deleted_at' )
                    ->where( 'operation_type',  'purchase' )
                    ->where( 'branch_id', $branch_id )
            ],
            'date' => 'required|date',
            'time' => 'required',
            'annual_consumtion_rate' => 'nullable|numeric|min:0|max:100',
            'operation_type' => 'required|in:purchase,opening_balance',
            'supplier_id' => 'nullable|required_if:operation_type,purchase|exists:suppliers,id',
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'invoice_number' => __( 'Invoice Number' ),
            'operation_type' => __( 'Operation Type' ),
            'supplier_id' => __( 'Supplier' ),
        ];
    }
}
