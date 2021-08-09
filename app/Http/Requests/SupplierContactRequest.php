<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierContactRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'phone_1' => 'required|string',
            'name' => 'required|string|min:1|max:100',
            'phone_2' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'sometimes|email',
            'job_title' => 'max:200',
        ];
    }

    public function attributes(): array
    {
        return [
            'phone_1' => __('phone 1'),
            'phone_2' => __('phone 2'),
            'name' => __('name'),
            'address' => __('address'),
        ];
    }
}
