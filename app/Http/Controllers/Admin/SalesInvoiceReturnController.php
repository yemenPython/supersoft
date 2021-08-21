<?php

namespace App\Http\Controllers\Admin;

use App\Models\Part;
use App\Models\PointRule;
use App\Models\ReturnedSaleReceipt;
use App\Models\User;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\TaxesFees;
use App\Models\SalesInvoice;
use App\Services\MailServices;
use App\Services\NotificationServices;
use App\Services\PointsServices;
use Illuminate\Http\Request;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoiceItems;
use App\Models\SalesInvoiceReturn;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\SalesInvoiceItemReturn;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use \App\Services\SalesInvoiceReturnServices;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\Invoices\SalesReturn;
use App\Http\Requests\Admin\SalesInvoicesReturn\CreateSalesInvoiceReturn;
use App\Http\Requests\Admin\SalesInvoicesReturn\CreateSalesInvoiceReturnRequest;
use App\Http\Requests\Admin\SalesInvoicesReturn\UpdateSalesInvoiceReturnRequest;

class SalesInvoiceReturnController extends Controller
{
    use  SalesInvoiceReturnServices, NotificationServices, MailServices, PointsServices;

    public $lang;

    public function __construct()
    {
//        $this->middleware('permission:view_sales_invoices_return');
//        $this->middleware('permission:create_sales_invoices_return',['only'=>['create','store']]);
//        $this->middleware('permission:update_sales_invoices_return',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_sales_invoices_return',['only'=>['destroy','deleteSelected']]);

        $this->lang = App::getLocale();

    }

    public function index(Request $request)
    {

//        if (!auth()->user()->can('view_sales_invoices_return')) {
//            return redirect()->back()->with(['authorization' => 'error']);
//        }

//        $invoices = SalesInvoiceReturn::query();

        return view('admin.sales_invoice_return.index');

//        return view('admin.sales_invoice_return.index',compact('invoices'));
    }

