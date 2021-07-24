<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SaleQuotation\CreateRequest;
use App\Http\Requests\Admin\SaleQuotation\UpdateRequest;
use App\Models\Branch;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\PurchaseQuotation;
use App\Models\SaleQuotation;
use App\Models\SaleQuotationItem;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplyTerm;
use App\Models\TaxesFees;
use App\Services\SaleQuotationServices;
use App\Traits\SubTypesServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleQuotationController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $saleQuotationServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->saleQuotationServices = new SaleQuotationServices();
    }

    public function index (Request $request) {

        $data = SaleQuotation::get();

        $paymentTerms = SupplyTerm::where('sale_quotation', 1)->where('status', 1)->where('type', 'payment')
            ->select('id', 'term_' . $this->lang)->get();

        $supplyTerms = SupplyTerm::where('sale_quotation', 1)->where('status', 1)->where('type', 'supply')
            ->select('id', 'term_' . $this->lang)->get();

        return view('admin.sale_quotations.index', compact('data', 'supplyTerms', 'paymentTerms'));
    }

    public function create (Request $request) {

        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $mainTypes = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $suppliers = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id','name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $taxes = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.sale_quotations.create',
            compact('branches', 'mainTypes', 'subTypes','additionalPayments', 'parts', 'suppliers', 'taxes'));
    }

    public function store(CreateRequest $request) {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $saleQuotationData =  $this->saleQuotationServices->saleQuotationData($data);

            $saleQuotationData['user_id'] = auth()->id();
            $saleQuotationData['branch_id'] = authIsSuperAdmin() ? $data['branch_id']  : auth()->user()->branch_id;

            $saleQuotation = SaleQuotation::create($saleQuotationData);

            $this->saleQuotationServices->saleQuotationTaxes($saleQuotation, $data);

            foreach ($data['items'] as $item) {

                $itemData = $this->saleQuotationServices->saleQuotationItemData($item);
                $itemData['sale_quotation_id'] = $saleQuotation->id;
                $saleQuotationItem = SaleQuotationItem::create($itemData);

                if (isset($item['taxes'])) {
                    $saleQuotationItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:sale-quotations.index'))->with(['message'=> __('sale.quotations.created.successfully'), 'alert-type'=>'success']);
    }

    public function edit (SaleQuotation $saleQuotation) {

        $branch_id = $saleQuotation->branch_id;

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $mainTypes = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $suppliers = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id','name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $taxes = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.sale_quotations.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts', 'saleQuotation', 'suppliers', 'taxes',
                'additionalPayments'));
    }

    public function update (UpdateRequest $request, SaleQuotation $saleQuotation) {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $this->saleQuotationServices->resetsaleQuotationItems($saleQuotation);

            $data = $request->all();

            $saleQuotationData =  $this->saleQuotationServices->saleQuotationData($data);

            $saleQuotation->update($saleQuotationData);

            $this->saleQuotationServices->saleQuotationTaxes($saleQuotation, $data);

            foreach ($data['items'] as $item) {

                $itemData = $this->saleQuotationServices->saleQuotationItemData($item);
                $itemData['sale_quotation_id'] = $saleQuotation->id;
                $saleQuotationItem = SaleQuotationItem::create($itemData);

                if (isset($item['taxes'])) {
                    $saleQuotationItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:sale-quotations.index'))->with(['message'=> __('sale.quotations.created.successfully'), 'alert-type'=>'success']);
    }

    public function destroy (SaleQuotation $saleQuotation) {

        try {

            $saleQuotation->delete();

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('sale.quotations.deleted.successfully'), 'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!isset($request->ids)) {
            return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
        }

        try {

            $ids = array_unique($request->ids);

            $saleQuotations = SaleQuotation::whereIn('id', $ids)->get();

            foreach ($saleQuotations as $saleQuotation) {
                $saleQuotation->delete();
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
        return redirect(route('admin:sale-quotations.index'))->with(['message' => __('words.sale-quotations-deleted'), 'alert-type' => 'success']);
    }

    public function selectPartRaw(Request $request)
    {
        $rules = [
            'part_id'=>'required|integer|exists:parts,id',
            'index'=>'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'] + 1;

            $part = Part::find($request['part_id']);

            $view = view('admin.sale_quotations.part_raw', compact('part', 'index'))->render();

//            $partMainTypes = $part->spareParts()->where('status', 1)->whereNull('spare_part_id')->orderBy('id', 'ASC')->get();

//            $partTypes = $this->purchaseQuotationServices->getPartTypes($partMainTypes, $part->id);

//            $partTypesView = view('admin.purchase_quotations.part_types', compact( 'part','index', 'partTypes'))->render();

            return response()->json(['parts'=> $view, 'index'=> $index], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function print(Request $request)
    {
        $saleQuotation = SaleQuotation::findOrFail($request['sale_quotation_id']);

        $view = view('admin.sale_quotations.print', compact('saleQuotation'))->render();

        return response()->json(['view' => $view]);
    }

    public function terms (Request $request) {

        $this->validate($request, [
            'sale_quotation_id'=>'required|integer|exists:sale_quotations,id',
            'terms'=>'required',
            'terms.*'=>'required|integer|exists:supply_terms,id',
        ]);

        try {

            $saleQuotation = SaleQuotation::find($request['sale_quotation_id']);

            $saleQuotation->terms()->sync($request['terms']);

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:sale-quotations.index'))->with(['message'=> __('sale.quotations.terms.successfully'), 'alert-type'=>'success']);
    }

    public function priceSegments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price_id'=>'required|integer|exists:part_prices,id',
            'index'=>'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'];

            $priceSegments = PartPriceSegment::where('part_price_id', $request['price_id'])->get();

            $view = view('admin.sale_quotations.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view'=> $view], 200);

        }catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }
}
