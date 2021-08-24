<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class LockerOpeningBalanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function appendBranchRule(): ?array
    {
        return authIsSuperAdmin() ? ['branch_id' => 'required|exists:branches,id'] : [];
    }

    public function rules(): array
    {
        return $this->appendBranchRule() + [
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
