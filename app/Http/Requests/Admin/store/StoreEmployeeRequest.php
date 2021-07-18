<?php

namespace App\Http\Requests\Admin\store;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        $id = request()->segment(5) ?? request()->segment(4);
        return [
            'employee_id' => 'required|exists:employee_data,id',
            'start_date' => 'required|string|max:50',
            'end_date' => 'nullable|max:50',
            'store_id' => 'required|numeric|exists:stores,id',
            'asset_employee_id' => '',

        ];
    }

    public function attributes(): array
    {
        return [
            'name' => __('name'),
            'phone' => __('phone'),
            'start_date' => __('start date'),
            'end_date' => __('end date'),
            'store_id' => __('Store'),
        ];
    }
}
