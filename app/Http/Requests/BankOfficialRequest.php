<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BankOfficialRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'bank_data_id' => 'required|exists:bank_data,id',
            'date_from' => 'required|string|max:50',
            'date_to' => 'nullable|max:50',
            'name_ar' => 'required|max:200|string',
            'name_en' => 'nullable|max:200|string',
            'phone1' => 'nullable|max:200|string',
            'phone2' => 'nullable|max:200|string',
            'phone3' => 'nullable|max:200|string',
            'email' => 'nullable|email|string',
            'status' => 'in:1,0',
        ];
    }

    public function attributes(): array
    {
        return [
            'phone3' => __('phone'),
            'phone2' => __('phone'),
            'phone1' => __('phone'),
            'email' => __('E-Mail'),
            'date_from' => __('start date'),
            'date_to' => __('end date'),
        ];
    }
}
