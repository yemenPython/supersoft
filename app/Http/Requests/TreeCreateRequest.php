<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreeCreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules(): array
    {
        return [
//            'branch_id' => 'required|exists:branches,id',
            'name_ar' => 'required|string|min:1|max:100',
            'name_en' => 'nullable|string|min:1|max:100',
            'status' => 'in:1,0',
        ];
    }

    public function attributes(): array
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'status' => __('Status'),
            'branch_id' => __('Branch'),
        ];
    }
}
