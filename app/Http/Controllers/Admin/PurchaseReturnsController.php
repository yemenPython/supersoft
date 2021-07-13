<?php

namespace App\Http\Controllers\Admin;

use App\Model\PurchaseReturnItem;
use App\Models\Branch;
use App\Models\PurchaseReceipt;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Services\PurchaseReturnServices;
use Exception;
use App\Models\Part;
use App\Models\Store;
use App\Models\TaxesFees;
use Illuminate\Http\Request;
use App\Model\PurchaseReturn;
use App\Models\RevenueReceipt;
use App\Models\PurchaseInvoice;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Filters\PurchaseReturnInvoiceFilter;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Requests\Admin\PurchaseReturn\PurchaseReturnRequest;
use App\Http\Controllers\DataExportCore\Invoices\PurchaseReturn as InvoicesPurchaseReturn;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PurchaseReturnsController extends Controller
{
    /**
     * @var PurchaseReturnItemsController
     */
    protected $purchaseReturnItemsController;
    public $lang;
    public $purchaseReturnServices;


    /**
     * @var PurchaseReturnInvoiceFilter
     */
    protected $purchaseReturnInvoiceFilter;

    public function __construct(PurchaseReturnInvoiceFilter $purchaseReturnInvoiceFilter)
    {
        $this->lang = App::getLocale();
        $this->purchaseReturnInvoiceFilter = $purchaseReturnInvoiceFilter;
        $this->purchaseReturnServices = new PurchaseReturnServices();


//        $this->middleware('permission:view_purchase_return_invoices');
//        $this->middleware('permission:create_purchase_return_invoices', ['only' => ['create', 'store']]);
//        $this->middleware('permission:update_purchase_return_invoices', ['only' => ['edit', 'update']]);
//        $this->middleware('permission:delete_purchase_return_invoices', ['only' => ['destroy', 'deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $invoices = PurchaseReturn::query();
        if ($request->hasAny((new PurchaseReturn())->getFillable())) {
            $invoices = $this->purchaseReturnInvoiceFilter->filter($request);
        }
        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;

            $sort_method = $request->has('sort_method') ? $request->sort_method : 'asc';

            if (!in_array($sort_method, ['asc', 'desc'])) $sort_method = 'desc';

            $sort_fields = [
                'invoice-number' => 'invoice_number',
//                'supplier' => 'supplier_id',
                'invoice-type' => 'type',
//                'payment' => 'remaining',
//                'paid' => 'paid',
//                'remaining' => 'remaining',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at',
                'total' => 'total'
            ];

            if (isset($sort_fields[$sort_by])) {
                $invoices = $invoices->orderBy($sort_fields[$sort_by], $sort_method);
            }

        } else {
            $invoices = $invoices->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $invoices->where(function ($q) use ($key) {
                $q->where('invoice_number', 'like', "%$key%");
//                    ->orWhere('remaining', 'like', "%$key%")
//                    ->orWhere('paid', 'like', "%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new InvoicesPurchaseReturn($invoices, $visible_columns), $request->invoker))();
        }

        $rows = $request->has('rows') ? $request->rows : 10;

        $invoices = $invoices->paginate($rows)->appends(request()->query());

        return view('admin.purchase_returns.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        if (!auth()->user()->can('create_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();

        $data['taxes'] = TaxesFees::where('active_purchase_invoice', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('active_purchase_invoice', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['supplyOrders'] = SupplyOrder::where('branch_id', $branch_id)
            ->whereHas('purchaseReceipts', function ($q) {
                $q->whereHas('concession', function ($q) {
                    $q->where('status', 'accepted');
                });

            })->select('id', 'number', 'supplier_id')
            ->get();

        $data['purchaseInvoices'] = PurchaseInvoice::where('branch_id', $branch_id)
            ->where('invoice_type', 'normal')
            ->doesntHave('invoiceReturn')
            ->where('status', 'accept')
            ->select('id', 'invoice_number', 'supplier_id')->get();

        return view('admin.purchase_returns.create', compact('data'));
    }

    //PurchaseReturnRequest

    public function store(PurchaseReturnRequest $request)
    {
        if (!auth()->user()->can('create_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {

            $data = $request->all();

            $checkData = $this->purchaseReturnServices->checkItemsQuantity($data);

            if (!isset($checkData['status']) || (isset($checkData['status']) && !$checkData['status'])) {

                $message = isset($checkData['message']) ? $checkData['message'] : __('sorry please try later');
                return redirect()->back()->with(['message' => $message, 'alert-type' => 'error']);
            }

            $purchaseReturnData = $this->purchaseReturnServices->PurchaseReturnData($data);

            $purchaseReturnData['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            DB::beginTransaction();

            $purchaseReturn = PurchaseReturn::create($purchaseReturnData);

            $this->purchaseReturnServices->purchaseReturnTaxes($purchaseReturn, $data);

            if ($data['invoice_type'] == 'from_supply_order') {

                $purchaseReturn->supplyOrders()->attach($data['supply_order_ids']);
                $purchaseReturn->purchaseReceipts()->attach($data['purchase_receipts']);
            }

            foreach ($data['items'] as $item) {

                $item_data = $this->purchaseReturnServices->ItemData($item, $data['invoice_type']);

                $item_data['purchase_returns_id'] = $purchaseReturn->id;

                $purchaseReturnItem = PurchaseReturnItem::create($item_data);

                if (isset($item['taxes'])) {
                    $purchaseReturnItem->taxes()->attach($item['taxes']);
                }

                if ($purchaseReturn->status == 'finished' && $purchaseReturn->invoice_type = 'normal') {

                    $checkItemQuantityData = $this->purchaseReturnServices->affectedPart($purchaseReturnItem);

                    if (!isset($checkItemQuantityData['status']) || (isset($checkItemQuantityData['status']) && !$checkItemQuantityData['status'])) {

                        $message = isset($checkItemQuantityData['message']) ? $checkItemQuantityData['message'] : __('sorry please try later');
                        return redirect()->back()->with(['message' => $message, 'alert-type' => 'error']);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

//            dd($e->getMessage());

            return redirect()->back()
                ->with(['message' => __('words.purchase-invoice-return-cant-created'), 'alert-type' => 'error']);
        }


        return redirect()->to(route('admin:purchase_returns.index'))
            ->with(['message' => __('words.purchase-invoice-return-created'), 'alert-type' => 'success']);

        //        return redirect()->to('admin/revenueReceipts/create?purchase_return_id=' . $purchaseReturn->id)
//            ->with(['message' => __('words.purchase-invoice-return-created'), 'alert-type' => 'success']);

    }

    public function edit(PurchaseReturn $purchaseReturn)
    {
        if (!auth()->user()->can('update_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($purchaseReturn->status == 'finished' && $purchaseReturn->invoice_type == 'normal') {
            return redirect()->back()->with(['message' => __('words.purchase-invoice-return-status-finished'), 'alert-type' => 'error']);
        }

        $branch_id = $purchaseReturn->branch_id;

        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();

        $data['taxes'] = TaxesFees::where('active_purchase_invoice', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('active_purchase_invoice', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['supplyOrders'] = SupplyOrder::where('branch_id', $branch_id)
            ->whereHas('purchaseReceipts', function ($q) {
                $q->whereHas('concession', function ($q) {
                    $q->where('status', 'accepted');
                });

            })->select('id', 'number', 'supplier_id')
            ->get();

        $data['purchaseInvoices'] = PurchaseInvoice::where('branch_id', $branch_id)
            ->where('invoice_type', 'normal')
            ->whereDoesntHave('invoiceReturn', function ($q) use ($purchaseReturn) {
                $q->where('purchase_invoice_id', '!=', $purchaseReturn->purchase_invoice_id);
            })
            ->where('status', 'accept')
            ->select('id', 'invoice_number', 'supplier_id')->get();

        $data['purchaseReceipts'] = $purchaseReceipts = PurchaseReceipt::whereIn('supply_order_id', $purchaseReturn->supplyOrders->pluck('id')->toArray())
            ->whereHas('concession', function ($q) {
                $q->where('status', 'accepted');

            })->select('id', 'number', 'supplier_id')
            ->get();

        return view('admin.purchase_returns.edit', compact('purchaseReturn', 'data'));
    }

    public function update(PurchaseReturnRequest $request, PurchaseReturn $purchaseReturn)
    {
        if (!auth()->user()->can('update_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($purchaseReturn->revenueReceipt->count()) {
            return redirect()->back()->with(['message' => __('words.purchase-invoice-return-paid'), 'alert-type' => 'error']);
        }

        if ($purchaseReturn->status == 'finished' && $purchaseReturn->invoice_type == 'normal') {
            return redirect()->back()->with(['message' => __('words.purchase-invoice-return-status-finished'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->purchaseReturnServices->resetPurchaseReturnItems($purchaseReturn);

            $data = $request->all();

            $checkData = $this->purchaseReturnServices->checkItemsQuantity($data);

            if (!isset($checkData['status']) || (isset($checkData['status']) && !$checkData['status'])) {

                $message = isset($checkData['message']) ? $checkData['message'] : __('sorry please try later');
                return redirect()->back()->with(['message' => $message, 'alert-type' => 'error']);
            }

            $purchaseReturnData = $this->purchaseReturnServices->PurchaseReturnData($data);

            $purchaseReturn->update($purchaseReturnData);

            $this->purchaseReturnServices->purchaseReturnTaxes($purchaseReturn, $data);

            if ($data['invoice_type'] == 'from_supply_order') {

                $purchaseReturn->supplyOrders()->attach($data['supply_order_ids']);
                $purchaseReturn->purchaseReceipts()->attach($data['purchase_receipts']);
            }

            foreach ($data['items'] as $item) {

                $item_data = $this->purchaseReturnServices->ItemData($item, $data['invoice_type']);

                $item_data['purchase_returns_id'] = $purchaseReturn->id;

                $purchaseReturnItem = PurchaseReturnItem::create($item_data);

                if (isset($item['taxes'])) {
                    $purchaseReturnItem->taxes()->attach($item['taxes']);
                }

                if ($purchaseReturn->status == 'finished' && $purchaseReturn->invoice_type = 'normal') {

                    $checkItemQuantityData = $this->purchaseReturnServices->affectedPart($purchaseReturnItem);

                    if (!isset($checkItemQuantityData['status']) || (isset($checkItemQuantityData['status']) && !$checkItemQuantityData['status'])) {

                        $message = isset($checkItemQuantityData['message']) ? $checkItemQuantityData['message'] : __('sorry please try later');
                        return redirect()->back()->with(['message' => $message, 'alert-type' => 'error']);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

//            dd($e->getMessage());

            return redirect()->back()
                ->with(['message' => __('words.purchase-invoice-return-cant-updated'), 'alert-type' => 'error']);
        }

        return redirect()->to(route('admin:purchase_returns.index'))
            ->with(['message' => __('words.purchase-invoice-return-updated'), 'alert-type' => 'success']);
    }

    public function destroy(PurchaseReturn $purchaseReturn)
    {
        if (!auth()->user()->can('delete_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($purchaseReturn->status == 'finished' && $purchaseReturn->invoice_type == 'normal') {
            return redirect()->back()->with(['message' => __('words.purchase-invoice-return-status-finished'), 'alert-type' => 'error']);
        }

        $purchaseReturn->delete();

        return redirect()->back()
            ->with(['message' => __('words.purchase-invoice-return-deleted'), 'alert-type' => 'success']);
    }

    public function showRevenues(int $id)
    {
        $invoice = PurchaseReturn::find($id);
        $revenues = RevenueReceipt::where('purchase_return_id', $invoice->id)->get();
        $revenuesSum = RevenueReceipt::where('purchase_return_id', $invoice->id)->sum('cost');
        $remaining = $invoice->total_after_discount - $revenuesSum;
        return view('admin.purchase_returns.parts.revenues', compact('revenues', 'invoice', 'revenuesSum', 'remaining'));
    }

    public function show(Request $request)
    {
        if (!auth()->user()->can('view_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $purchase_invoice = PurchaseReturn::find($request->invoiceID);

        $invoice = view('admin.purchase_returns.show', compact('purchase_invoice'))->render();

        return response()->json(['invoice' => $invoice]);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_purchase_return_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            $invoicesReturn = PurchaseReturn::whereIn('id', $request->ids)->get();

            foreach ($invoicesReturn as $invoice) {

                if ($invoice->status == 'finished' && $invoice->invoice_type == 'normal') {
                    continue;
                }

                $invoice->delete();
            }
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->back()
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    //  NEW VERSION
    public function getPurchaseReceipts(Request $request)
    {
        $rules = [
            'supply_order_ids' => 'required',
            'supply_order_ids.*' => 'required|integer|exists:supply_orders,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseReceipts = PurchaseReceipt::whereIn('supply_order_id', $request['supply_order_ids'])
                ->whereHas('concession', function ($q) {
                    $q->where('status', 'accepted');

                })->select('id', 'number', 'supplier_id')
                ->get();

            if (count(array_unique($purchaseReceipts->pluck('supplier_id')->toArray())) > 1) {
                return response()->json('sorry, please select supply orders for one supplier', 400);
            }

            $supplier_id = isset($purchaseReceipts->pluck('supplier_id')->toArray()[0]) ? $purchaseReceipts->pluck('supplier_id')->toArray()[0] : null;

            $supplier = Supplier::find($supplier_id);

            $view = view('admin.purchase_returns.purchase_receipts', compact('purchaseReceipts', 'supplier'))->render();

            $real_purchase_receipts = view('admin.purchase_returns.real_purchase_receipts', compact('purchaseReceipts'))->render();

            return response()->json(['view' => $view, 'real_purchase_receipts' => $real_purchase_receipts], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function addPurchaseReceipts(Request $request)
    {
        $rules = [
            'purchase_receipts' => 'required',
            'purchase_receipts.*' => 'required|integer|exists:purchase_receipts,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseReceipts = PurchaseReceipt::with('items')
                ->whereIn('id', $request['purchase_receipts'])
                ->has('concession')
                ->get();

            $itemsCount = 0;

            foreach ($purchaseReceipts as $purchaseReceipt) {
                $itemsCount += $purchaseReceipt->items()->count();
            }

            $view = view('admin.purchase_returns.purchase_receipt_items',
                compact('purchaseReceipts'))->render();

            return response()->json(['view' => $view, 'index' => $itemsCount], 200);

        } catch (\Exception $e) {

            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }
    }

    public function SelectPurchaseInvoice(Request $request)
    {
        $rules = [
            'purchase_invoice_id' => 'required|integer|exists:purchase_invoices,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $purchaseInvoice = PurchaseInvoice::find($request['purchase_invoice_id']);

            $index = $purchaseInvoice->items->count();

            $view = view('admin.purchase_returns.purchase_invoice_items', compact('purchaseInvoice'))->render();

            return response()->json(['view' => $view, 'index' => $index], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function showPartQuantity(Request $request)
    {

        $rules = [
            'part_id' => 'required|integer|exists:parts,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $part = Part::find($request['part_id']);

            $view = view('admin.purchase_returns.part_quantity', compact('part'))->render();

            return response()->json(['view' => $view,], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }

    }
}
