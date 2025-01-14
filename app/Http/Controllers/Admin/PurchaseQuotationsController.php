<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PurchaseQuotation\CreateRequest;
use App\Http\Requests\Admin\PurchaseQuotation\UpdateRequest;
use App\Models\Branch;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\PurchaseQuotation;
use App\Models\PurchaseQuotationItem;
use App\Models\PurchaseRequest;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplyTerm;
use App\Models\TaxesFees;
use App\Services\PurchaseQuotationServices;
use App\Traits\SubTypesServices;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PurchaseQuotationsController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $purchaseQuotationServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->purchaseQuotationServices = new PurchaseQuotationServices();
    }

    public function index (Request $request)
    {
        $data = PurchaseQuotation::query()->latest();

        if ($request->filled('filter')) {
            $data = $this->filter($request, $data);
        }

        $paymentTerms = SupplyTerm::where('for_purchase_quotation', 1)->where('status', 1)->where('type', 'payment')
            ->select('id', 'term_' . $this->lang)->get();

        $supplyTerms = SupplyTerm::where('for_purchase_quotation', 1)->where('status', 1)->where('type', 'supply')
            ->select('id', 'term_' . $this->lang)->get();


        if ($request->isDataTable) {
            return $this->dataTableColumns($data);
        } else {
            return view('admin.purchase_quotations.index', [
                'data' => $data,
                'paymentTerms' => $paymentTerms,
                'supplyTerms' => $supplyTerms,
                'js_columns' => PurchaseQuotation::getJsDataTablesColumns(),
            ]);
        }
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

        $purchaseRequests = PurchaseRequest::where('status','accept_approval')
            ->where('branch_id', $branch_id)
            ->select('id','number')
            ->get();

        $suppliers = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id','name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $taxes = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.purchase_quotations.create',
            compact('branches', 'mainTypes', 'subTypes','additionalPayments',
                'parts', 'purchaseRequests', 'suppliers', 'taxes'));
    }

    public function store(CreateRequest $request) {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $purchaseQuotationData =  $this->purchaseQuotationServices->PurchaseQuotationData($data);

            $purchaseQuotationData['user_id'] = auth()->id();
            $purchaseQuotationData['branch_id'] = authIsSuperAdmin() ? $data['branch_id']  : auth()->user()->branch_id;

            $purchaseQuotation = PurchaseQuotation::create($purchaseQuotationData);

            $this->purchaseQuotationServices->purchaseQuotationTaxes($purchaseQuotation, $data);

            if (isset($data['terms'])) {
                $purchaseQuotation->terms()->attach($data['terms']);
            }

            foreach ($data['items'] as $item) {

                $itemData = $this->purchaseQuotationServices->PurchaseQuotationItemData($item);
                $itemData['purchase_quotation_id'] = $purchaseQuotation->id;
                $purchaseQuotationItem = PurchaseQuotationItem::create($itemData);

                if (isset($item['taxes'])) {
                    $purchaseQuotationItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-quotations.index'))->with(['message'=> __('purchase.quotations.created.successfully'), 'alert-type'=>'success']);
    }

    public function edit (PurchaseQuotation $purchaseQuotation) {

        $branch_id = $purchaseQuotation->branch_id;

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

        $purchaseRequests = PurchaseRequest::where('status','accept_approval')
            ->where('branch_id', $branch_id)
            ->select('id','number')
            ->get();

        $suppliers = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id','name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $taxes = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.purchase_quotations.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts', 'purchaseQuotation',
                'purchaseRequests', 'suppliers', 'taxes', 'additionalPayments'));
    }

    public function update (UpdateRequest $request, PurchaseQuotation $purchaseQuotation) {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $this->purchaseQuotationServices->resetPurchaseQuotationItems($purchaseQuotation);

            $data = $request->all();

            $purchaseQuotationData =  $this->purchaseQuotationServices->PurchaseQuotationData($data);

            $purchaseQuotation->update($purchaseQuotationData);

            $this->purchaseQuotationServices->purchaseQuotationTaxes($purchaseQuotation, $data);

            if (isset($data['terms'])) {
                $purchaseQuotation->terms()->attach($data['terms']);
            }

            foreach ($data['items'] as $item) {

                $itemData = $this->purchaseQuotationServices->PurchaseQuotationItemData($item);
                $itemData['purchase_quotation_id'] = $purchaseQuotation->id;
                $purchaseQuotationItem = PurchaseQuotationItem::create($itemData);

                if (isset($item['taxes'])) {
                    $purchaseQuotationItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-quotations.index'))->with(['message'=> __('purchase.quotations.updated.successfully'), 'alert-type'=>'success']);
    }

    public function destroy (PurchaseQuotation $purchaseQuotation) {

        try {

            $purchaseQuotation->delete();

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('purchase.quotations.deleted.successfully'), 'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!isset($request->ids)) {
            return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
        }
        try {
            $purchaseQuotations = PurchaseQuotation::whereIn('id', array_unique($request->ids))->get();
            foreach ($purchaseQuotations as $purchaseQuotation) {
                $purchaseQuotation->delete();
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
        return redirect(route('admin:purchase-quotations.index'))->with(['message' => __('words.purchase-quotations-deleted'), 'alert-type' => 'success']);
    }

    public function selectPurchaseRequest (Request $request) {

        $rules = [
            'purchase_request_id' => 'required|integer|exists:purchase_requests,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseRequest = PurchaseRequest::with('items')->find($request['purchase_request_id']);

            $view = view('admin.purchase_quotations.purchase_request_items', compact('purchaseRequest'))->render();

            $partTypesViews = [];

            $index = 0;

            foreach($purchaseRequest->items as $item) {

                foreach ($item->spareParts as $itemType) {

                    $index +=1;

                    $selectedTypes = $item->spareParts->pluck('id')->toArray();

                    $part = $item->part;

                    $partMainTypes = $part->spareParts()->where('status', 1)->whereNull('spare_part_id')->orderBy('id', 'ASC')->get();

                    $partTypes = $this->purchaseQuotationServices->getPartTypes($partMainTypes, $part->id);

                    $partTypesViews[$index] = view('admin.purchase_quotations.part_types',
                        compact( 'part','index', 'partTypes', 'selectedTypes'))->render();
                }
            }

            return response()->json(['view' => $view,  'index' => $index, 'partTypesViews'=>$partTypesViews], 200);

        } catch (\Exception $e) {

//            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }

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

            $view = view('admin.purchase_quotations.part_raw', compact('part', 'index'))->render();

            $partMainTypes = $part->spareParts()->where('status', 1)->whereNull('spare_part_id')->orderBy('id', 'ASC')->get();

            $partTypes = $this->purchaseQuotationServices->getPartTypes($partMainTypes, $part->id);

            $partTypesView = view('admin.purchase_quotations.part_types', compact( 'part','index', 'partTypes'))->render();

            return response()->json(['parts'=> $view, 'partTypesView'=> $partTypesView, 'index'=> $index], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function print(Request $request)
    {
        // is first page
        $purchaseQuotation = PurchaseQuotation::findOrFail($request['purchase_quotation_id']);
        // header 6 row
        // items 21 row
        // 3 row
        // 30
        // taxes rows
        // is last page

        // show footer
        //
        $view = view('admin.purchase_quotations.print', compact('purchaseQuotation'))->render();

        return response()->json(['view' => $view]);
    }

    public function terms (Request $request) {

        $this->validate($request, [
            'purchase_quotation_id'=>'required|integer|exists:purchase_quotations,id'
        ]);

        try {

            $purchaseQuotation = PurchaseQuotation::find($request['purchase_quotation_id']);

            $purchaseQuotation->terms()->sync($request['terms']);

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-quotations.index'))->with(['message'=> __('purchase.quotations.terms.successfully'), 'alert-type'=>'success']);
    }

    public function show (PurchaseQuotation $purchaseQuotation) {

        $branch_id = $purchaseQuotation->branch_id;

        $taxes = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('purchase_quotation', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.purchase_quotations.info.show', compact('purchaseQuotation', 'taxes', 'additionalPayments'));
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

            $view = view('admin.purchase_quotations.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view'=> $view], 200);

        }catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.purchase_quotations.datatables.options';

        return DataTables::of($items)->addIndexColumn()
            ->addColumn('branch_id', function ($item) use ($viewPath) {
                $withBranch = true;
                return view($viewPath, compact('item', 'withBranch'))->render();
            })

            ->addColumn('supplier_id', function ($item) use ($viewPath) {
                $withSupplier = true;
                return view($viewPath, compact('item', 'withSupplier'))->render();
            })

            ->addColumn('date', function ($item) use ($viewPath) {
                $withDate = true;
                return view($viewPath, compact('item', 'withDate'))->render();
            })
            ->addColumn('number', function ($item) {
                return $item->number;
            })
            ->addColumn('quotation_type', function ($item) use ($viewPath) {
                $quotation_type = true;
                return view($viewPath, compact('item', 'quotation_type'))->render();
            })
            ->addColumn('total', function ($item) use ($viewPath) {
                $total = true;
                return view($viewPath, compact('item', 'total'))->render();
            })
            ->addColumn('different_days', function ($item) use ($viewPath) {
                $different_days = true;
                return view($viewPath, compact('item', 'different_days'))->render();
            })
            ->addColumn('remaining_days', function ($item) use ($viewPath) {
                $remaining_days = true;
                return view($viewPath, compact('item', 'remaining_days'))->render();
            })
            ->addColumn('status', function ($item) use ($viewPath) {
                $withStatus = true;
                return view($viewPath, compact('item', 'withStatus'))->render();
            })
            ->addColumn('executionStatus', function ($item) use ($viewPath) {
                $executionStatus = true;
                return view($viewPath, compact('item', 'executionStatus'))->render();
            })
            ->addColumn('created_at', function ($item) {
                return $item->created_at->format('y-m-d h:i:s A');
            })
            ->addColumn('updated_at', function ($item) {
                return $item->updated_at->format('y-m-d h:i:s A');
            })
            ->addColumn('action', function ($item) use ($viewPath) {
                $withActions = true;
                return view($viewPath, compact('item', 'withActions'))->render();
            })

            ->addColumn('options', function ($item) use ($viewPath) {
                $withOptions = true;
                return view($viewPath, compact('item', 'withOptions'))->render();
            })
            ->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
    }


    private function filter(Request $request, Builder $data): Builder
    {
        return $data->where(function ($query) use ($request) {
            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('number')) {
                $query->where('id', $request->number);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('quotation_type') && $request->quotation_type != 'cash_credit') {
                $query->where('quotation_type', $request->quotation_type);
            }

            if ($request->filled('quotation_type') && $request->quotation_type == 'cash_credit') {
                $query->whereIn('quotation_type', ['cash', 'credit']);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('purchase_request_id')) {
                $query->where('purchase_request_id', $request->purchase_request_id);
            }

            if ($request->filled('date_add_from') && $request->filled('date_add_to')){
                $query->whereBetween('date', [$request->date_add_from, $request->date_add_to]);
            }

            if ($request->filled('date_from') && $request->filled('date_to')){
                $query->whereBetween('date_from', [$request->date_from, $request->date_to]);
            }

            if ($request->filled('supply_date_from') && $request->filled('supply_date_to')){
                $query->whereBetween('supply_date_from', [$request->supply_date_from, $request->supply_date_to]);
            }
        });
    }
}
