<?php

namespace App\Http\Requests\Admin\TaxCard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaxCardRequest extends FormRequest
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
            'activity'=>'required',
            'registration_number'=>'required',
            'registration_date'=>'required',
            'end_date'=>'required',
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
