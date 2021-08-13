<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMaintenanceCenterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
        ];
    }
}
