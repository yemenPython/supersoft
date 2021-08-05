<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PurchaseReceipt\CreateRequest;
use App\Models\Branch;
use App\Models\PurchaseReceipt;
use App\Models\SupplyOrder;
use App\Models\TaxesFees;
use App\Services\PurchaseReceiptServices;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PurchaseReceiptsController extends Controller
{
    public $lang;
    public $purchaseReceiptServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->purchaseReceiptServices = new PurchaseReceiptServices();
    }

    public function index(Request $request)
    {
        $purchase_receipts = PurchaseReceipt::query()->latest();
        if ($request->filled('filter')) {
            $purchase_receipts = $this->filter($request, $purchase_receipts);
        }
        if ($request->isDataTable) {
            return $this->dataTableColumns($purchase_receipts);
        } else {
            return view('admin.purchase_receipts.index', [
                'purchase_receipts' => $purchase_receipts,
                'js_columns' => PurchaseReceipt::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create(Request $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['supply_orders'] = SupplyOrder::with('supplier')
            ->where('branch_id', $branch_id)
            ->where('status', 'accept')
            ->select('id', 'number', 'supplier_id')->get();

        $data['branches'] = Branch::select('id', 'name_' . $this->lang)->get();

        $lastNumber = PurchaseReceipt::where('branch_id', $branch_id)
            ->orderBy('id', 'desc')
            ->first();

        $data['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.purchase_receipts.create', compact('data'));
    }

    public function store (CreateRequest $request) {

        try {

            $data = $request->all();

            $branch_id = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $supplyOrder = SupplyOrder::find($data['supply_order_id']);

            if ($supplyOrder->check_if_complete_receipt) {
                return redirect()->back()->with(['message'=> __('sorry, this supply order is complete'), 'alert-type'=>'error']);
            }

            $validateData = $this->purchaseReceiptServices->validateItemsQuantity($supplyOrder, $data['items']);

            if (!$validateData['status']) {
                $message = isset($validateData['message']) ? $validateData['message'] : 'sorry, please try later';
                return redirect()->back()->with(['message'=> $message, 'alert-type'=>'error']);
            }

            $purchaseReceiptData = $this->purchaseReceiptServices->purchaseReceiptData($data);

            $purchaseReceiptData['branch_id'] = $branch_id;
            $purchaseReceiptData['user_id'] = auth()->id();
            $purchaseReceiptData['supplier_id'] = $supplyOrder->supplier_id;

            $lastNumber = PurchaseReceipt::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

            $purchaseReceiptData['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            DB::beginTransaction();

            $purchaseReceipt = PurchaseReceipt::create($purchaseReceiptData);

            $total = $this->purchaseReceiptServices->savePurchaseReceiptItems($supplyOrder, $data['items'], $purchaseReceipt->id);

            $purchaseReceipt->total = $total['total'];
            $purchaseReceipt->total_accepted = $total['total_accepted'];
            $purchaseReceipt->total_rejected = $total['total_rejected'];
            $purchaseReceipt->save();

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());
            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-receipts.index'))
            ->with(['message'=> __('purchase.receipts.created.successfully'), 'alert-type'=>'success']);
    }

    public function edit (PurchaseReceipt $purchaseReceipt) {

        if ($purchaseReceipt->concession) {
            return redirect()->back()->with(['message'=> __('sorry, you can not edit that'), 'alert-type'=>'error']);
        }

        $branch_id = $purchaseReceipt->branch_id;

        $data['supply_orders'] = SupplyOrder::with('supplier')
            ->where('branch_id', $branch_id)
            ->where('status', 'accept')
            ->select('id', 'number', 'supplier_id')->get();

        $data['branches'] = Branch::select('id', 'name_' . $this->lang)->get();

        return view('admin.purchase_receipts.edit', compact('data', 'purchaseReceipt'));
    }

    public function update (CreateRequest $request, PurchaseReceipt $purchaseReceipt) {

        if ($purchaseReceipt->concession) {
            return redirect()->back()->with(['message'=> __('sorry, you can not edit that'), 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $this->purchaseReceiptServices->resetPurchaseReceiptItems($purchaseReceipt);

            $supplyOrder = SupplyOrder::find($data['supply_order_id']);

            if ($supplyOrder->check_if_complete_receipt) {
                return redirect()->back()->with(['message'=> __('sorry, this supply order is complete'), 'alert-type'=>'error']);
            }

            $validateData = $this->purchaseReceiptServices->validateItemsQuantity($supplyOrder, $data['items']);

            if (!$validateData['status']) {
                $message = isset($validateData['message']) ? $validateData['message'] : 'sorry, please try later';
                return redirect()->back()->with(['message'=> $message, 'alert-type'=>'error']);
            }

            $purchaseReceiptData = $this->purchaseReceiptServices->purchaseReceiptData($data);

            $purchaseReceiptData['supplier_id'] = $supplyOrder->supplier_id;

            $purchaseReceipt->update($purchaseReceiptData);

            $total = $this->purchaseReceiptServices->savePurchaseReceiptItems($supplyOrder, $data['items'], $purchaseReceipt->id);

            $purchaseReceipt->total = $total['total'];
            $purchaseReceipt->total_accepted = $total['total_accepted'];
            $purchaseReceipt->total_rejected = $total['total_rejected'];
            $purchaseReceipt->save();

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }

        return redirect(route('admin:purchase-receipts.index'))
            ->with(['message'=> __('purchase.receipts.updated.successfully'), 'alert-type'=>'success']);

    }

    public function destroy (PurchaseReceipt $purchaseReceipt) {

        if ($purchaseReceipt->concession) {
            return redirect()->back()->with(['message'=> __('sorry, you can not delete that'), 'alert-type'=>'error']);
        }

        try {

            $purchaseReceipt->delete();

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('purchase.receipts.deleted.successfully'), 'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (!isset($request->ids)) {
            return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
        }
        try {
            $purchaseReceipts = PurchaseReceipt::whereIn('id', array_unique($request->ids))->get();
            foreach ($purchaseReceipts as $purchaseReceipt) {
                if ($purchaseReceipt->concession) {
                    return redirect()->back()->with(['message'=> __('sorry, you can not delete that'), 'alert-type'=>'error']);
                }
                $purchaseReceipt->delete();
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
        return redirect()->back()->with(['message' => __('purchase.receipts.deleted.successfully'), 'alert-type' => 'success']);
    }

    public function selectSupplyOrder(Request $request)
    {
        $rules = [
            'supply_order_id'=>'required|integer|exists:supply_orders,id',
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

            return response()->json(['parts'=> $view, 'supplier_name'=> $supplerName, 'index'=> $index], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function show(Request $request)
    {
        $purchaseReceipt = PurchaseReceipt::findOrFail($request['purchase_receipt_id']);

        $view = view('admin.purchase_receipts.print', compact('purchaseReceipt'))->render();

        return response()->json(['view' => $view]);
    }

    public function showData (PurchaseReceipt $purchaseReceipt) {

        return view('admin.purchase_receipts.info.show', compact('purchaseReceipt'));
    }


    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.purchase_receipts.datatables.options';
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
            ->addColumn('total_accepted', function ($item) use ($viewPath) {
                $total_accepted = true;
                return view($viewPath, compact('item', 'total_accepted'))->render();
            })
            ->addColumn('total_rejected', function ($item) use ($viewPath) {
                $total_rejected = true;
                return view($viewPath, compact('item', 'total_rejected'))->render();
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

            if ($request->filled('date_add_from') && $request->filled('date_add_to')){
                $query->whereBetween('date', [$request->date_add_from, $request->date_add_to]);
            }

        });
    }
}
