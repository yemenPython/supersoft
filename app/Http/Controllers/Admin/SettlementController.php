<?php

namespace App\Http\Controllers\Admin;

use App\Filters\SettlementFilter;
use App\Http\Requests\Admin\Settlements\CreateRequest;
use App\Models\Branch;
use App\Models\EmployeeData;
use App\Models\Part;
use App\Models\PartPriceSegment;
use App\Models\Settlement;
use App\Models\SettlementItem;
use App\Models\SparePart;
use App\Services\SettlementServices;
use App\Traits\SubTypesServices;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SettlementController extends Controller
{

    use SubTypesServices;

    /**
     * @var SettlementFilter
     */
    protected $settlementFilter;

    public $lang;

    public $settlementService;

    public function __construct(SettlementFilter $settlementFilter)
    {
        $this->lang = App::getLocale();
        $this->settlementService = new SettlementServices();
        $this->settlementFilter = $settlementFilter;
    }

    public function index(Request $request)
    {
        $dataQuery = Settlement::query()->latest();
        if ($request->hasAny((new Settlement())->getFillable())
            || $request->has('dateFrom')
            || $request->has('dateTo')
            || $request->has('store_id')
            || $request->has('settlement_type')
            || $request->has('barcode')
            || $request->has('supplier_barcode')
            || $request->has('partId')
        ) {
            $dataQuery = $this->settlementFilter->filter($request);
        }
        if ($request->isDataTable) {
            return $this->dataTableColumns($dataQuery);
        } else {
            return view('admin.settlements.index', [
                'data' => $dataQuery,
                'js_columns' => Settlement::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create(Request $request)
    {
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

        $lastNumber = Settlement::where('branch_id', $branch_id)
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.settlements.create', compact('branches', 'mainTypes', 'subTypes', 'parts', 'number'));
    }

    public function store(CreateRequest $request)
    {
        if (!$request->has('items')) {
            return redirect()->back()->with(['message' => __('items required'), 'alert-type' => 'error']);
        }

        if ($this->settlementService->checkMaxQuantityOfItem($request['items'])) {
            return redirect()->back()->with(['message' => __('quantity not available'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->validated();

            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }

            $data['user_id'] = auth()->id();

            $lastNumber = Settlement::where('branch_id', $data['branch_id'])->orderBy('id', 'desc')->first();

            $data['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            $data['total'] = 0;

            $settlement = Settlement::create($data);

            $total = 0;

            foreach ($request['items'] as $item) {

                $item['settlement_id'] = $settlement->id;
                SettlementItem::create($item);
                $total += $item['price'] * $item['quantity'];
            }

            $settlement->total = $total;

            $settlement->save();

            if ($request->has('employees')) {
                $settlement->employees()->attach(array_unique($request['employees']));
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
//            dd($e->getMessage());

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:settlements.index'))->with(['message'=> __('Settlements.created'), 'alert-type' => 'success']);
    }

    public function show (Settlement $settlement) {

        $totalQuantity = $settlement->items->sum('quantity');
        return view('admin.settlements.show', compact('settlement', 'totalQuantity'));
    }

    public function edit(Settlement $settlement)
    {
        if ($settlement->concession) {
            return redirect()->back()->with(['message'=> __('sorry, this item has related data'), 'alert-type'=>'error']);
        }

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $mainTypes = SparePart::where('branch_id', $settlement->branch_id)
            ->where('status', 1)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('branch_id', $settlement->branch_id)
            ->where('status', 1)
            ->select('name_' . $this->lang, 'id')
            ->get();

//        $stores = Store::where('branch_id', $settlement->branch_id)->select('name_' . $this->lang,'id')->get();

        $totalQuantity = $settlement->items->sum('quantity');

        $employees = EmployeeData::where('status', 1)->where('branch_id', $settlement->branch_id)->select('name_' . $this->lang, 'id')->get();

        return view('admin.settlements.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts','settlement', 'totalQuantity', 'employees'));
    }

    public function update (CreateRequest $request, Settlement $settlement) {

        if (!$request->has('items')) {
            return redirect()->back()->with(['message'=>'sorry, please select items', 'alert-type' => 'error']);
        }

        if ($settlement->concession) {
            return redirect()->back()->with(['message'=> __('sorry, this item has related data'), 'alert-type'=>'error']);
        }

        if ($this->settlementService->checkMaxQuantityOfItem($request['items'])) {
            return redirect()->back()->with(['message' => __('quantity not available'), 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $this->settlementService->deleteItems($settlement);

            $data = $request->validated();

            $data['total'] = 0;

            $settlement->update($data);

            $total = 0;

            foreach ($request['items'] as $item) {

                $item['settlement_id'] = $settlement->id;
                SettlementItem::create($item);
                $total += $item['price'] * $item['quantity'];
            }

            $settlement->total = $total;

            $settlement->save();

            $settlement->employees()->detach();

            if ($request->has('employees')) {
                $settlement->employees()->attach(array_unique($request['employees']));
            }

            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:settlements.index'))->with(['message'=> __('Settlements.updated'), 'alert-type' => 'success']);
    }

    public function destroy (Settlement $settlement) {

        if ($settlement->concession) {
            return redirect()->back()->with(['message'=> __('sorry, this item has related data'), 'alert-type'=>'error']);
        }

        try {

            $settlement->forceDelete();

        }catch (\Exception $e) {

            return redirect()->back()->with(['message'=>'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:settlements.index'))->with(['message'=> __('Settlement.deleted'), 'alert-type' => 'success']);
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

            $view = view('admin.settlements.part_raw', compact('part', 'index'))->render();

            return response()->json(['parts'=> $view, 'index'=> $index], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
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

            $view = view('admin.settlements.ajax_price_segments', compact('priceSegments', 'index'))->render();

            return response()->json(['view'=> $view], 200);

        }catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {
            $settlements = Settlement::whereIn('id', $request->ids)->get();
            foreach ($settlements as $settlement) {
                if ($settlement->concession) {
                    return redirect()->back()->with(['message'=> __('sorry, this item has related data'), 'alert-type'=>'error']);
                }
            }
            foreach ($settlements as $settlement) {
                $settlement->forceDelete();
            }
            return redirect(route('admin:settlements.index'))->with(['message'=> __('Settlement.deleted'), 'alert-type' => 'success']);
        }
        return redirect(route('admin:settlements.index'))->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function print(Request $request)
    {
        $settlement = Settlement::findOrFail($request['settlement_id']);
        $view = view('admin.settlements.print', compact('settlement'))->render();
        return response()->json(['view' => $view]);
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

            if ($this->settlementService->checkMaxQuantityOfItem($request['items'])) {
                return response()->json(__('quantity not available'), 400);
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
        $viewPath = 'admin.settlements.optional-datatable.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('branch_id', function ($item) use ($viewPath) {
                $withBranch = true;
                return view($viewPath, compact('item', 'withBranch'))->render();
            })
            ->addColumn('date', function ($item) use ($viewPath) {
                $withDate = true;
                return view($viewPath, compact('item', 'withDate'))->render();
            })
            ->addColumn('number', function ($item) {
                return $item->number;
            })
            ->addColumn('type', function ($item) use ($viewPath) {
                $withType = true;
                return view($viewPath, compact('item', 'withType'))->render();
            })
            ->addColumn('total', function ($item) use ($viewPath) {
                $withTotal = true;
                return view($viewPath, compact('item', 'withTotal'))->render();
            })
            ->addColumn('status', function ($item) use ($viewPath) {
                $withStatus = true;
                return view($viewPath, compact('item', 'withStatus'))->render();
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


    public function newEmployee(Request $request)
    {
        $rules = [
            'index' => 'required|integer|min:0',
        ];

        $branch_id = auth()->user()->branch_id;

        if (authIsSuperAdmin()) {

            $rules['branch_id'] = 'required|integer|exists:branches,id';
            $branch_id = $request['branch_id'];
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $employeeIndex = $request['index'] + 1;

            $employees = EmployeeData::where('status', 1)->where('branch_id', $branch_id)->select('name_' . $this->lang, 'id')->get();

            $view = view('admin.settlements.employees.ajax_employee_percent', compact('employees', 'employeeIndex'))->render();

            return response()->json(['view' => $view, 'index' => $employeeIndex], 200);

        } catch (\Exception $e) {

            dd($e->getMessage());
            return response()->json('sorry, please try later', 400);
        }

    }

    public function destroyEmployee(Request $request)
    {
        $rules = [
            'id' => 'required|integer|exists:employee_data,id',
            'settlement_id' => 'required|integer|exists:settlements,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $settlement = Settlement::find($request['settlement_id']);
            $settlement->employees()->detach($request['id']);

        } catch (\Exception $e) {

            return response()->json('sorry, please try later', 400);
        }

    }

}
