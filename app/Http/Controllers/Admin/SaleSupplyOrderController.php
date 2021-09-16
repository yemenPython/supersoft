<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SaleSupplyOrder\CreateRequest;
use App\Http\Requests\Admin\SaleSupplyOrder\UpdateRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\PurchaseQuotation;
use App\Models\PurchaseRequest;
use App\Models\SaleQuotation;
use App\Models\SaleSupplyOrder;
use App\Models\SaleSupplyOrderItem;
use App\Models\Settlement;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use App\Models\SupplyTerm;
use App\Models\TaxesFees;
use App\Services\SaleSupplyOrderService;
use App\Services\SupplyOrderServices;
use App\Traits\SubTypesServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\DataTables;

class SaleSupplyOrderController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $saleSupplyOrderServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->saleSupplyOrderServices = new SaleSupplyOrderService();
    }

    public function index(Request $request)
    {
        $sale_supply_orders = SaleSupplyOrder::query()->latest();

        $data['paymentTerms'] = SupplyTerm::where('sale_supply_order', 1)->where('status', 1)->where('type', 'payment')
            ->select('id', 'term_' . $this->lang)->get();

        $data['supplyTerms'] = SupplyTerm::where('sale_supply_order', 1)->where('status', 1)->where('type', 'supply')
            ->select('id', 'term_' . $this->lang)->get();

        if ($request->isDataTable) {
            return $this->dataTableColumns($sale_supply_orders);
        } else {
            return view('admin.sale_supply_orders.index', [
                'sale_supply_orders' => $sale_supply_orders,
                'data' => $data,
                'js_columns' => SaleSupplyOrder::getJsDataTablesColumns(),
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

        $data['taxes'] = TaxesFees::where('sale_supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('sale_supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['suppliers'] = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $data['customers'] = Customer::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'customer_category_id')
            ->get();

        $lastNumber = SaleSupplyOrder::where('branch_id', $branch_id)
            ->orderBy('id', 'desc')
            ->first();

        $data['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.sale_supply_orders.create', compact('data'));
    }

    public function store(CreateRequest $request)
    {
        if (!$request->has('items')) {
            return redirect()->back()->with(['message' => 'sorry, please select items', 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $supplyOrderData = $this->saleSupplyOrderServices->supplyOrderData($data);

            $supplyOrderData['user_id'] = auth()->id();
            $supplyOrderData['branch_id'] = authIsSuperAdmin() ? $data['branch_id'] : auth()->user()->branch_id;

            $lastNumber = SaleSupplyOrder::where('branch_id', $supplyOrderData['branch_id'])
                ->orderBy('id', 'desc')
                ->first();

            $supplyOrderData['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            $supplyOrder = SaleSupplyOrder::create($supplyOrderData);

            $this->saleSupplyOrderServices->supplyOrderTaxes($supplyOrder, $data);

            if (isset($data['sale_quotations'])) {
                $supplyOrder->saleQuotations()->attach($data['sale_quotations']);
            }

            foreach ($data['items'] as $item) {

                $itemData = $this->saleSupplyOrderServices->supplyOrderItemData($item);
                $itemData['sale_supply_order_id'] = $supplyOrder->id;
                $supplyOrderItem = SaleSupplyOrderItem::create($itemData);

                if (isset($item['taxes'])) {
                    $supplyOrderItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:sale-supply-orders.index'))->with(['message' => __('supply.orders.created.successfully'), 'alert-type' => 'success']);
    }

    public function edit(SaleSupplyOrder $saleSupplyOrder)
    {
        $branch_id = $saleSupplyOrder->branch_id;

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

        $data['taxes'] = TaxesFees::where('sale_supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('sale_supply_order', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['suppliers'] = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $data['customers'] = Customer::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'customer_category_id')
            ->get();

//        $data['purchaseQuotations'] = PurchaseQuotation::where('purchase_request_id', $supplyOrder->purchase_request_id)
//            ->where(function ($q) use ($supplyOrder) {
//
//                $q->doesntHave('supplyOrders')
//                    ->orWhereHas('supplyOrders', function ($supply) use ($supplyOrder) {
//                        $supply->where('supply_order_id', $supplyOrder->id);
//                    });
//            })
//            ->where('supplier_id', $supplyOrder->supplier_id)
//            ->select('id', 'number', 'supplier_id')->get();

        $data['saleQuotations'] = SaleQuotation::where('branch_id', $branch_id)
            ->where('status','finished')
            ->select('id', 'number', 'salesable_id', 'salesable_type')
            ->get();

        return view('admin.sale_supply_orders.edit', compact('data', 'saleSupplyOrder'));
    }

    public function update(UpdateRequest $request, SaleSupplyOrder $saleSupplyOrder)
    {
        if ($saleSupplyOrder->status == 'finished') {
            return redirect(route('admin:sale-supply-orders.index'))->with(['message' => 'sorry, this supply order finished', 'alert-type' => 'error']);
        }

        if (!$request->has('items')) {
            return redirect(route('admin:sale-supply-orders.index'))->with(['message' => 'sorry, please select items', 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->saleSupplyOrderServices->resetSupplyOrderDataItems($saleSupplyOrder);

            $data = $request->all();

            $supplyOrderData = $this->saleSupplyOrderServices->supplyOrderData($data);

            $saleSupplyOrder->update($supplyOrderData);

            $this->saleSupplyOrderServices->supplyOrderTaxes($saleSupplyOrder, $data);

            if (isset($data['sale_quotations'])) {
                $saleSupplyOrder->saleQuotations()->attach($data['sale_quotations']);
            }

            foreach ($data['items'] as $item) {

                $itemData = $this->saleSupplyOrderServices->supplyOrderItemData($item);
                $itemData['sale_supply_order_id'] = $saleSupplyOrder->id;
                $supplyOrderItem = SaleSupplyOrderItem::create($itemData);

                if (isset($item['taxes'])) {
                    $supplyOrderItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect(route('admin:sale-supply-orders.index'))->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:sale-supply-orders.index'))
            ->with(['message' => __('supply.orders.created.successfully'), 'alert-type' => 'success']);
    }

    public function destroy(SaleSupplyOrder $saleSupplyOrder)
    {
        if ($saleSupplyOrder->status == 'finished') {
            return redirect()->back()->with(['message' => 'sorry, this supply order finished', 'alert-type' => 'error']);
        }

        try {

            $saleSupplyOrder->delete();

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('supply.orders.deleted.successfully'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {

        if (!isset($request->ids)) {
            return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
        }

        try {
            $supplyOrders = SaleSupplyOrder::whereIn('id', array_unique($request->ids))->get();

            foreach ($supplyOrders as $supplyOrder) {

                if ($supplyOrder->status == 'finished') {
                    return redirect()->back()->with(['message' => 'sorry, this supply order finished', 'alert-type' => 'error']);
                }

                $supplyOrder->delete();
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('supply.orders.deleted.successfully'), 'alert-type' => 'success']);
    }

    public function addSaleQuotations(Request $request)
    {
        $rules = [
            'sale_quotations' => 'required',
            'sale_quotations.*' => 'required|integer|exists:sale_quotations,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $saleQuotations = SaleQuotation::with('items')
                ->whereIn('id', $request['sale_quotations'])
                ->get();

            $customers = [];
            $itemsCount = 0;

            foreach ($saleQuotations as $saleQuotation) {

                if (!empty($customers) && !in_array($saleQuotation->salesable_id, $customers)) {
                    return response()->json(__('sorry, supplier is different'), 400);
                }

                $customers[] = $saleQuotation->salesable_id;
                $itemsCount += $saleQuotation->items()->count();
            }

            $customerId = isset($customers[0]) ? $customers[0] : null;

            $view = view('admin.sale_supply_orders.sale_quotation_items',
                compact('saleQuotations'))->render();

            return response()->json(['view' => $view, 'index' => $itemsCount,
                'customerId' => $customerId, 'type_for'=> $request['type_for']], 200);

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

            $view = view('admin.sale_supply_orders.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts' => $view, 'index' => $index], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function print(Request $request)
    {
        $saleSupplyOrder = SaleSupplyOrder::findOrFail($request['sale_supply_order_id']);

        $view = view('admin.sale_supply_orders.print', compact('saleSupplyOrder'))->render();

        return response()->json(['view' => $view]);
    }

    public function terms(Request $request)
    {
        $this->validate($request, [
            'sale_supply_order_id' => 'required|integer|exists:sale_supply_orders,id'
        ]);

        try {

            $supplyOrder = SaleSupplyOrder::find($request['sale_supply_order_id']);

            $supplyOrder->terms()->sync($request['terms']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:sale-supply-orders.index'))->with(['message' => __('supply.orders.terms.successfully'), 'alert-type' => 'success']);
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

            $view = view('admin.sale_supply_orders.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function show (SaleSupplyOrder $saleSupplyOrder) {

        $branch_id = $saleSupplyOrder->branch_id;

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

        return view('admin.sale_supply_orders.info.show', compact('saleSupplyOrder', 'data'));
    }

    public function checkStock(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $invalidItems = $this->saleSupplyOrderServices->checkMaxQuantityOfItem($request['items']);

            if (!empty($invalidItems)) {

                $message = __('quantity not available for this items ') ."\n          ". '('.implode(' ,', $invalidItems).')';
                return response()->json($message, 400);
            }

        } catch (\Exception $e) {
            return response()->json(['sorry, please try later'], 400);
        }

        return response()->json(['message' => __('quantity available')], 200);
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.sale_supply_orders.datatables.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('date', function ($item) use ($viewPath) {
                $withDate = true;
                return view($viewPath, compact('item', 'withDate'))->render();
            })
            ->addColumn('branch_id', function ($item) use ($viewPath) {
                $withBranch = true;
                return view($viewPath, compact('item', 'withBranch'))->render();
            })
            ->addColumn('number', function ($item) {
                return $item->number;
            })

            ->addColumn('type_for', function ($item) {
                return $item->type_for ? $item->type_for : '---' ;
            })

            ->addColumn('salesable_id', function ($item) {
                return $item->salesable ? $item->salesable->name : '---' ;
            })
            ->addColumn('total', function ($item) use ($viewPath) {
                $withTotal = true;
                return view($viewPath, compact('item', 'withTotal'))->render();
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
            ->addColumn('statusExecution', function ($item) use ($viewPath) {
                $withStatusExecution = true;
                return view($viewPath, compact('item', 'withStatusExecution'))->render();
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

    public function getSaleQuotations (Request $request) {

        $rules = [
            'type_for' => 'required|string|in:supplier,customer',
        ];

        $rules['sale_supply_order_id'] = $request->has('sale_supply_order_id') ? 'required|integer|exists:sale_supply_orders,id':'';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $branch_id = $request['branch_id'];

            $saleSupplyOrder = null;

            $saleQuotations = SaleQuotation::where('type_for', $request['type_for'])->where('status','finished');

            if ($request->has('branch_id')) {
                $saleQuotations->where('branch_id', $branch_id);
            }

            if ($request['salesable_id']) {
                $saleQuotations->where('salesable_id', $request['salesable_id']);
            }

            if ($request->has('sale_supply_order_id')) {
                $saleSupplyOrder = SaleSupplyOrder::find($request['sale_supply_order_id']);
            }

            $saleQuotations = $saleQuotations->select('id', 'number', 'salesable_id', 'salesable_type')->get();

            $view = view('admin.sale_supply_orders.sale_quotations.index', compact('saleQuotations', 'saleSupplyOrder'))->render();

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['view' => $view], 200);
    }
}
