<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SaleQuotation\CreateRequest;
use App\Http\Requests\Admin\SaleQuotation\UpdateRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\SaleQuotation;
use App\Models\SaleQuotationItem;
use App\Models\SparePart;
use App\Models\Supplier;
use App\Models\SupplyTerm;
use App\Models\TaxesFees;
use App\Services\SaleQuotationServices;
use App\Traits\SubTypesServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SaleQuotationController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $saleQuotationServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->saleQuotationServices = new SaleQuotationServices();
    }

    public function index (Request $request) {

        $data = SaleQuotation::query()->latest();

        $paymentTerms = SupplyTerm::where('sale_quotation', 1)->where('status', 1)->where('type', 'payment')
            ->select('id', 'term_' . $this->lang)->get();

        $supplyTerms = SupplyTerm::where('sale_quotation', 1)->where('status', 1)->where('type', 'supply')
            ->select('id', 'term_' . $this->lang)->get();

        if ($request->isDataTable) {
            return $this->dataTableColumns($data);
        } else {
            return view('admin.sale_quotations.index', [
                'data' => $data,
                'paymentTerms' => $paymentTerms,
                'supplyTerms' => $supplyTerms,
                'js_columns' => SaleQuotation::getJsDataTablesColumns(),
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

        $taxes = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        $suppliers = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $customers = Customer::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'customer_category_id')
            ->get();

        $lastNumber = SaleQuotation::where('branch_id', $branch_id)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.sale_quotations.create',
            compact('branches', 'mainTypes', 'subTypes','additionalPayments', 'parts', 'taxes',
                'customers', 'suppliers', 'number'));
    }

    public function store(CreateRequest $request) {


        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->all();

            $saleQuotationData =  $this->saleQuotationServices->saleQuotationData($data);

            $saleQuotationData['user_id'] = auth()->id();
            $saleQuotationData['branch_id'] = authIsSuperAdmin() ? $data['branch_id']  : auth()->user()->branch_id;

            $lastNumber = SaleQuotation::where('branch_id', $saleQuotationData['branch_id'])
                ->orderBy('id', 'desc')
                ->first();

            $saleQuotationData['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            $saleQuotation = SaleQuotation::create($saleQuotationData);

            $this->saleQuotationServices->saleQuotationTaxes($saleQuotation, $data);

            foreach ($data['items'] as $item) {

                $itemData = $this->saleQuotationServices->saleQuotationItemData($item);
                $itemData['sale_quotation_id'] = $saleQuotation->id;
                $saleQuotationItem = SaleQuotationItem::create($itemData);

                if (isset($item['taxes'])) {
                    $saleQuotationItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            dd($e->getMessage());
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:sale-quotations.index'))->with(['message'=> __('sale.quotations.created.successfully'), 'alert-type'=>'success']);
    }

    public function edit (SaleQuotation $saleQuotation) {

        $branch_id = $saleQuotation->branch_id;

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

        $taxes = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type', 'execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        $suppliers = Supplier::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'group_id', 'sub_group_id')
            ->get();

        $customers = Customer::where('status', 1)
            ->where('branch_id', $branch_id)
            ->select('id', 'name_' . $this->lang, 'customer_category_id')
            ->get();

        return view('admin.sale_quotations.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts', 'saleQuotation', 'taxes',
                'additionalPayments', 'customers', 'suppliers'));
    }

    public function update (UpdateRequest $request, SaleQuotation $saleQuotation) {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type'=>'error']);
        }

        try {

            DB::beginTransaction();

            $this->saleQuotationServices->resetsaleQuotationItems($saleQuotation);

            $data = $request->all();

            $saleQuotationData =  $this->saleQuotationServices->saleQuotationData($data);

            $saleQuotation->update($saleQuotationData);

            $this->saleQuotationServices->saleQuotationTaxes($saleQuotation, $data);

            foreach ($data['items'] as $item) {

                $itemData = $this->saleQuotationServices->saleQuotationItemData($item);
                $itemData['sale_quotation_id'] = $saleQuotation->id;
                $saleQuotationItem = SaleQuotationItem::create($itemData);

                if (isset($item['taxes'])) {
                    $saleQuotationItem->taxes()->attach($item['taxes']);
                }
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:sale-quotations.index'))->with(['message'=> __('sale.quotations.created.successfully'), 'alert-type'=>'success']);
    }

    public function destroy (SaleQuotation $saleQuotation) {

        try {

            $saleQuotation->delete();

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect()->back()->with(['message'=> __('sale.quotations.deleted.successfully'), 'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!isset($request->ids)) {
            return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
        }

        try {

            $ids = array_unique($request->ids);

            $saleQuotations = SaleQuotation::whereIn('id', $ids)->get();

            foreach ($saleQuotations as $saleQuotation) {
                $saleQuotation->delete();
            }

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }
        return redirect(route('admin:sale-quotations.index'))->with(['message' => __('words.sale-quotations-deleted'), 'alert-type' => 'success']);
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

            $view = view('admin.sale_quotations.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts'=> $view, 'index'=> $index], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function print(Request $request)
    {
        $saleQuotation = SaleQuotation::findOrFail($request['sale_quotation_id']);

        $view = view('admin.sale_quotations.print', compact('saleQuotation'))->render();

        return response()->json(['view' => $view]);
    }

    public function terms (Request $request) {

        $this->validate($request, [
            'sale_quotation_id'=>'required|integer|exists:sale_quotations,id',
            'terms'=>'required',
            'terms.*'=>'required|integer|exists:supply_terms,id',
        ]);

        try {

            $saleQuotation = SaleQuotation::find($request['sale_quotation_id']);

            $saleQuotation->terms()->sync($request['terms']);

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type'=>'error']);
        }

        return redirect(route('admin:sale-quotations.index'))->with(['message'=> __('sale.quotations.terms.successfully'), 'alert-type'=>'success']);
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

            $view = view('admin.sale_quotations.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view'=> $view], 200);

        }catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function show (SaleQuotation $saleQuotation) {

        $branch_id = $saleQuotation->branch_id;

        $taxes = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'tax')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        $additionalPayments = TaxesFees::where('active_offers', 1)
            ->where('branch_id', $branch_id)
            ->where('type', 'additional_payments')
            ->select('id','value', 'tax_type','execution_time', 'name_' . $this->lang)
            ->get();

        return view('admin.sale_quotations.info.show', compact('saleQuotation', 'taxes', 'additionalPayments'));
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

            $invalidItems = $this->saleQuotationServices->checkMaxQuantityOfItem($request['items']);

            if (!empty($invalidItems)) {

                $message = __('quantity not available for this items ') ."\n          ". '('.implode(' ,', $invalidItems).')';
                return response()->json($message, 400);
            }

        } catch (\Exception $e) {

            dd($e->getMessage());
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
        $viewPath = 'admin.sale_quotations.datatables.options';
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

            ->addColumn('type', function ($item) use ($viewPath) {
                $type = true;
                return view($viewPath, compact('item', 'type'))->render();
            })
            ->addColumn('customer', function ($item) use ($viewPath) {
                $withCustomer = true;
                return view($viewPath, compact('item', 'withCustomer'))->render();
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
}
