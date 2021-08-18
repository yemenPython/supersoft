<?php

namespace App\Http\Requests\Admin\CompanyContract;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyContractRequest extends FormRequest
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
    public function rules()
    {

        $rules = [
            'contract_date'=>'required',
            'register_date'=>'required',
            'commercial_feature'=>'required',
            'company_purpose'=>'required',
            'share_capital'=>'required',
            'partnership_duration'=>'required',
            'start_at'=>'required',
            'end_at'=>'required',
        ];

        if(authIsSuperAdmin()){
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;

        }else{

            $branch = auth()->user()->branch_id;
        }

        return $rules;
    }

    public function attributes()
    {
       return [
       ];
    }

    public function messages()
    {
        return [
        ];
    }
}
