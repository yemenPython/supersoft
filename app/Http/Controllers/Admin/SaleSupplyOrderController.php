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
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use App\Models\SupplyTerm;
use App\Models\TaxesFees;
use App\Services\SaleSupplyOrderService;
use App\Services\SupplyOrderServices;
use App\Traits\SubTypesServices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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

    public function index()
    {
        $data['sale_supply_orders'] = SaleSupplyOrder::get();

        $data['paymentTerms'] = SupplyTerm::where('supply_order', 1)->where('status', 1)->where('type', 'payment')
            ->select('id', 'term_' . $this->lang)->get();

        $data['supplyTerms'] = SupplyTerm::where('supply_order', 1)->where('status', 1)->where('type', 'supply')
            ->select('id', 'term_' . $this->lang)->get();

        return view('admin.sale_supply_orders.index', compact('data'));
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

        $data['customers'] = Customer::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'customer_category_id')
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

        $data['saleQuotations'] = SaleQuotation::where('branch_id', $branch_id)
            ->select('id', 'number')
            ->get();

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

            $supplyOrder = SaleSupplyOrder::create($supplyOrderData);

            $this->saleSupplyOrderServices->supplyOrderTaxes($supplyOrder, $data);

            if (isset($data['purchase_quotations'])) {
                $supplyOrder->purchaseQuotations()->attach($data['purchase_quotations']);
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

                if (!empty($customers) && !in_array($saleQuotation->customer_id, $customers)) {
                    return response()->json(__('sorry, supplier is different'), 400);
                }

                $customers[] = $saleQuotation->customer_id;
                $itemsCount += $saleQuotation->items()->count();
            }

            $customerId = isset($customers[0]) ? $customers[0] : null;

            $view = view('admin.sale_supply_orders.sale_quotation_items',
                compact('saleQuotations'))->render();

            return response()->json(['view' => $view, 'index' => $itemsCount, 'customerId' => $customerId], 200);

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

            $view = view('admin.sale_supply_orders.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }
}