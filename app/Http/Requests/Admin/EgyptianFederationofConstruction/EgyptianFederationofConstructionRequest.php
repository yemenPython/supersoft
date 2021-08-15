<?php

namespace App\Http\Requests\Admin\EgyptianFederationofConstruction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EgyptianFederationofConstructionRequest extends FormRequest
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
//        {$this->post->id}

        $rules = [
//            'name_en'=>'required|string|max:255',
            'membership_no'=>'required',
            'date_of_register'=>'required',
            'payment_receipt'=>'required',
            'end_date'=>'required',
            'company_type'=>'required',
            //'country_id'=>'nullable|integer|exists:countries,id',
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
         'supplier_type' => __('Supplier Type')
       ];
    }

    public function messages()
    {
        return [
        ];
    }
}
