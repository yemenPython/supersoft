<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssetMaintenanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:200',
            'name_en' => 'nullable|string|max:200',
            'maintenance_type' => 'required|in:km,hour',
            'period' => 'required|numeric',
            'number_of_km_h' => 'required|numeric',
            'maintenance_detection_id' => 'required|exists:maintenance_detections,id',
            'maintenance_detection_type_id' => 'required|exists:maintenance_detection_types,id',
        ];
    }

    function attributes(): array
    {
        return [
            'name_ar' => __('Name in Arabic'),
            'name_en' => __('Name in English'),
            'maintenance_type' => __('Maintenance Type'),
            'period' => __('Maintenance Period (by month)'),
            'number_of_km_h' => __('Number Of KM or Hour'),
            'maintenance_detection_id' => __('Maintenance Detection'),
            'maintenance_detection_type_id' => __('Maintenance Types'),
            'Status' => __('Status'),
        ];
    }
}
