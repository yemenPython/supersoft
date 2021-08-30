<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankCommissionerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'bank_data_id' => 'required|exists:bank_data,id',
            'employee_id' => 'required|exists:employee_data,id',
            'date_from' => 'required',
            'date_to' => 'nullable',
            'status' => 'in:1,0',
        ];
    }

    public function attributes(): array
    {
        return [
            'date_from' => __('start date'),
            'date_to' => __('end date'),
            'employee_id' => __('Employee'),
        ];
    }
}
