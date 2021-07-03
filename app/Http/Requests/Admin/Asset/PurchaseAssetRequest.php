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
                    ->where( 'branch_id', $branch_id )
            ],
            'date' => 'required|date',
            'time' => 'required',
//            'asset_id' => 'required|exists:assets_tb,id',
//            'items' => 'required|array',
            'supplier_id' => 'required|integer|exists:suppliers,id',
//            'items.*.asset_id' => 'required|integer|exists:assets_tb,id',
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'invoice_number' => __( 'Invoice Number' )
        ];
    }
}
