<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\Part;
use App\Models\PurchaseQuotation;
use App\Models\PurchaseQuotationItem;
use App\Models\PurchaseRequest;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\SupplyOrderItem;
use App\Services\PurchaseQuotationCompareServices;
use App\Services\SupplyOrderServices;
use App\Traits\SubTypesServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PurchaseQuotationCompareController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $purchaseQuotationCompareServices;
    public $supplyOrderServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->purchaseQuotationCompareServices = new PurchaseQuotationCompareServices();
        $this->supplyOrderServices = new SupplyOrderServices();
    }

    public function index(Request $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $purchase_quotations = PurchaseQuotation::where('branch_id', $branch_id)
            ->select('id', 'number')
            ->get();

        $purchase_requests = PurchaseRequest::where('branch_id', $branch_id)
            ->select('id', 'number')
            ->get();

        $parts = Part::where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang)
            ->get();

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $suppliers = Supplier::where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang)
            ->get();

        $mainTypes = SparePart::where('status', 1)
            ->where('branch_id', $branch_id)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $partsTypes = $this->getAllPartTypes($mainTypes);

        $data = PurchaseQuotationItem::query()->whereHas('purchaseQuotation', function ($q) use($branch_id){
            $q->where('branch_id', $branch_id);
        })->latest();

        if ($request->filled('filter')) {
            $data = $this->purchaseQuotationCompareServices->filter($request, $data, $branch_id);
        }

        if ($request->isDataTable) {
            return $this->dataTableColumns($data);

        } else {

            return view('admin.purchase_quotations_compare.index', [
                'data' => $data,
                'purchase_quotations' => $purchase_quotations,
                'purchase_requests' => $purchase_requests,
                'parts' => $parts,
                'suppliers' => $suppliers,
                'branches' => $branches,
                'partsTypes' => $partsTypes,
                'js_columns' => PurchaseQuotationItem::getJsDataTablesColumns(),
            ]);
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'purchase_quotations_items' => 'required',
            'purchase_quotations_items.*' => 'required|integer|exists:purchase_quotation_items,id',
        ];

        if (authIsSuperAdmin()) {
            $rules['branch_id'] = 'required|integer|exists:branches,id';
        }

        $this->validate($request, $rules);

        $checkItemsData = $this->purchaseQuotationCompareServices->checkItems($request['purchase_quotations_items']);

        if (isset($checkItemsData['status']) && !$checkItemsData['status']) {

            $message = isset($checkItemsData['message']) ? $checkItemsData['message'] : 'sorry, not valid data';
            return redirect()->back()->with(['message' => __($message), 'alert-type' => 'error']);
        }


        try {

            if (!isset($checkItemsData['suppliersId']) && !isset($checkItemsData['suppliersId'][0])) {
                return redirect()->back()->with(['message' => __('supplier not valid'), 'alert-type' => 'error']);
            }

            if (!isset($checkItemsData['purchase_request_ids']) && !isset($checkItemsData['purchase_request_ids'][0])) {
                return redirect()->back()->with(['message' => __('purchase request not valid'), 'alert-type' => 'error']);
            }

            $branch_id = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $lastNumber = SupplyOrder::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

            $number = $lastNumber ? intval($lastNumber->number) + 1 : 1;

            $data = [
                'number' => $number,
                'supplier_id' => $checkItemsData['suppliersId'][0],
                'purchase_request_id' => $checkItemsData['purchase_request_ids'][0],
                'type' => $checkItemsData['purchase_request_ids'][0] ? 'from_purchase_request' : 'normal',
                'date' => Carbon::now()->format('Y-m-d'),
                'time' => Carbon::now()->format('H:i:s'),
                'user_id' => auth()->id(),
                'discount' => 0,
                'discount_type' => 'amount',
            ];

            foreach ($request['purchase_quotations_items'] as $index => $itemId) {

                $index += 1;

                $item = $item = PurchaseQuotationItem::find($itemId);

                $data['items'][$index]['part_id'] = $item->part_id;
                $data['items'][$index]['spare_part_id'] = $item->spare_part_id;
                $data['items'][$index]['part_price_id'] = $item->part_price_id;
                $data['items'][$index]['quantity'] = $item->quantity;
                $data['items'][$index]['price'] = $item->price;
                $data['items'][$index]['discount'] = $item->discount;
                $data['items'][$index]['discount_type'] = $item->discount_type;
                $data['items'][$index]['taxes'] = $item->taxes->pluck('id')->toArray();
            }

            $supplyOrderData = $this->supplyOrderServices->supplyOrderData($data);

            $supplyOrderData['user_id'] = auth()->id();
            $supplyOrderData['branch_id'] = $branch_id;

            DB::beginTransaction();

            $supplyOrder = SupplyOrder::create($supplyOrderData);

            if (isset($checkItemsData['purchase_quotations'])) {

                $quotations = array_values(array_unique($checkItemsData['purchase_quotations']));
                $supplyOrder->purchaseQuotations()->attach($quotations);
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

            dd($e->getMessage());

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        $url = route('admin:supply-orders.edit', $supplyOrder->id);

        return redirect($url)->with(['message' => __('supply.orders.created.successfully'), 'alert-type' => 'success']);
    }

    public function partsByType(Request $request)
    {
        $rules = [
            'spare_part_id' => 'nullable|integer|exists:spare_parts,id'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $parts = Part::where('status', 1);

            if ($request['spare_part_id'] != null) {

                $parts->whereHas('spareParts', function ($q) use ($request) {
                    $q->where('spare_part_type_id', $request['spare_part_id']);
                });
            }

            $parts = $parts->select('name_' . $this->lang, 'id')->get();

            $view = view('admin.purchase_quotations_compare.ajax_parts', compact('parts'))->render();

            return response()->json(['parts' => $view], 200);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function getPurchaseQuotations(Request $request)
    {

        $rules = [
            'type' => 'required|string|in:suppliers,purchase_request',
        ];

        $rules['item_id'] = 'nullable|integer|exists:purchase_requests,id';

        if ($request['type'] == 'supplier') {
            $rules['item_id'] = 'nullable|integer|exists:supplier,id';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $quotations = PurchaseQuotation::query();

            if ($request['type'] == 'suppliers' && $request['item_id'] != null) {
                $quotations->where('supplier_id', $request['item_id']);
            }

            if ($request['type'] == 'purchase_request' && $request['item_id'] != null) {
                $quotations->where('purchase_request_id', $request['item_id']);
            }

            $quotations = $quotations->select('number', 'id')->get();

            $view = view('admin.purchase_quotations_compare.ajax_quotations', compact('quotations'))->render();

            return response()->json(['quotations' => $view], 200);

        } catch (\Exception $e) {

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
        $viewPath = 'admin.purchase_quotations_compare.datatables.options';

        return DataTables::of($items)->addIndexColumn()

            ->addColumn('quotation_number', function ($item) use ($viewPath) {

                return $item->purchaseQuotation ? $item->purchaseQuotation->number : '---';
            })
            ->addColumn('purchase_request_number', function ($item) use ($viewPath) {
                $withPurchaseRequest = true;
                return view($viewPath, compact('item', 'withPurchaseRequest'))->render();
            })
            ->addColumn('supplier_id', function ($item) {
                return $item->purchaseQuotation && $item->purchaseQuotation->supplier  ? $item->purchaseQuotation->supplier->name : '' ;
            })
            ->addColumn('part_id', function ($item) use ($viewPath) {
                return $item->part ? $item->part->name : '---';
            })
            ->addColumn('spare_part_id', function ($item) use ($viewPath) {
                return $item->sparePart ? $item->sparePart->type : '---';
            })
            ->addColumn('part_price_id', function ($item) use ($viewPath) {
                return $item->partPrice &&  $item->partPrice->unit  ? $item->partPrice->unit->unit : '---';
            })
            ->addColumn('part_price_segment_id', function ($item) use ($viewPath) {
                return $item->partPriceSegment ? $item->partPriceSegment->name : '---';
            })
            ->addColumn('quantity', function ($item) use ($viewPath) {
                return $item->quantity ;
            })
            ->addColumn('price', function ($item) {
                return $item->price;
            })

            ->addColumn('discount_type', function ($item) {
                return $item->discount_type;
            })
            ->addColumn('discount', function ($item) {
                return $item->discount;
            })

            ->addColumn('sub_total', function ($item) {
                return $item->sub_total;
            })

            ->addColumn('total_after_discount', function ($item) {
                return $item->total_after_discount;
            })
            ->addColumn('tax', function ($item) {
                return $item->tax;
            })

            ->addColumn('total', function ($item) {
                return $item->total;
            })

            ->addColumn('action', function ($item) use ($viewPath) {
                $withActions = true;
                return view($viewPath, compact('item', 'withActions'))->render();

            })->addColumn('options', function ($item) use ($viewPath) {
                $withOptions = true;
                return view($viewPath, compact('item', 'withOptions'))->render();

            })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
    }

//    private function filter(Request $request, Builder $data): Builder
//    {
//
//        return $data->where(function ($query) use ($request) {
//
//            if ($request->filled('branch_id')) {
//                $query->where('branch_id', $request->branch_id);
//            }
//
//            if ($request->filled('number')) {
//                $query->where('number', $request->number);
//            }
//
//            if ($request->filled('type')) {
//                $query->where('type', $request->type);
//            }
//
//        });
//    }
}
