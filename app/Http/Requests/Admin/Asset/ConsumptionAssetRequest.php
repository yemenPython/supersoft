<?php


namespace App\Http\Requests\Admin\Asset;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ConsumptionAssetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function appendBranchRule(): ?array
    {
        return authIsSuperAdmin() ? ['branch_id' => 'required|exists:branches,id'] : [];
    }

    public function rules(): array
    {
        $date_from = '';
        $date_to = '';
        if (!empty( $this->request->all() )) {
            $date_from = $this->request->all()['date_from'];
            $date_to = $this->request->all()['date_to'];
        }
        return $this->appendBranchRule() + [
                'number' => 'required',
                'date' => 'required',
                'date_from' => 'required',
                'type'=>'required|in:asset,expenses,both',
                'date_to' => 'required',
                'time' => 'required',
                'items' => 'required|array',
            ];
    }

    public function attributes(): array
    {
        return [
            'items' => __( 'Assets Data' )
        ];
    }
}
