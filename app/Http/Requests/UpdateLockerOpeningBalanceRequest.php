<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLockerOpeningBalanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return  [
                'number' => 'required',
                'date' => 'required',
                'time' => 'required',
                'total' => 'required|numeric',
                'added_total' => 'required|numeric',
                'current_total' => 'required|numeric',
                'items' => 'required|array',
                'items*added_balance' => 'required|numeric',
                'items*locker_id' => 'required|exists:lockers,id',
            ];
    }

    public function attributes(): array
    {
        return [
            'items' => __('Lockers')
        ];
    }
}
