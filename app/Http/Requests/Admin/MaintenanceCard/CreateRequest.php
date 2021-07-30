<?php

namespace App\Http\Requests\Admin\MaintenanceCard;

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

            'asset_id'=>'required|integer|exists:assets_tb,id',
            'type'=>'required|string|in:internal,external',
            'receive_date' => 'required|date',
            'delivery_date' => 'required|date',
            'receive_time' => 'required',
            'delivery_time' => 'required',
            'note' => 'nullable|string|max:1000',
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        return $rules;
    }
}
