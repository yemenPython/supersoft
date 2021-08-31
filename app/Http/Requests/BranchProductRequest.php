<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BranchProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:200',
            'name_en' => 'nullable|string|max:200',
            'branch_id' => 'required|exists:branches,id',
        ];
    }

    function attributes(): array
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'branch_id' => __('Branch'),
        ];
    }
}
