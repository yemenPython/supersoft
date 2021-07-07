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
//                Rule::unique( 'consumption_assets' )
//                    ->ignore( $this->consumption_asset )
//                    ->whereNull( 'deleted_at' )
//                    ->where( 'date_from', $date_from )
//                    ->where( 'date_to', $date_to )],

//                'invoice_number' => ['required', 'max:50', 'string',
//                    Rule::unique( 'purchase_assets' )
//                        ->ignore( $this->purchase_asset )
//                        ->whereNull( 'deleted_at' )
//                        ->where( 'branch_id', $branch_id )
//                ,

                'date_to' => 'required',
                'time' => 'required',
                'items' => 'required|array',
//                'items.*.date_of_work' => 'required|date|before_or_equal:date_from',
            ];
    }

    public function attributes(): array
    {
        return [
            'items' => __( 'Assets Data' )
        ];
    }
}
