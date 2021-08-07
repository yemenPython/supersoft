<?php

namespace App\Http\Controllers\Admin;

use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\PointRule;
use App\Models\SaleSupplyOrder;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyTerm;
use App\Models\User;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\SparePart;
use App\Models\TaxesFees;
use App\Notifications\LessPartsNotifications;
use App\Services\HandleQuantityService;
use App\Services\MailServices;
use App\Services\NotificationServices;
use App\Services\PointsServices;
use App\Services\SalesInvoiceServices;
use App\Traits\SubTypesServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use App\Models\SalesInvoice;
use Illuminate\Http\Request;
use App\Models\RevenueReceipt;
use App\Models\PurchaseInvoice;
use App\Models\CustomerCategory;
use App\Models\SalesInvoiceItems;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use App\Services\SampleSalesInvoiceServices;
use App\Http\Controllers\ExportPrinterFactory;
use App\Http\Controllers\DataExportCore\Invoices\Sales;
use App\Http\Requests\Admin\salesInvoice\CreateSalesInvoiceRequest;
use App\Http\Requests\Admin\salesInvoice\UpdateSalesInvoiceRequest;
use Yajra\DataTables\DataTables;
use function GuzzleHttp\Promise\all;


class SalesInvoicesController extends Controller
{
    use  \App\Services\SalesInvoice, SampleSalesInvoiceServices, NotificationServices, MailServices, PointsServices;

    use SubTypesServices;