    public function create(Request $request)
    {

        if (!auth()->user()->can('create_sales_invoices_return')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();

        $data['taxes'] = TaxesFees::where('purchase_return', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('purchase_return', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.sales_invoice_return.create', compact('data'));
    }

    public function salesInvoiceData(Request $request)
    {

        $salesInvoice = SalesInvoice::findOrFail($request['sales_invoice_id']);

        $view = view('admin.sales_invoice_return.sales_invoice', compact('salesInvoice'))->render();

        return response()->json(['view' => $view], 200);
    }

    public function store(CreateSalesInvoiceReturnRequest $request)
    {

        if (!auth()->user()->can('create_sales_invoices_return')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            DB::beginTransaction();

            $branch_id = $request['branch_id'];

            if (!authIsSuperAdmin()) {
                $branch_id = auth()->user()->branch_id;
            }

            $sales_invoice = SalesInvoice::findOrFail($request['sales_invoice_id']);

            $customer_id = null;

            if ($sales_invoice->customer) {
                $customer_id = $sales_invoice->customer->id;
            }

            $sales_invoice_return_data = $this->prepareInvoiceData($request, $branch_id);

            $last_invoice = SalesInvoiceReturn::where('branch_id', $branch_id)->latest('created_at')->first();

            $sales_invoice_return_data['invoice_number'] = $last_invoice ? $last_invoice->invoice_number + 1 : 1;

            $sales_invoice_return_data['customer_id'] = $customer_id;

            $sales_invoice_return = SalesInvoiceReturn::create($sales_invoice_return_data);

            $this->handlePointsLog($sales_invoice_return);

            foreach ($request['return_part_ids'] as $index => $part_id) {

                $sales_invoice_item = SalesInvoiceItems::findOrFail($request['sales_invoice_items_id_' . $index]);

                if ($request['return_qty_' . $index] > $sales_invoice_item->sold_qty) {

                    return redirect()->back()->with(['message' => __('words.sorry return quantity is more than sold'),
                        'alert-type' => 'error']);
                }

                $sales_invoice_return_item = $this->calculateItemTotal($request, $part_id, $index);

                $sales_invoice_return_item['sales_invoice_return_id'] = $sales_invoice_return->id;

                $return_item = SalesInvoiceItemReturn::create($sales_invoice_return_item);

                $purchase_invoice = $sales_invoice_item->purchaseInvoice;

                if (!$purchase_invoice) {
                    return redirect()->back()->with(['message' => __('words.something-wrong'),
                        'alert-type' => 'error']);
                }

                $invoice_item = $purchase_invoice->items()->where('part_id', $part_id)->first();

                $this->affectedPurchaseItem($invoice_item, $request['return_qty_' . $index]);

                $this->affectedPart($part_id, $request['return_qty_' . $index], $request['selling_price_' . $index]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
//            dd($e->getMessage());

            return redirect()->back()->with(['message' => __('words.try-again'),
                'alert-type' => 'error']);
        }

        $url = route('admin:expenseReceipts.create', ['sales_invoice_return_id' => $sales_invoice_return->id]);

        try {

            $this->sendNotification('return_sales_invoice', 'customer',
                [
                    'sales_invoice_return' => $sales_invoice_return,
                    'message' => 'New sales invoice return created for you please check'
                ]);

            if ($sales_invoice_return->customer && $sales_invoice_return->customer->email) {

                $this->sendMail($sales_invoice_return->customer->email, 'sales_invoice_return_status', 'sales_invoice_return_create',
                    'App\Mail\SalesInvoiceReturn');
            }

        } catch (\Exception $e) {

            return redirect($url)->with(['message' => __('words.sale-return-invoice-created'), 'alert-type' => 'success']);
        }

        return redirect($url)->with(['message' => __('words.sale-return-invoice-created'), 'alert-type' => 'success']);
//        return Response::json($url, 200);
    }

    public function expensesReceipts(SalesInvoiceReturn $invoice)
    {
        return view('admin.sales_invoice_return.expenses_receipts.index', compact('invoice'));
    }

    public function show(Request $request)
    {
        if (!auth()->user()->can('create_sales_invoices_return')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $invoice = SalesInvoiceReturn::find($request->invoiceID);
        $taxes = TaxesFees::where('active_invoices', 1)->where('branch_id', $invoice->branch_id)->get();
        $totalTax = TaxesFees::where('active_invoices', 1)->where('branch_id', $invoice->branch_id)->sum('value');
        $invoiceData = view('admin.sales_invoice_return.show', compact('invoice', 'taxes', 'totalTax'))->render();
        return response()->json(['invoice' => $invoiceData]);
    }

    public function edit(SalesInvoiceReturn $invoice)
    {

        if (!auth()->user()->can('update_sales_invoices_return')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $salesInvoices = SalesInvoice::select('id', 'invoice_number')
            ->where('branch_id', $invoice->branch_id)->get();
        $branches = Branch::where('status', 1)->get()->pluck('name', 'id');
        $taxes = TaxesFees::where('active_invoices', 1)->where('branch_id', $invoice->branch_id)->get();

        return view('admin.sales_invoice_return.edit',
            compact('invoice', 'salesInvoices', 'branches', 'taxes'));
    }

    // UpdateSalesInvoiceReturnRequest

    public function update(UpdateSalesInvoiceReturnRequest $request, SalesInvoiceReturn $invoice)
    {

        if (!auth()->user()->can('update_sales_invoices_return')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($invoice->expensesReceipts->count()) {
            return redirect()->back()->with(['message' => __('words.sale-return-invoice-paid'), 'alert-type' => 'error']);
        }

        try {
            DB::beginTransaction();

//          reset to qty done in create
            $this->resetSalesInvoiceQty($invoice);

            $branch_id = $invoice->branch_id;

            $sales_invoice = SalesInvoice::findOrFail($request['sales_invoice_id']);

            $customer_id = null;

            if ($sales_invoice->customer) {
                $customer_id = $sales_invoice->customer->id;
            }

            $sales_invoice_return_data = $this->prepareInvoiceData($request, $branch_id);

//            $last_invoice = SalesInvoiceReturn::where('branch_id',$branch_id)->latest('created_at')->first();

//            $sales_invoice_return_data['invoice_number'] = $last_invoice? $last_invoice->invoice_number + 1 : 1;

            $sales_invoice_return_data['customer_id'] = $customer_id;

            $invoice->update($sales_invoice_return_data);

            $this->handlePointsLog($invoice);

            foreach ($request['return_part_ids'] as $index => $part_id) {

                $sales_invoice_item = SalesInvoiceItems::findOrFail($request['sales_invoice_items_id_' . $index]);

                if ($request['return_qty_' . $index] > $sales_invoice_item->sold_qty) {
                    return redirect()->back()->with(['message' => __('words.sorry return quantity is more than sold'), 'alert-type' => 'error']);
                }

                $sales_invoice_return_item = $this->calculateItemTotal($request, $part_id, $index);

                $sales_invoice_return_item['sales_invoice_return_id'] = $invoice->id;

                $return_item = SalesInvoiceItemReturn::create($sales_invoice_return_item);

                $purchase_invoice = $sales_invoice_item->purchaseInvoice;

                if (!$purchase_invoice) {
                    return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
                }

                $invoice_item = $purchase_invoice->items()->where('part_id', $part_id)->first();

                $this->affectedPurchaseItem($invoice_item, $request['return_qty_' . $index]);

                $this->affectedPart($part_id, $request['return_qty_' . $index], $request['selling_price_' . $index]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());

            return redirect()->back()->with(['message' => __('words.try-again'), 'alert-type' => 'error']);
        }

//        $url = route('admin:expenseReceipts.create',['sales_invoice_return_id'=> $invoice->id]);

//        if($invoice->remaining <= 0){
        $url = route('admin:sales.invoices.return.index');
//        }

        try {

            $this->sendNotification('return_sales_invoice', 'customer',
                [
                    'sales_invoice_return' => $invoice,
                    'message' => 'your sales invoice return updated, please check'
                ]);

            if ($invoice->customer && $invoice->customer->email) {

                $this->sendMail($invoice->customer->email, 'sales_invoice_return_status', 'sales_invoice_return_edit',
                    'App\Mail\SalesInvoiceReturn');
            }

        } catch (\Exception $e) {

            return redirect($url)->with(['message' => __('words.sale-return-invoice-updated'), 'alert-type' => 'success']);
        }

        return redirect($url)->with(['message' => __('words.sale-return-invoice-updated'), 'alert-type' => 'success']);
    }

    public function destroy(SalesInvoiceReturn $invoice)
    {

        if (!auth()->user()->can('delete_sales_invoices_return')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $invoice->delete();

        $this->sendNotification('return_sales_invoice', 'customer',
            [
                'sales_invoice_return' => $invoice,
                'message' => 'your sales invoice return deleted, please check'
            ]);

        if ($invoice->customer && $invoice->customer->email) {

            $this->sendMail($invoice->customer->email, 'sales_invoice_return_status', 'sales_invoice_return_delete',
                'App\Mail\SalesInvoiceReturn');
        }

        return redirect()->back()->with(['message' => __('words.sale-return-invoice-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_sales_invoices_return')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            if (isset($request->ids) && is_array($request->ids)) {
                SalesInvoiceReturn::whereIn('id', array_unique($request->ids))->delete();
                return redirect()->back()
                    ->with([
                        'message' => __('words.selected-row-deleted'),
                        'alert-type' => 'success'
                    ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => __('words.try-again'), 'alert-type' => 'error']);
        }
        return redirect()->back()
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

//  new version
    public function getTypeItems(Request $request)
    {
        $rules = [
            'type' => 'required|string|in:normal,direct_invoice,direct_sale_quotations,from_sale_quotations,from_sale_supply_order',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            if ($request['type'] == 'normal') {

                $items = ReturnedSaleReceipt::where('type', 'from_invoice')
                    ->select('id', 'number')
                    ->get();

            } elseif ($request['type'] == 'direct_invoice') {

                $items = SalesInvoice::where('status', 'finished')
                    ->where('invoice_type', 'direct_invoice')
                    ->select('id', 'number')
                    ->get();

            } elseif ($request['type'] == 'direct_sale_quotations') {

                $items = SalesInvoice::where('status', 'finished')
                    ->where('invoice_type', 'direct_sale_quotations')
                    ->select('id', 'number')
                    ->get();

            } elseif ($request['type'] == 'from_sale_quotations') {

                $items = ReturnedSaleReceipt::where('type', 'from_sale_quotation')
                    ->select('id', 'number')
                    ->get();

            } elseif ($request['type'] == 'from_sale_supply_order') {

                $items = ReturnedSaleReceipt::where('type', 'from_sale_supply_order')
                    ->select('id', 'number')
                    ->get();
            }

            $view = view('admin.sales_invoice_return.ajax_type_items', compact('items'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {

            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }
    }

    public function selectSalesInvoiceOrReturnedReceipt(Request $request) {

        $rules = [
            'type' => 'required|string|in:normal,direct_invoice,direct_sale_quotations,from_sale_quotations,from_sale_supply_order',
            'item_id' => 'required|integer|min:0'
        ];

        if ( in_array($request['type'], ['direct_invoice', 'direct_sale_quotations'])) {
            $rules['item_id'] = 'required|integer|exists:sales_invoices,id';

        }else {
            $rules['item_id'] = 'required|integer|exists:returned_sale_receipts,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {


            if (in_array($request['type'], ['direct_invoice', 'direct_sale_quotations'])) {

                $returnedItem =  SalesInvoice::with('items')->where('id', $request['item_id'])
                    ->where('status', 'finished')
                    ->first();
            }else {

                $returnedItem = ReturnedSaleReceipt::find($request['item_id']);
            }

            if (!$returnedItem) {
                return response()->json('sorry, data not valid', 400);
            }


            $index = $returnedItem->items->count();


            $view = view('admin.sales_invoice_return.returned_items', compact('returnedItem'))->render();

            return response()->json(['view' => $view, 'index'=> $index], 200);

        } catch (\Exception $e) {

            dd($e->getMessage());
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

            $view = view('admin.sales_invoice_return.part_quantity', compact('part'))->render();

            return response()->json(['view' => $view,], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function terms(Request $request)
    {
        $this->validate($request, [
            'purchase_return_id' => 'required|integer|exists:purchase_returns,id'
        ]);

        try {

            $purchaseReturn = PurchaseReturn::find($request['purchase_return_id']);

            $purchaseReturn->terms()->sync($request['terms']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('purchase.return.terms.successfully'), 'alert-type' => 'success']);
    }

}
