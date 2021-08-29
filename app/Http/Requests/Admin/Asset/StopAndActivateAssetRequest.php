<?php


namespace App\Http\Requests\Admin\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StopAndActivateAssetRequest extends FormRequest
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
                'asset_id' => 'required',
                'date' => 'required',
                'status'=>'required|in:stop,activate'
            ];
    }

    public function attributes(): array
    {
        return [
        ];
    }
}
