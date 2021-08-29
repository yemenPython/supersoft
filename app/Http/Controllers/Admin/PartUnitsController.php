<?php

namespace App\Http\Controllers\Admin;

use App\Models\Part;
use App\Models\PartPrice;
use App\Models\PartPriceSegment;
use App\Models\SparePartUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PartUnitsController extends Controller
{
    public $lang;

    public function __construct()
    {
        $this->lang = App::getLocale();
    }

    public function newUnit(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'units_count' => 'required|integer|min:0'
        ]);

        if ($validator->failed()) {

            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['units_count'] + 1;

            $partUnits = SparePartUnit::whereNotIn('id', $request['selectedUnitIds'])->select('id', 'unit_' . $this->lang)->get();

            $view = view('admin.parts.units.ajax_form_new_unit',
                compact('index', 'partUnits'))->render();

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['view' => $view, 'index' => $index], 200);
    }

    public function storeUnit(Request $request)
    {
        $part = Part::find($request['unit_part_id']);

        if (!$part) {
            return response()->json(__('sorry, part not valid'), 400);
        }

        $rules = $this->rules();

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $data = $request->all();

            $unitCounts = $part->prices->count();
            $partUnits = $part->prices->pluck('unit_id')->toArray();

            if (in_array($request['unit_id'], $partUnits)) {
                return response()->json('sorry, this unit already exists', 400);
            }

            $data['part_id'] = $part->id;

            if ($unitCounts == 0) {
                $data['quantity'] = 1;
                $part->spare_part_unit_id = $request['unit_id'];
                $part->save();
            }

            DB::beginTransaction();

            $partPrice = $part->prices()->create($data);

            if (isset($data['prices'])) {

                foreach ($data['prices'] as $item) {
                    $partPrice->partPriceSegments()->create($item);
                }
            }

            $prices = $part->load('prices')->prices;

            $pricesIndexView = view('admin.parts.units.index', compact('prices'))->render();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(__('sorry, please try later'), 400);
        }

        return response()->json(['message' => __('unite saved successfully'),
            'prices_index' => $pricesIndexView,
            'unitsCount' => $prices->count(),
        ], 200);
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'part_id' => 'required|integer|exists:parts,id',
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $part = Part::find($request['part_id']);

            $price = PartPrice::where('part_id', $part->id)->where('unit_id', $part->spare_part_unit_id)->first();

            $price = $price ?? null;

            $defaultUnit = $price && $price->unit ? $price->unit->unit : '---';

            $partUnits = SparePartUnit::select('id', 'unit_' . $this->lang)->get();

            $isFirstUnit = $price ? false : true;

            $formType = 'create';

            $priceFormView = view('admin.parts.units.ajax_form_new_unit',
                compact('price', 'defaultUnit', 'partUnits', 'isFirstUnit', 'formType'))->render();

        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['prices_form' => $priceFormView, 'defaultUnit' => $defaultUnit, 'price' => $price], 200);
    }

    public function edit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:part_prices,id',
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $price = PartPrice::find($request['id']);

            $part = $price->part;

            $partUnits = SparePartUnit::select('id', 'unit_' . $this->lang)->get();

            $isFirstUnit = $price->unit_id == $part->spare_part_unit_id ? true : false;

            $defaultUnit = $part && $part->sparePartsUnit ? $part->sparePartsUnit->unit : '---';

            $priceFormView = view('admin.parts.units.ajax_form_new_unit',
                compact('price', 'defaultUnit', 'partUnits', 'isFirstUnit'))->render();

        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['prices_form' => $priceFormView], 200);
    }

    public function update(Request $request)
    {
        $rules = $this->rules();

        $rules['id'] = 'required|integer|exists:part_prices,id';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $data = $request->all();

            $price = PartPrice::find($request['id']);

            $part = $price->part;

            $unitCounts = $part->prices()->where('id','!=', $price->id)->count();

            $partUnits = $part->prices()->where('id','!=', $price->id)->pluck('unit_id')->toArray();

            if (in_array($request['unit_id'], $partUnits)) {
                return response()->json('sorry, this unit already exists', 400);
            }

            if ($unitCounts == 0) {
                $data['quantity'] = 1;
            }

            $price->update($data);

            if (isset($data['prices'])) {
                $this->updatePriceSegments($data['prices'], $request['id']);
            }

            $defaultPrice = PartPrice::where('part_id', $part->id)->where('unit_id', $part->spare_part_unit_id)->first();

            $defaultPrice = $defaultPrice ?? null;

            $defaultUnit = $defaultPrice && $defaultPrice->unit ? $price->unit->unit : '---';

            $prices = $part->load('prices')->prices;

            $pricesIndexView = view('admin.parts.units.index', compact('prices'))->render();

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }

        return response()->json([
            'prices_index' => $pricesIndexView,
            'defaultPrice'=> $defaultPrice,
            'defaultUnit'=> $defaultUnit,
            'message' => __('unite saved successfully'),
        ], 200);
    }

    public function rules()
    {
        $rules = [

            'unit_id' => 'required|integer|exists:spare_part_units,id|min:0',
            'quantity' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'less_selling_price' => 'required|numeric|min:0',
            'service_selling_price' => 'required|numeric|min:0',
            'less_service_selling_price' => 'required|numeric|min:0',
            'maximum_sale_amount' => 'required|numeric|min:0',
            'minimum_for_order' => 'required|numeric|min:0',
            'damage_price' => 'required|numeric|min:0',
            'biggest_percent_discount' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|min:2',
            'supplier_barcode' => 'nullable|string|min:2',
        ];

        return $rules;
    }

    public function updatePriceSegments ($prices, $partPriceId) {

        foreach ($prices as $item) {

            if (isset($item['id'])) {

                $priceSegment = PartPriceSegment::find($item['id']);

                if ($priceSegment) {
                    $priceSegment->update($item);
                    continue;
                }
            }

            $item['part_price_id'] = $partPriceId;

            PartPriceSegment::create($item);
        }
    }

    public function destroy(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:part_prices,id',
            'part_id' => 'required|integer|exists:parts,id',
        ]);

        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $price = PartPrice::find($request['id']);

            $price->delete();

            $prices = PartPrice::where('part_id', $request['part_id'])
                ->select('id', 'quantity', 'unit_id', 'selling_price', 'purchase_price')
                ->get();

            $pricesIndexView = view('admin.parts.units.index', compact('prices'))->render();

        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['message' => __('part unit deleted successfully'), 'prices_index' => $pricesIndexView], 200);
    }

