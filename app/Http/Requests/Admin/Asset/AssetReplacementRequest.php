<?php


namespace App\Http\Requests\Admin\Asset;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class AssetReplacementRequest
 * @package App\Http\Requests\Admin\Asset
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetReplacementRequest extends FormRequest
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
                'total_after_replacement' => 'required|numeric',
                'total_before_replacement' => 'required|numeric',
                'items' => 'required|array',
                'items*value_replacement' => 'required|numeric',
                'items*value_after_replacement' => 'required|numeric',
                'items*asset_id' => 'required|exists:assets_tb,id',
            ];
    }

    public function attributes(): array
    {
       return [
         'items' => __('Assets Data')
       ];
    }
}