    public $lang;
    public $salesInvoiceServices;
    public $handleQuantityServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->salesInvoiceServices = new SalesInvoiceServices();
        $this->handleQuantityServices = new HandleQuantityService();

//        $this->middleware('permission:view_sales_invoices');
//        $this->middleware('permission:create_sales_invoices',['only'=>['create','store']]);
//        $this->middleware('permission:update_sales_invoices',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_sales_invoices',['only'=>['destroy','deleteSelected']]);
    }

    public function oldIndex(Request $request)
    {
        if (!auth()->user()->can('view_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $invoices = SalesInvoice::query();
        $invoices->globalCheck($request);

        if ($request->has('invoice_number') && $request['invoice_number'] != '')
            $invoices->where('id', 'like', $request['invoice_number']);

        if ($request->has('customer_id') && $request['customer_id'] != '')
            $invoices->where('customer_id', $request['customer_id']);

        if ($request->has('customer_phone') && $request['customer_phone'] != '')
            $invoices->where('customer_id', $request['customer_phone']);

        if ($request->has('type') && $request['type'] != '')
            $invoices->where('type', $request['type']);

        if ($request->has('branch_id') && $request['branch_id'] != '')
            $invoices->where('branch_id', $request['branch_id']);

        if ($request->has('created_by') && $request['created_by'] != '')
            $invoices->where('created_by', $request['created_by']);

        if ($request->has('date_from') && $request['date_from'] != '')
            $invoices->whereDate('created_at', '>=', $request['date_from']);

        if ($request->has('date_to') && $request['date_to'] != '')
            $invoices->whereDate('created_at', '<=', $request['date_to']);

        if ($request->has('sort_by') && $request->sort_by != '') {
            $sort_by = $request->sort_by;
            $sort_method = $request->has('sort_method') ? $request->sort_method : 'asc';
            if (!in_array($sort_method, ['asc', 'desc'])) $sort_method = 'desc';
            $sort_fields = [
                'invoice-number' => 'invoice_number',
                'customer' => 'customer_id',
                'invoice-type' => 'type',
                'created-at' => 'created_at',
                'updated-at' => 'updated_at'
            ];
            $invoices = $invoices->orderBy($sort_fields[$sort_by], $sort_method);
        } else {
            $invoices = $invoices->orderBy('id', 'DESC');
        }
        if ($request->has('key')) {
            $key = $request->key;
            $invoices->where(function ($q) use ($key) {
                $q->where('invoice_number', 'like', "%$key%")
                    ->orWhere('created_at', 'like', "%$key%");
            });
        }
        if ($request->has('invoker') && in_array($request->invoker, ['print', 'excel'])) {
            $visible_columns = $request->has('visible_columns') ? $request->visible_columns : [];
            return (new ExportPrinterFactory(new Sales($invoices->with('customer'), $visible_columns), $request->invoker))();
        }
        $rows = $request->has('rows') ? $request->rows : 10;

        $invoices = $invoices->paginate($rows)->appends(request()->query());


        $users = filterSetting() ? User::all()->pluck('name', 'id') : null;
        $customers = filterSetting() ? Customer::get() : null;
        $branches = filterSetting() ? Branch::all()->pluck('name', 'id') : null;
        $salesInvoices = filterSetting() ? SalesInvoice::select('id', 'invoice_number')->get() : null;

        return view('admin.sales-invoices.index',
            compact('invoices', 'users', 'customers', 'branches', 'salesInvoices'));
    }

    public function revenueReceipts(SalesInvoice $invoice)
    {
        return view('admin.sales-invoices.revenue_receipts.index', compact('invoice'));
    }

    public function show(Request $request)
    {
        abort(404);

        if (!auth()->user()->can('view_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $sales_invoice = SalesInvoice::find($request->invoiceID);

        $taxes = TaxesFees::where('active_invoices', 1)->where('branch_id', $sales_invoice->branch_id)->get();

        $totalTax = TaxesFees::where('active_invoices', 1)->where('branch_id', $sales_invoice->branch_id)->sum('value');

        $setting = Setting::where('branch_id', auth()->user()->branch_id)->where('sales_invoice_status', 1)->first();

        $invoice = view('admin.sales-invoices.show',
            compact('sales_invoice', 'taxes', 'totalTax', 'setting'))->render();

        return response()->json(['invoice' => $invoice]);
    }

//  ////////////////// new version /////////////////////////////////

    public function index(Request $request)
    {
        $data = SalesInvoice::query()->latest();

        if ($request->filled('filter')) {
            $data = $this->filter($request, $data);
        }

        $paymentTerms = SupplyTerm::where('purchase_invoice', 1)->where('status', 1)->where('type', 'payment')
            ->select('id', 'term_' . $this->lang)->get();

        $supplyTerms = SupplyTerm::where('purchase_invoice', 1)->where('status', 1)->where('type', 'supply')
            ->select('id', 'term_' . $this->lang)->get();

        if ($request->isDataTable) {

            return $this->dataTableColumns($data);

        } else {

            return view('admin.sales_invoice.index', [
                'data' => $data,
                'paymentTerms' => $paymentTerms,
                'supplyTerms' => $supplyTerms,
                'js_columns' => SalesInvoice::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create(Request $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();

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

        $data['taxes'] = TaxesFees::where('active_invoices', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('active_invoices', 1)
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

        return view('admin.sales_invoice.create', compact('data'));
    }

    public function store(CreateSalesInvoiceRequest $request)
    {
        try {

            DB::beginTransaction();

            $data = $request->all();

            $invoice_data = $this->salesInvoiceServices->prepareInvoiceData($data);

            $invoice_data['created_by'] = auth()->id();
            $invoice_data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $salesInvoice = SalesInvoice::create($invoice_data);

            $this->salesInvoiceServices->salesInvoiceTaxes($salesInvoice, $data);

            foreach ($data['items'] as $item) {

                $item_data = $this->salesInvoiceServices->calculateItemTotal($item);

                $item_data['sales_invoice_id'] = $salesInvoice->id;

                $salesInvoiceItem = SalesInvoiceItems::create($item_data);

                if (isset($item['taxes'])) {
                    $salesInvoiceItem->taxes()->attach($item['taxes']);
                }
            }

            if ($salesInvoice->status == 'finished' && $salesInvoice->invoice_type = 'direct_invoice') {

                $acceptQuantityData = $this->handleQuantityServices->acceptQuantity($salesInvoice->items, 'pull');

                if (isset($acceptQuantityData['status']) && !$acceptQuantityData['status']) {

                    $message = isset($acceptQuantityData['message']) ? $acceptQuantityData['message'] : __('sorry, please try later');

                    return redirect()->back()->with(['message' => $message, 'alert-type' => 'error']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => __('words.sales-invoice-cant-created'), 'alert-type' => 'error']);
        }

        return redirect()->to(route('admin:sales.invoices.index'))
            ->with(['message' => __('words.sales-invoice-created'), 'alert-type' => 'success']);

    }

    public function edit(SalesInvoice $salesInvoice)
    {

        if (!auth()->user()->can('update_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($salesInvoice->status == 'finished') {
            return redirect()->back()->with(['message' => __('words.cant.update.finished.items'), 'alert-type' => 'error']);
        }

        $branch_id = $salesInvoice->branch_id;

        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();

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

        $data['taxes'] = TaxesFees::where('active_invoices', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('active_invoices', 1)
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

        return view('admin.sales_invoice.edit', compact('data', 'salesInvoice'));

    }

    public function update(UpdateSalesInvoiceRequest $request, SalesInvoice $salesInvoice)
    {
        if ($salesInvoice->status == 'finished') {
            return redirect()->back()->with(['message' => __('words.cant.update.finished.items'), 'alert-type' => 'success']);
        }

        try {

            DB::beginTransaction();

            $this->salesInvoiceServices->resetSalesInvoiceDataItems($salesInvoice);

            $data = $request->all();

            $invoice_data = $this->salesInvoiceServices->prepareInvoiceData($data);

            $salesInvoice->update($invoice_data);

            $this->salesInvoiceServices->salesInvoiceTaxes($salesInvoice, $data);

            foreach ($data['items'] as $item) {

                $item_data = $this->salesInvoiceServices->calculateItemTotal($item);

                $item_data['sales_invoice_id'] = $salesInvoice->id;

                $purchaseInvoiceItem = SalesInvoiceItems::create($item_data);

                if (isset($item['taxes'])) {
                    $purchaseInvoiceItem->taxes()->attach($item['taxes']);
                }
            }

            if ($salesInvoice->status == 'finished' && $salesInvoice->invoice_type = 'direct_invoice') {

                $acceptQuantityData = $this->handleQuantityServices->acceptQuantity($salesInvoice->items, 'pull');

                if (isset($acceptQuantityData['status']) && !$acceptQuantityData['status']) {

                    $message = isset($acceptQuantityData['message']) ? $acceptQuantityData['message'] : __('sorry, please try later');

                    return redirect()->back()->with(['message' => $message, 'alert-type' => 'error']);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:sales.invoices.index'))->with(['message' => __('words.sale-invoice-updated'), 'alert-type' => 'success']);
    }

    public function destroy(SalesInvoice $invoice)
    {
        if (!auth()->user()->can('delete_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($invoice->status == 'finished') {
            return redirect()->back()->with(['message' => __('words.cant.update.finished.items')]);
        }

        $invoice->delete();

//        $this->sendNotification('sales_invoice', 'customer',
//            [
//                'sales_invoice' => $invoice,
//                'message' => 'Your sales invoice deleted, please check'
//            ]);

//        if ($invoice->customer && $invoice->customer->email) {
//
//            $this->sendMail($invoice->customer->email, 'sales_invoice_status', 'sales_invoice_delete', 'App\Mail\SalesInvoice');
//        }

        return redirect()->back()->with(['message' => __('words.sale-invoice-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_sales_invoices')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try {
            if (isset($request->ids) && is_array($request->ids)) {

                SalesInvoice::where('status', '!=', 'finished')->whereIn('id', array_unique($request->ids))->delete();

                return redirect()->back()->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => __('words.try-agian'), 'alert-type' => 'error']);
        }
        return redirect()->back()
            ->with(['message' => __('words.select-row-least'), 'alert-type' => 'error']);
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

            $view = view('admin.sales_invoice.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts' => $view, 'index' => $index], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function print(Request $request)
    {
        $salesInvoice = SalesInvoice::findOrFail($request['sales_invoice_id']);

        $view = view('admin.sales_invoice.show', compact('salesInvoice'))->render();

        return response()->json(['view' => $view]);
    }

    public function terms(Request $request)
    {
        $this->validate($request, [
            'sales_invoice_id' => 'required|integer|exists:sales_invoices,id'
        ]);

        try {

            $salesInvoice = SalesInvoice::find($request['sales_invoice_id']);

            $salesInvoice->terms()->sync($request['terms']);

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:sales.invoices.index'))->with(['message' => __('sales.invoices.terms.successfully'), 'alert-type' => 'success']);
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

            $view = view('admin.sales_invoice.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view' => $view], 200);

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function showData(SalesInvoice $salesInvoice)
    {
        $branch_id = $salesInvoice->branch_id;

        $data['taxes'] = TaxesFees::where('active_invoices', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $data['additionalPayments'] = TaxesFees::where('active_invoices', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id', 'value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.sales_invoice.info.show', compact('salesInvoice', 'data'));
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.sales_invoice.datatables.options';

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
                return $item->type_for ? $item->type_for:'---';
            })
            ->addColumn('salesable_id', function ($item) use ($viewPath) {
                return $item->salesable ? $item->salesable->name : '---';
            })
            ->addColumn('total', function ($item) use ($viewPath) {
                return $item->total;
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
                $query->where('number', $request->number);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

        });
    }

}
