<?php

namespace App\Http\Requests\Admin\Currency;

use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CurrencyRequest extends FormRequest
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
        $setting = Setting::first();
        $id = request()->segment(5) ?? request()->segment(4);
        $getIgnoredID = Currency::find($id);
        if ($getIgnoredID && $getIgnoredID->id) {
            $ruleAR = ['required','string','max:50', Rule::unique('currencies', 'name_ar')->ignore($id).',deleted_at,NULL'];
            $ruleEN = ['required','string','max:50', Rule::unique('currencies', 'name_en')->ignore($id).',deleted_at,NULL'];
        }
        else {
            $ruleAR = 'required|string|max:50|unique:currencies,name_ar,NULL,id,deleted_at,NULL';
            $ruleEN = 'required|string|max:50|unique:currencies,name_en,NULL,id,deleted_at,NULL';
        }

        $rules1 =  [
            'name_ar' => $ruleAR,
            'name_en' => $ruleEN,
            'symbol_ar' => 'required|string|max:50',
            'symbol_en' => 'required|string|max:50',
            'status' => 'required|in:1,0',
        ];
        if ($setting->active_multi_currency) {
            $rules2 = [
                'is_main_currency' => 'required|in:1,0',
                'conversion_factor' => 'nullable|numeric',
            ];
           return array_merge($rules1, $rules2);
        }
        return $rules1;
    }

    public function attributes()
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'symbol_ar' => __('Symbol in Arabic'),
            'symbol_en' => __('Symbol in English'),
            'conversion_factor' => __('Conversion Factor'),
            'status' => __('Status'),
            'is_main_currency' => __('Is Main Currency'),
        ];
    }
}
