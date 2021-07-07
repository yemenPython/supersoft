<?php

namespace App\Http\Requests\Admin\Asset;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateAssetReplacementRequest
 * @package App\Http\Requests\Admin\Asset
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class UpdateAssetReplacementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
                'number' => 'required',
                'date' => 'required',
                'time' => 'required',
                'total_after_replacement' => 'required|numeric',
                'total_before_replacement' => 'required|numeric',
                'items' => 'required|array',
                'items.*.value_replacement' => 'required|numeric',
                'items.*.value_after_replacement' => 'required|numeric',
                'items.*.asset_id' => 'required|exists:assets_tb,id',
            ];
    }

    public function attributes(): array
    {
        return [
            'items' => __('Assets Data')
        ];
    }
}
