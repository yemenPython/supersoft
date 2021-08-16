<?php

namespace App\Http\Requests\Admin\CommercialRegister;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommercialRegisterRequest extends FormRequest
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
            'commercial_registry_office'=>'required',
            'national_number'=>'required',
            'deposit_number'=>'required',
            'deposit_date'=>'required',
            'valid_until'=>'required',
            'commercial_feature'=>'required',
            'company_type'=>'required',
            'purpose'=>'required',
            'no_of_years'=>'required',
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
