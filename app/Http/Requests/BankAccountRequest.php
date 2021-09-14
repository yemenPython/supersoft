<?php

namespace App\Http\Requests;

use App\Models\Banks\TypeBankAccount;
use Illuminate\Foundation\Http\FormRequest;

class BankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (!$this->main_type_bank_account_id) {
            return [
                'main_type_bank_account_id' => 'required|exists:type_bank_accounts,id',
            ];
        }
        $mainTypeBankAccount = TypeBankAccount::find($this->main_type_bank_account_id);
        if ($mainTypeBankAccount->name == 'حسابات جارية') {
            $mainTypeBankAccountId = $mainTypeBankAccount->id;
            $sunTypeAccountRule = 'required_if:main_type_bank_account_id,==,'.$mainTypeBankAccountId;
        } else {
            $sunTypeAccountRule = 'nullable';
        }
        $mainRules = [
            'sub_type_bank_account_id' => $sunTypeAccountRule,
            'branch_id' => 'required|exists:branches,id',
            'bank_data_id' => 'required|exists:bank_data,id',
            'branch_product_id' => 'nullable|exists:branch_products,id',
            'currency_id' => 'nullable|exists:currencies,id',
            'interest_rate' => 'nullable',
            'main_type_bank_account_id' => 'required|exists:type_bank_accounts,id',
        ];

        $rules1 = [
            'bank_account_child_id' => 'nullable|exists:banks_accounts,id',
            'type' => 'required|in:deposit_for,savings_certificate',
            'yield_rate_type' => 'required|in:fixed,not_fixed',
            'deposit_number' => 'nullable',
            'deposit_term' => 'nullable',
            'periodicity_return_disbursement' => 'nullable',
            'Last_renewal_date' => 'nullable',
            'deposit_opening_date' => 'nullable',
            'auto_renewal' => 'in:1,0',
        ];

        $rules2 = [
            'sub_type_bank_account_id' => 'nullable|exists:type_bank_accounts,id',
            'account_number' => 'nullable',
            'account_name' => 'nullable',
            'iban' => 'nullable',
            'customer_number' => 'nullable',
            'account_open_date' => 'nullable',
            'with_returning' => 'in:1,0',
            'status' => 'in:1,0',
            'check_books' => 'in:1,0',
            'overdraft' => 'in:1,0',
        ];

        if ($this->main_type_bank_account_id != null && $this->sub_type_bank_account_id == null) {
            $rules = $mainRules + $rules1;
        }

        if ($this->main_type_bank_account_id != null && $this->sub_type_bank_account_id != null) {
            $rules = $mainRules + $rules2;
        }

        return $rules;
    }

    function attributes(): array
    {
        return [
            'branch_id' => __('Branch'),
            'bank_data_id' => __('Bank Name'),
            'branch_product_id' => __('Account Type'),
            'currency_id' => __('Currency'),
            'main_type_bank_account_id' => __('Bank Account Type'),
            'sub_type_bank_account_id' => __('Current Account Type'),
            'interest_rate' => __('Interest Rate'),
            'bank_account_child_id' => __('Add interest in an account'),
            'type' => __('Type'),
            'yield_rate_type' => __('Yield rate type'),
            'deposit_number' => __('deposit number'),
            'deposit_term' => __('Deposit term'),
            'periodicity_return_disbursement' => __('Periodicity of return disbursement'),
            'Last_renewal_date' => __('Last renewal date'),
            'deposit_opening_date' => __('Deposit opening date'),
            'auto_renewal' => __('auto renewal'),
            'account_number' => __('Account Number'),
            'account_name' => __('Account Name'),
            'iban' => __('IBAN'),
            'customer_number' => __('Customer Number'),
            'account_open_date' => __('Account Open Date'),
            'with_returning' => __('With Returning'),
            'status' => __('Account Status'),
            'check_books' => __('check books'),
            'overdraft' => __('Overdraft'),
        ];
    }

    public function messages()
    {
       return [
         'sub_type_bank_account_id.required_if' =>  'نوع الحساب الجارى مطلوب اذا كان نوع الحساب البنكى حسابات جارية !'
       ];
    }
}
