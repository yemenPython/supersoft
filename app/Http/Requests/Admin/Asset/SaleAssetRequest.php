<?php


namespace App\Http\Requests\Admin\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaleAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function appendBranchRule(): ?array
    {
        return authIsSuperAdmin() ? ['branch_id' => 'required|exists:branches,id'] : [];
    }

    public function rules(): array
    {
        return $this->appendBranchRule() + [
                'number' => 'required',
                'date' => 'required',
                'time' => 'required',
                'type' => 'required',
                'items' => 'required|array',
                'items.*.sale_amount' => 'required|numeric',
            ];
    }

    public function attributes(): array
    {
        return [
            'items' => __( 'Sale Amount' )
        ];
    }
}
