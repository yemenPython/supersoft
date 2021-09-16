<?php

namespace App\Http\Requests\Admin\Asset;

use App\Models\AssetGroup;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssetGroupRequest extends FormRequest
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

    public function rules()
    {
        $id = request()->segment(5) ?? request()->segment(4);
        $getIgnoredID = AssetGroup::find($id);
        if ($getIgnoredID && $getIgnoredID->id) {
            $ruleAR = ['required','string','max:50', Rule::unique('assets_groups', 'name_ar')->ignore($id).',deleted_at,NULL'];
            $ruleEN = ['required','string','max:50', Rule::unique('assets_groups', 'name_en')->ignore($id).',deleted_at,NULL'];
            $rateInp = 'nullable|required_if:consumption_type,manual|numeric|min:0|max:100';
            $branch_id = 'required|numeric|exists:branches,id';
            $consumption_type = 'required';
            $age_years = 'nullable|required_if:consumption_type,automatic|numeric|min:1';
            $consumption_period = 'nullable|required_if:consumption_type,automatic|numeric|min:1';
            $age_months = 'nullable|numeric|min:1';
            $consumption_for = 'nullable|required_if:consumption_type,automatic';
        }
        else {
            $ruleAR = 'required|string|max:50|unique:assets_groups,name_ar,NULL,id,deleted_at,NULL';
            $ruleEN = 'required|string|max:50|unique:assets_groups,name_en,NULL,id,deleted_at,NULL';
            $rateInp = 'nullable|required_if:consumption_type,manual|numeric|min:0|max:100';
            $branch_id = 'required|numeric|exists:branches,id';
            $consumption_type = 'required';
            $age_years = 'nullable|required_if:consumption_type,automatic|numeric|min:1';
            $consumption_for = 'nullable|required_if:consumption_type,automatic';
            $consumption_period = 'nullable|required_if:consumption_type,automatic|numeric|min:1';
            $age_months = 'nullable|numeric|min:1';
        }
        return [
            'name_ar' => $ruleAR,
            'name_en' => $ruleEN,
            'annual_consumtion_rate' => $rateInp,
            'branch_id' => $branch_id,
            'consumption_type'=>$consumption_type,
            'age_years'=>$age_years,
            'consumption_period'=>$consumption_period,
            'age_months'=>$age_months,
            'consumption_for'=>$consumption_for,
        ];
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'annual_consumtion_rate' => __('Annual Consumption Rate'),
            'name_en' => __('Name in English'),
            'branch_id' => __('branch id'),
            'consumption_type' => __('Consumption Type'),
            'consumption_for' => __('Consumption For'),
        ];
    }
}
