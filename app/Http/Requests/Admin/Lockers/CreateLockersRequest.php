<?php

namespace App\Http\Requests\Admin\Lockers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateLockersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'balance' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch = request()->branch_id;
        } else {
            $branch = auth()->user()->branch_id;
        }

        $rules['name_en'] =
            [
                'nullable', 'string', 'max:150',
            ];

        $rules['name_ar'] =
            [
                'required', 'string', 'max:150',
                Rule::unique('lockers')->where(function ($query) use ($branch) {
                    return $query->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch)
                        ->where('deleted_at', null);
                }),
            ];
        return $rules;
    }
}
