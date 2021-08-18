<?php

namespace App\Http\Requests\Admin\RegisterAddedValue;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterAddedValueRequest extends FormRequest
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
            'area'=>'required',
            'register_date'=>'required',
            'errands'=>'required',
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
