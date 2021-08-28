<?php

namespace App\Http\Requests;

use App\Models\Banks\BankData;
use Illuminate\Foundation\Http\FormRequest;

class BankDataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return BankData::$rules;
    }

    function attributes(): array
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'date' => __('Start Date With Bank'),
        ];
    }
}
