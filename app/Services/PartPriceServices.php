<?php

namespace App\Services;

use App\Models\PartPrice;
use App\Models\PartPriceSegment;
use Illuminate\Validation\Rule;

class PartPriceServices
{

    public function saveCartPrices($part, $units, $defaultUnitId)
    {
        if (isset($units)) {

            foreach ($units as $index => $unit) {

                if ($index == 1) {

                    $unit['unit_id'] = $defaultUnitId;
                }

                $unit['part_id'] = $part->id;

                if (isset($unit['default_purchase'])) {

                    $unit['default_purchase'] = 1;

                } else {

                    $unit['default_purchase'] = 0;
                }

                if (isset($unit['default_sales'])) {

                    $unit['default_sales'] = 1;

                } else {

                    $unit['default_sales'] = 0;
                }

                if (isset($unit['default_maintenance'])) {

                    $unit['default_maintenance'] = 1;

                } else {

                    $unit['default_maintenance'] = 0;
                }

                $partPrice = $this->partPrice($unit);

//              UNIT PRICES
                if (isset($unit['prices']) && $unit['prices']) {

                    foreach ($unit['prices'] as $priceSegment) {

                        $priceSegment['part_price_id'] = $partPrice->id;

                        if (isset($priceSegment['id'])) {

                            $partPriceSegment = PartPriceSegment::find($priceSegment['id']);
                            $partPriceSegment->update($priceSegment);

                        }else {

                            PartPriceSegment::create($priceSegment);
                        }
                    }
                }
            }
        }
    }

    public function partPrice($unit)
    {

        $partPrice = null;

        if (isset($unit['part_price_id'])) {

            $partPrice = PartPrice::find($unit['part_price_id']);
        }

        if ($partPrice) {

            $partPrice->update($unit);

        } else {

            $partPrice = PartPrice::create($unit);
        }

        return $partPrice;
    }

//    public function deletePartPriceSegment($partPrice, $unit)
//    {
//
//        $partPriceSegments = $partPrice->priceSegments()->pluck('price_segment_id')->toArray();
//
//        $priceSegmentsRequests = array_column($unit['prices'], 'id');
//
//        $diffIds = array_diff($partPriceSegments, $priceSegmentsRequests);
//
//        if (!empty($diffIds)) {
//
//            $partPrice->priceSegments()->detach($diffIds);
//        }
//    }

    public function defaultUnit($request)
    {

        $units = $request['units'];

        if ($request->has('default_purchase') && isset($units[$request['default_purchase']])) {

            $units[$request['default_purchase']]['default_purchase'] = 1;
        }

        if ($request->has('default_sales') && isset($units[$request['default_sales']])) {

            $units[$request['default_sales']]['default_sales'] = 1;
        }

        if ($request->has('default_maintenance') && isset($units[$request['default_maintenance']])) {

            $units[$request['default_maintenance']]['default_maintenance'] = 1;
        }

        return $units;
    }

    public function deletePart ($part) {

        foreach($part->prices as $price) {

            foreach( $price->partPriceSegments as $partPriceSegment) {

                $partPriceSegment->delete();
            }

            $price->delete();
        }

        $part->delete();
    }

    public function partRules () {

        $rules = [

//            'spare_part_unit_id' => 'required|integer|exists:spare_part_units,id',
            'suppliers_ids' => 'nullable',
            'description' => 'nullable|string',
            'part_in_store' => 'nullable|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'spare_part_type_ids'=>'required',
            'spare_part_type_ids.*'=>'required|integer|exists:spare_parts,id',
        ];

        $branch_id = auth()->user()->branch_id;

        if(authIsSuperAdmin()) {

            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = request()['branch_id'];
        }

        if (!request()->has('is_service')) {

            $rules['stores.*'] = 'integer|exists:stores,id';
        }

        $rules['name_en'] =
            [
                'required', 'max:255',
                Rule::unique('parts')->where(function ($query) use($branch_id) {
                    return $query->where('name_en', request()->name_en)
                        ->where('branch_id', $branch_id)
                        ->where('deleted_at', null);
                }),
            ];

        $rules['name_ar'] =
            [
                'required', 'max:255',
                Rule::unique('parts')->where(function ($query) use($branch_id) {
                    return $query->where('name_ar', request()->name_ar)
                        ->where('branch_id', $branch_id)
                        ->where('deleted_at', null);
                }),
            ];

        return $rules;
    }
}
