<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpeningBalanceAccountRequest extends FormRequest
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
                'items' => 'required|array',
                'items*added_balance' => 'required|numeric',
                'items*bank_account_id' => 'required|exists:banks_accounts,id',
            ];
    }

    public function attributes(): array
    {
        return [
            'items' => __('Accounts')
        ];
    }
}
