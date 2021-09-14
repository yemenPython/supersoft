<?php

namespace App\Http\Controllers\Admin;


use App\Http\Requests\Admin\SupplyOrders\CreateRequest;
use App\Http\Requests\Admin\SupplyOrders\UpdateRequest;
use App\Models\Branch;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\PurchaseQuotation;
use App\Models\PurchaseRequest;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use App\Models\SupplyTerm;
use App\Models\TaxesFees;
use App\Services\SupplyOrderServices;
use App\Traits\SubTypesServices;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SupplyOrderController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $supplyOrderServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->supplyOrderServices = new SupplyOrderServices();
    }

    public function index(Request $request)
    {
        $supply_orders = SupplyOrder::query()->latest();
        if ($request->filled('filter')) {
            $supply_orders = $this->filter($request, $supply_orders);
        }
        $data['paymentTerms'] = SupplyTerm::where('supply_order', 1)->where('status', 1)->where('type', 'payment')
            ->select('id', 'term_' . $this->lang)->get();
        $data['supplyTerms'] = SupplyTerm::where('supply_order', 1)->where('status', 1)->where('type', 'supply')
            ->select('id', 'term_' . $this->lang)->get();
        if ($request->isDataTable) {
            return $this->dataTableColumns($supply_orders);
        } else {
            return view('admin.supply_orders.index', [
                'data' => $data,
                'supply_orders' => $supply_orders,
                'js_columns' => SupplyOrder::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create(Request $request)
    {

        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['branches'] = Branch::select('id', 'name_' . $this->lang)->get();

        $data['mainTypes'] = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $data['subTypes'] = $this->getSubPartTypes($data['mainTypes']);

        $data['parts'] = Part::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $data['purchaseRequests'] = PurchaseRequest::where('status', 'accept_approval')
            ->where('branch_id', $branch_id)
            ->select('id', 'number')
            ->get();

        $data['suppliers'] = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $data['taxes'] = TaxesFees::where('supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.supply_orders.create', compact('data'));
    }

    public function store(CreateRequest $request)
    {
        
        if (!$request->has('items')) {
            return redirect()->back()->with(['message' => 'sorry, please select items', 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $supplyOrderData = $this->supplyOrderServices->supplyOrderData($data);

            $supplyOrderData['user_id'] = auth()->id();
            $supplyOrderData['branch_id'] = authIsSuperAdmin() ? $data['branch_id'] : auth()->user()->branch_id;

            $supplyOrder = SupplyOrder::create($supplyOrderData);

            $this->supplyOrderServices->supplyOrderTaxes($supplyOrder, $data);

            if (isset($data['purchase_quotations'])) {
                $supplyOrder->purchaseQuotations()->attach($data['purchase_quotations']);
            }

            foreach ($data['items'] as $item) {

                $itemData = $this->supplyOrderServices->supplyOrderItemData($item);
                $itemData['supply_order_id'] = $supplyOrder->id;
                $supplyOrderItem = SupplyOrderItem::create($itemData);

                if (isset($item['taxes'])) {
                    $supplyOrderItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:supply-orders.index'))->with(['message' => __('supply.orders.created.successfully'), 'alert-type' => 'success']);
    }

    public function edit(SupplyOrder $supplyOrder)
    {

        if ($supplyOrder->purchaseReceipts->count()) {
            return redirect()->back()->with(['message' => 'sorry, this supply order has purchase receipts', 'alert-type' => 'error']);
        }

        $branch_id = $supplyOrder->branch_id;

        $data['branches'] = Branch::select('id', 'name_' . $this->lang)->get();

        $data['mainTypes'] = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $data['subTypes'] = $this->getSubPartTypes($data['mainTypes']);

        $data['parts'] = Part::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('name_' . $this->lang, 'id')
            ->get();

        $data['purchaseRequests'] = PurchaseRequest::where('status', 'accept_approval')
            ->where('branch_id', $branch_id)
            ->select('id', 'number')
            ->get();

        $data['suppliers'] = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $data['taxes'] = TaxesFees::where('supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['purchaseQuotations'] = PurchaseQuotation::where('purchase_request_id', $supplyOrder->purchase_request_id)
            ->where(function ($q) use ($supplyOrder) {

                $q->doesntHave('supplyOrders')
                    ->orWhereHas('supplyOrders', function ($supply) use ($supplyOrder) {
                        $supply->where('supply_order_id', $supplyOrder->id);
                    });
            })
            ->where('supplier_id', $supplyOrder->supplier_id)
            ->select('id', 'number', 'supplier_id')->get();

        return view('admin.supply_orders.edit', compact('data', 'supplyOrder'));
    }

    public function update(UpdateRequest $request, SupplyOrder $supplyOrder)
    {
        if ($supplyOrder->purchaseReceipts->count()) {
            return redirect()->back()->with(['message' => 'sorry, this supply order has purchase receipts', 'alert-type' => 'error']);
        }

        if (!$request->has('items')) {
            return redirect()->back()->with(['message' => 'sorry, please select items', 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->supplyOrderServices->resetSupplyOrderDataItems($supplyOrder);

            $data = $request->all();

            $supplyOrderData = $this->supplyOrderServices->supplyOrderData($data);

            $supplyOrder->update($supplyOrderData);

            $this->supplyOrderServices->supplyOrderTaxes($supplyOrder, $data);

            if (isset($data['purchase_quotations'])) {
                $supplyOrder->purchaseQuotations()->attach($data['purchase_quotations']);
            }

            foreach ($data['items'] as $item) {

                $itemData = $this->supplyOrderServices->supplyOrderItemData($item);
                $itemData['supply_order_id'] = $supplyOrder->id;
                $supplyOrderItem = SupplyOrderItem::create($itemData);

                if (isset($item['taxes'])) {
                    $supplyOrderItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:supply-orders.index'))->with(['message' => __('supply.orders.created.successfully'), 'alert-type' => 'success']);
    }

    public function destroy(SupplyOrder $supplyOrder)
    {

        if ($supplyOrder->purchaseReceipts->count()) {
            return redirect()->back()->with(['message' => 'sorry, this supply order has purchase receipts', 'alert-type' => 'error']);
        }

        try {

            $supplyOrder->delete();

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('supply.orders.deleted.successfully'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (!isset($request->ids)) {
            return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
        }
        try {
            $supplyOrders = SupplyOrder::whereIn('id', array_unique($request->ids))->get();
            foreach ($supplyOrders as $supplyOrder) {
                if ($supplyOrder->purchaseReceipts->count()) {
                    return redirect()->back()->with(['message' => 'sorry, this supply order has purchase receipts', 'alert-type' => 'error']);
                }
                $supplyOrder->delete();
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
        return redirect()->back()->with(['message' => __('supply.orders.deleted.successfully'), 'alert-type' => 'success']);
    }

    public function getPurchaseQuotations(Request $request)
    {

        $rules = [
            'purchase_request_id' => 'required|integer|exists:purchase_requests,id',
            'supplier_id' => 'nullable|integer|exists:suppliers,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseQuotations = PurchaseQuotation::where('purchase_request_id', $request['purchase_request_id'])
                ->doesntHave('supplyOrders');

            if ($request->has('supplier_id')) {
                $purchaseQuotations->where('supplier_id', $request['supplier_id']);
            }

            $purchaseQuotations = $purchaseQuotations->select('id', 'number', 'supplier_id')->get();

            $view = view('admin.supply_orders.purchase_quotations', compact('purchaseQuotations'))->render();
            $real_purchase_quotations = view('admin.supply_orders.real_purchase_quotations', compact('purchaseQuotations'))->render();

            return response()->json(['view' => $view, 'real_purchase_quotations' => $real_purchase_quotations], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function addPurchaseQuotations(Request $request)
    {

        $rules = [
            'purchase_quotations' => 'required',
            'purchase_quotations.*' => 'required|integer|exists:purchase_quotations,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseQuotations = PurchaseQuotation::with('items')
                ->whereIn('id', $request['purchase_quotations'])
                ->get();


            $suppliers = [];
            $itemsCount = 0;

            foreach ($purchaseQuotations as $purchaseQuotation) {

                if (!empty($suppliers) && !in_array($purchaseQuotation->supplier_id, $suppliers)) {
                    return response()->json(__('sorry, supplier is different'), 400);
                }

                $suppliers[] = $purchaseQuotation->supplier_id;
                $itemsCount += $purchaseQuotation->items()->count();
            }

            $supplierId = isset($suppliers[0]) ? $suppliers[0] : null;

            $view = view('admin.supply_orders.purchase_quotation_items',
                compact('purchaseQuotations'))->render();

            return response()->json(['view' => $view, 'index' => $itemsCount, 'supplierId' => $supplierId], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function selectPartRaw(Request $request)
    {
        $rules = [
            'part_id' => 'required|integer|exists:parts,id',
            'index' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'] + 1;

            $part = Part::find($request['part_id']);

            $view = view('admin.supply_orders.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts' => $view, 'index' => $index], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function print(Request $request)
    {
        $supplyOrder = SupplyOrder::findOrFail($request['supply_order_id']);

        $view = view('admin.supply_orders.print', compact('supplyOrder'))->render();

        return response()->json(['view' => $view]);
    }

    public function terms(Request $request)
    {

        $this->validate($request, [
            'supply_order_id' => 'required|integer|exists:supply_orders,id'
        ]);

        try {

            $supplyOrder = SupplyOrder::find($request['supply_order_id']);

            $supplyOrder->terms()->sync($request['terms']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:supply-orders.index'))->with(['message' => __('supply.orders.terms.successfully'), 'alert-type' => 'success']);
    }

    public function priceSegments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'price_id' => 'required|integer|exists:part_prices,id',
            'index' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $index = $request['index'];

            $priceSegments = PartPriceSegment::where('part_price_id', $request['price_id'])->get();

            $view = view('admin.supply_orders.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function show (SupplyOrder $supplyOrder) {

        $branch_id = $supplyOrder->branch_id;

        $data['taxes'] = TaxesFees::where('supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.supply_orders.info.show', compact('supplyOrder', 'data'));
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.supply_orders.datatables.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('date', function ($item) use ($viewPath) {
                $withDate = true;
                return view($viewPath, compact('item', 'withDate'))->render();
            })
            ->addColumn('branch_id', function ($item) use ($viewPath) {
                $withBranch = true;
                return view($viewPath, compact('item', 'withBranch'))->render();
            })

            ->addColumn('supplier_id', function ($item) use ($viewPath) {
                $withSupplier = true;
                return view($viewPath, compact('item', 'withSupplier'))->render();
            })

            ->addColumn('number', function ($item) {
                return $item->number;
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
            })->addColumn('options', function ($item) use ($viewPath) {
                $withOptions = true;
                return view($viewPath, compact('item', 'withOptions'))->render();
            })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
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


            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('q_number')) {
                $query->whereHas('purchaseQuotations', function ($q) use ($request) {
                    $q->where('purchase_quotation_id', $request->q_number);
                });
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
        });
    }
}
