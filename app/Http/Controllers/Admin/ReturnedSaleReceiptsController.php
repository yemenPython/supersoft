<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ReturnSaleReceipt\CreateRequest;
use App\Models\Branch;
use App\Models\ReturnedSaleReceipt;
use App\Models\SaleQuotation;
use App\Models\SalesInvoice;
use App\Models\SaleSupplyOrder;
use App\Services\ReturnedSaleReceiptServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Yajra\DataTables\DataTables;

class ReturnedSaleReceiptsController extends Controller
{
    public $lang;
    public $returnedSaleReceiptServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->returnedSaleReceiptServices = new ReturnedSaleReceiptServices();
    }

    public function index(Request $request)
    {

        $return_receipts = ReturnedSaleReceipt::query()->latest();

        if ($request->filled('filter')) {
            $return_receipts = $this->filter($request, $return_receipts);
        }
        if ($request->isDataTable) {
            return $this->dataTableColumns($return_receipts);
        } else {
            return view('admin.returned_sale_receipt.index', [
                'return_receipts' => $return_receipts,
                'js_columns' => ReturnedSaleReceipt::getJsDataTablesColumns(),
            ]);
        }

//        return view('admin.returned_sale_receipt.index');
    }

    public function create(Request $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['branches'] = Branch::select('id', 'name_' . $this->lang)->get();

        $lastNumber = ReturnedSaleReceipt::where('branch_id', $branch_id)
            ->orderBy('id', 'desc')
            ->first();

        $data['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.returned_sale_receipt.create', compact('data'));
    }

    public function store(CreateRequest $request)
    {

//        dd($request->all());

        try {

            $data = $request->all();

            $branch_id = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            if (request()['type'] == 'from_invoice') {
                $salesable = SalesInvoice::find($data['salesable_id']);

            } elseif (request()['type'] == 'from_sale_quotation') {
                $salesable = SaleQuotation::find($data['salesable_id']);

            } else {
                $salesable = SaleSupplyOrder::find($data['salesable_id']);
            }

            if ($salesable->check_if_complete_receipt) {
                return redirect()->back()->with(['message' => __('sorry, this supply order is complete'), 'alert-type' => 'error']);
            }

            $validateData = $this->returnedSaleReceiptServices->validateItemsQuantity($salesable, $data['items']);

            if (!$validateData['status']) {
                $message = isset($validateData['message']) ? $validateData['message'] : 'sorry, please try later';
                return redirect()->back()->with(['message' => $message, 'alert-type' => 'error']);
            }

            $returnedReceiptData = $this->returnedSaleReceiptServices->returnedReceiptData($data);

            $returnedReceiptData['branch_id'] = $branch_id;
            $returnedReceiptData['user_id'] = auth()->id();
            $returnedReceiptData['clientable_id'] = $salesable->salesable_id;
            $returnedReceiptData['clientable_type'] = $salesable->salesable_type;

            $lastNumber = ReturnedSaleReceipt::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

            $returnedReceiptData['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            DB::beginTransaction();

            $returnedReceipt = ReturnedSaleReceipt::create($returnedReceiptData);

            $total = $this->returnedSaleReceiptServices->saveReturnedReceiptItems($salesable, $data['items'], $returnedReceipt);

            $returnedReceipt->total = $total['total'];
            $returnedReceipt->total_accepted = $total['total_accepted'];
            $returnedReceipt->total_rejected = $total['total_rejected'];
            $returnedReceipt->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage(), $e->getLine());
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:return-sale-receipts.index'))
            ->with(['message' => __('returned.receipts.created.successfully'), 'alert-type' => 'success']);
    }

    public function edit(ReturnedSaleReceipt $returnSaleReceipt)
    {

        $branch_id = $returnSaleReceipt->branch_id;

        if ($returnSaleReceipt->type == 'from_invoice') {

            $items = SalesInvoice::where('invoice_type', 'normal')
                ->where('status', 'finished')
                ->where('branch_id', $branch_id)
                ->has('concession')->select('id', 'number')->get();

        } elseif ($returnSaleReceipt->type == 'from_sale_quotation') {

            $items = SaleQuotation::where('status', 'finished')
                ->where('branch_id', $branch_id)
                ->whereHas('salesInvoices', function ($q) {
                    $q->where('status', 'finished');
                })
                ->select('id', 'number')->get();

        } else {

            $items = SaleSupplyOrder::where('status', 'finished')
                ->where('branch_id', $branch_id)
                ->whereHas('salesInvoices', function ($q) {
                    $q->where('status', 'finished');
                })
                ->select('id', 'number')->get();
        }

        $data['branches'] = Branch::select('id', 'name_' . $this->lang)->get();

        return view('admin.returned_sale_receipt.edit', compact('returnSaleReceipt', 'data', 'items'));
    }

    public function update(CreateRequest $request, ReturnedSaleReceipt $returnSaleReceipt)
    {

        try {

            DB::beginTransaction();

            $data = $request->all();

            $this->returnedSaleReceiptServices->resetReturnedReceiptItems($returnSaleReceipt);

            if (request()['type'] == 'from_invoice') {
                $salesable = SalesInvoice::find($data['salesable_id']);

            } elseif (request()['type'] == 'from_sale_quotation') {
                $salesable = SaleQuotation::find($data['salesable_id']);

            } else {
                $salesable = SaleSupplyOrder::find($data['salesable_id']);
            }

            if ($salesable->check_if_complete_receipt) {
                return redirect()->back()->with(['message' => __('sorry, this supply order is complete'), 'alert-type' => 'error']);
            }

            $validateData = $this->returnedSaleReceiptServices->validateItemsQuantity($salesable, $data['items']);

            if (!$validateData['status']) {
                $message = isset($validateData['message']) ? $validateData['message'] : 'sorry, please try later';
                return redirect()->back()->with(['message' => $message, 'alert-type' => 'error']);
            }

            $returnedReceiptData = $this->returnedSaleReceiptServices->returnedReceiptData($data);

            $returnedReceiptData['clientable_id'] = $salesable->salesable_id;
            $returnedReceiptData['clientable_type'] = $salesable->salesable_type;

            $returnSaleReceipt->update($returnedReceiptData);

            $total = $this->returnedSaleReceiptServices->saveReturnedReceiptItems($salesable, $data['items'], $returnSaleReceipt);

            $returnSaleReceipt->total = $total['total'];
            $returnSaleReceipt->total_accepted = $total['total_accepted'];
            $returnSaleReceipt->total_rejected = $total['total_rejected'];
            $returnSaleReceipt->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:return-sale-receipts.index'))
            ->with(['message' => __('returned.receipts.updated.successfully'), 'alert-type' => 'success']);

    }

    public function destroy(ReturnedSaleReceipt $returnSaleReceipt)
    {

        try {

            $returnSaleReceipt->delete();

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('returned.receipts.deleted.successfully'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!isset($request->ids)) {
            return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
        }

        try {

            $returnedReceipts = ReturnedSaleReceipt::whereIn('id', array_unique($request->ids))->get();

            foreach ($returnedReceipts as $returnedReceipt) {

                $returnedReceipt->delete();
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect()->back()->with(['message' => __('returned.receipts.deleted.successfully'), 'alert-type' => 'success']);
    }

    public function selectSupplyOrder(Request $request)
    {
        $rules = [
            'supply_order_id' => 'required|integer|exists:supply_orders,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $supplyOrder = SupplyOrder::find($request['supply_order_id']);

            $index = $supplyOrder->items->count();

            $supplerName = $supplyOrder->supplier ? $supplyOrder->supplier->name : '---';

            $view = view('admin.purchase_receipts.supply_order_items', compact('supplyOrder'))->render();

            return response()->json(['parts' => $view, 'supplier_name' => $supplerName, 'index' => $index], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function print(Request $request)
    {
        $returnedSaleReceipt = ReturnedSaleReceipt::findOrFail($request['returned_receipt_id']);

        $view = view('admin.returned_sale_receipt.print', compact('returnedSaleReceipt'))->render();

        return response()->json(['view' => $view]);
    }

    public function show(ReturnedSaleReceipt $returnSaleReceipt)
    {
        return view('admin.returned_sale_receipt.info.show', compact('returnSaleReceipt'));
    }

    public function selectType(Request $request)
    {

        $rules = [
            'type' => 'required|string|in:from_invoice,from_sale_quotation,from_sale_supply_order',
        ];

        $rules['branch_id'] = authIsSuperAdmin() ? 'required|integer|exists:branches,id' : '';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $type = $request['type'];

            if ($type == 'from_invoice') {

                $items = SalesInvoice::where('invoice_type', 'normal')
                    ->where('status', 'finished')
                    ->when($request->has('branch_id'), function ($q) use ($request) {
                        $q->where('branch_id', $request['branch_id']);
                    })
                    ->has('concession')->select('id', 'number')->get();

            } elseif ($type == 'from_sale_quotation') {

                $items = SaleQuotation::where('status', 'finished')
                    ->when($request->has('branch_id'), function ($q) use ($request) {
                        $q->where('branch_id', $request['branch_id']);
                    })
                    ->whereHas('salesInvoices', function ($q) {
                        $q->where('status', 'finished');
                    })
                    ->select('id', 'number')->get();

            } else {

                $items = SaleSupplyOrder::where('status', 'finished')
                    ->when($request->has('branch_id'), function ($q) use ($request) {
                        $q->where('branch_id', $request['branch_id']);
                    })
                    ->whereHas('salesInvoices', function ($q) {
                        $q->where('status', 'finished');
                    })
                    ->select('id', 'number')->get();
            }

            $view = view('admin.returned_sale_receipt.ajax_type_items', compact('items'))->render();

            return response()->json(['parts' => $view], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function getTypeItems(Request $request)
    {

        $rules = [
            'type' => 'required|string|in:from_invoice,from_sale_quotation,from_sale_supply_order',
        ];

        if ($request['type'] == 'from_invoice') {
            $rules['item_id'] = 'required|integer|exists:sales_invoices,id';

        } elseif ($request['type'] == 'from_sale_quotation') {
            $rules['item_id'] = 'required|integer|exists:sale_quotations,id';

        } else {
            $rules['item_id'] = 'required|integer|exists:sale_supply_orders,id';
        }

        $rules['branch_id'] = authIsSuperAdmin() ? 'required|integer|exists:branches,id' : '';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $type = $request['type'];

            if ($type == 'from_invoice') {

                $saleModel = SalesInvoice::find($request['item_id']);

            } elseif ($type == 'from_sale_quotation') {

                $saleModel = SaleQuotation::find($request['item_id']);

            } else {

                $saleModel = SaleSupplyOrder::find($request['item_id']);
            }

            $index = $saleModel->items->count();

            $view = view('admin.returned_sale_receipt.ajax_items', compact('saleModel'))->render();

            return response()->json(['view' => $view, 'index' => $index], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    /**
     * @param Builder $items
     * @return mixed
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.returned_sale_receipt.datatables.options';

        return DataTables::of($items)->addIndexColumn()
            ->addColumn('date', function ($item) use ($viewPath) {
                $withDate = true;
                return view($viewPath, compact('item', 'withDate'))->render();
            })
            ->addColumn('branch_id', function ($item) use ($viewPath) {
                $withBranch = true;
                return view($viewPath, compact('item', 'withBranch'))->render();
            })
            ->addColumn('client_id', function ($item) use ($viewPath) {

                return $item->clientable ? $item->clientable->name : '---';
            })
            ->addColumn('number', function ($item) {
                return $item->number;
            })
            ->addColumn('total', function ($item) use ($viewPath) {
                $total = true;
                return view($viewPath, compact('item', 'total'))->render();
            })
            ->addColumn('total_accepted', function ($item) use ($viewPath) {
                $total_accepted = true;
                return view($viewPath, compact('item', 'total_accepted'))->render();
            })
            ->addColumn('total_rejected', function ($item) use ($viewPath) {
                $total_rejected = true;
                return view($viewPath, compact('item', 'total_rejected'))->render();
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

    private function filter(Request $request, Builder $data)
    {
        return $data->where(function ($query) use ($request) {
            if ($request->filled('branchId')) {
                $query->where('branch_id', $request->branchId);
            }

            if ($request->filled('number')) {
                $query->where('id', $request->number);
            }

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('supply_order_number')) {
                $query->where('supply_order_id', $request->supply_order_number);
            }

            if ($request->filled('date_add_from') && $request->filled('date_add_to')) {
                $query->whereBetween('date', [$request->date_add_from, $request->date_add_to]);
            }

        });
    }
}