//    public function update (Request $request) {
//
//        $validator = Validator::make($request->all(), [
//
//            'price_id' => 'required|integer|exists:part_prices,id',
//            'quantity' => 'required|integer|min:0',
//            'selling_price' => 'required|numeric|min:0',
//            'purchase_price' => 'required|numeric|min:0',
//            'less_selling_price' => 'required|numeric|min:0',
//            'service_selling_price' => 'required|numeric|min:0',
//            'less_service_selling_price' => 'required|numeric|min:0',
//            'maximum_sale_amount' => 'required|numeric|min:0',
//            'minimum_for_order' => 'required|numeric|min:0',
//            'biggest_percent_discount' => 'required|numeric|min:0',
//            'barcode' => 'nullable|string|min:2',
//        ]);
//
//        if ($validator->failed()) {
//            return response()->json($validator->errors()->first(), 400);
//        }
//
//        try {
//
//            $data = $request->only(['quantity', 'selling_price', 'purchase_price', 'less_selling_price',
//               'service_selling_price', 'less_service_selling_price', 'maximum_sale_amount',
//                'minimum_for_order', 'biggest_percent_discount', 'barcode']);
//
//            DB::table('part_prices')->where('id', $request['price_id'])->update($data);
//
//        } catch (\Exception $e) {
//
//            return response()->json($e->getMessage(), 400);
//        }
//
//        return response()->json(['message' => __('part unit updated successfully')], 200);
//    }
}
