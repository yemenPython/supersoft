<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class SupplierBankAccountRequest extends FormRequest
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
            "bank_name" => "required",
            "account_number" => "required",
            "account_name" => "required",
        ];
    }

    public function attributes(): array
    {
        return [
            'bank_name' => __('Bank Name'),
            'account_number' => __('Account Number'),
            'account_name' => __('Account Name'),
        ];
    }
}
