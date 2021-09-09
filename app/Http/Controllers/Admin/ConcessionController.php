<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\TaxesFees;
use Exception;
use App\Models\Branch;
use App\Models\Concession;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\ConcessionType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Services\ConcessionService;
use App\Http\Requests\Admin\Concession\CreateRequest;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Yajra\DataTables\DataTables;

class ConcessionController extends Controller
{
    public $lang;
    public $concessionService;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->concessionService = new ConcessionService();
    }

    public function index(Request $request)
    {
        $concessions = $this->concessionService->search($request);
        $additionalData['concessions'] = Concession::select('id', 'number')->get();
        $concessionTypes = ConcessionType::select('id', 'name_' . $this->lang)->get();
        if ($request->isDataTable) {
            return $this->dataTableColumns($concessions);
        } else {
            return view('admin.concessions.index', [
                'concessions' => $concessions,
                'concessionTypes' => $concessionTypes,
                'additionalData' => $additionalData,
                'js_columns' => Concession::getJsDataTablesColumns(),
            ]);
        }
    }

    public function archive(Request $request)
    {
        $concessions = $this->concessionService->search($request, true);
        $concessions = $concessions->get();
        $concessionTypes = ConcessionType::select('id', 'name_' . $this->lang)->get();
        $additionalData['concessions'] = Concession::select('id', 'number')->get();
        return view('admin.concessions.archive', compact('concessions', 'concessionTypes', 'additionalData'));
    }

    public function create(Request  $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $concessionTypes = ConcessionType::where('branch_id', $branch_id)
            ->where('type', 'add')
            ->select('id', 'name_' . $this->lang, 'type')
            ->get();

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $lastNumber = Concession::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

        $number = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.concessions.create', compact('concessionTypes', 'branches', 'number'));
    }

    public function store(Request $request)
    {
        $rules = $this->concessionService->createConcessionRules();

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $concessionType = ConcessionType::find($request['concession_type_id']);

            if ($concessionType->type != $request['type']) {
                return response()->json( __('sorry, concession type not valid'), 400);
            }

            $concessionTypeItem = $concessionType->concessionItem;

            if (!$concessionTypeItem) {
                return response()->json( __('sorry, concession type not have item'), 400);
            }

            $className = $concessionTypeItem->model;

            if ($this->concessionService->checkItemHasOldConcession($className, $request['item_id'],$concessionType->type)) {
                return response()->json( __('sorry, this item has old concession'), 400);
            }

            $invalidItems = $this->concessionService->checkMaxQuantityOfItem($className, $request['item_id'], $request['type']);

            if (!empty($invalidItems)) {

                $message = __('quantity not available for this items ') . '('.implode($invalidItems,' ,').')';
                return response()->json($message, 400);
            }

            $data = $request->all();

            $data['concessionable_type'] = $className;

            DB::beginTransaction();

            $concessionData = $this->concessionService->concessionData($data, 'create');

            $concession = Concession::create($concessionData);

            if ($className == 'StoreTransfer' && $concession->type == 'add') {

                if (!$this->concessionService->checkStoreTransferHasConcession($concession)) {
                    return response()->json( __('sorry, please create withdrawal concession first and accept'), 400);
                }
            }

            $this->concessionService->createConcessionItems($concession);

            if ($concession->status == 'accepted') {

                $acceptQuantityData = $this->concessionService->acceptQuantity($concession);

                if (isset($acceptQuantityData['status']) && !$acceptQuantityData['status']) {

                    $message = isset($acceptQuantityData['message']) ? $acceptQuantityData['message'] : __('sorry, please try later');

                    return response()->json( __($message), 400);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json( __('sorry, please try later'), 400);
        }

        return response()->json( ['message' => __('Concession created successfully'), 'link'=> route('admin:concessions.index') ], 200);
    }

    public function edit(Concession $concession)
    {
        if ($concession->status != 'pending') {
            return redirect()->back()->with(['message' => 'sorry, you can note update this item', 'alert-type' => 'error']);
        }

        $concessionTypes = ConcessionType::where('branch_id', $concession->branch_id)
            ->where('type', $concession->type)
            ->select('id', 'name_' . $this->lang, 'type')
            ->get();

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $model = $concession->concessionable_type;

        $modelName = $concession->concessionType->concessionItem ? $concession->concessionType->concessionItem->model : '';

        $concessionTypeItems = $this->getConcessionTypeItems($concession, $modelName, $model);

        $totalPrice = 0;

        foreach ($concession->concessionItems as $item) {

            $totalPrice += $item->price * $item->quantity;
        }

        return view('admin.concessions.edit',
            compact('concessionTypes', 'branches', 'concession', 'concessionTypeItems', 'totalPrice'));
    }

    public function getConcessionTypeItems ($concession, $modelName, $model) {

        $concessionTypeItems = $model::where('branch_id', $concession->branch_id);

        if ($modelName == 'Settlement' && $concession->concessionType->type == 'add') {
            $concessionTypeItems->where('type', 'positive');
        }

        if ($modelName == 'Settlement' && $concession->concessionType->type == 'withdrawal') {
            $concessionTypeItems->where('type', 'negative');
        }

        if ($modelName == 'StoreTransfer' && $concession->type == 'add') {

            $concessionTypeItems->whereDoesntHave('concession', function ($q) use ($concession) {

                $q->where('type', 'add')->where('concessionable_id','!=', $concession->concessionable_id);

            })->whereHas('concession', function ($q) use ($concession) {
                $q->where('type', 'withdrawal'); // and status accepted
            });

        } elseif ($modelName == 'PurchaseReturn') {

            $concessionTypeItems->where('invoice_type', 'from_supply_order')
                ->where('status', 'finished')->where(function ($q) use ($concession) {

                    $q->whereHas('concession', function ($q) use ($concession) {
                        $q->where('concessionable_id', $concession->concessionable_id);
                    })->orDoesntHave('concession');
                });

        }elseif ($modelName == 'SalesInvoice') {

            $concessionTypeItems->where('status', 'finished')
                ->whereIn('invoice_type', ['normal', 'from_sale_quotations', 'from_sale_supply_order'])

                ->where(function ($q) use ($concession) {

                    $q->whereHas('concession', function ($q) use ($concession) {
                        $q->where('concessionable_id', $concession->concessionable_id);
                    })->orDoesntHave('concession');
                });

        } else {

            $concessionTypeItems->whereHas('concession', function ($q) use ($concession) {

                $q->where('concessionable_id', $concession->concessionable_id);

            })->orDoesntHave('concession');
        }

        $concessionTypeItems = $concessionTypeItems->get();

        return $concessionTypeItems;
    }

    public function update(Request $request)
    {
        $rules = $this->concessionService->createConcessionRules();

        $rules['concession_id'] = 'required|integer|exists:concessions,id';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        $concession = Concession::find($request['concession_id']);

        if ($concession->status != 'pending') {
            return redirect()->back()->with(['message' => 'sorry, you can note update this item', 'alert-type' => 'error']);
        }

        try {

            $concessionType = ConcessionType::find($request['concession_type_id']);

            if ($concessionType->type != $request['type']) {
                return response()->json( __('sorry, concession type not valid'), 400);
            }

            $concessionTypeItem = $concessionType->concessionItem;

            if (!$concessionTypeItem) {
                return response()->json( __('sorry, concession type not have item'), 400);
            }

            $data = $request->all();

            $data['concessionable_type'] = $concessionTypeItem->model;

            DB::beginTransaction();

            $this->concessionService->deleteConcessionItems($concession);

            $concessionData = $this->concessionService->concessionData($data, 'update');

            $className = $concession->concessionable ? class_basename($concession->concessionable) : '';

            if ($concession->concessionable_id != $data['item_id'] &&
                $this->concessionService->checkItemHasOldConcession($className, $request['item_id'],$concessionType->type)) {

                return response()->json( __('sorry, this item has old concession'), 400);
            }

            $invalidItems = $this->concessionService->checkMaxQuantityOfItem($className, $request['item_id'], $request['type']);

            if (!empty($invalidItems)) {

                $message = __('quantity not available for this items ') . '('.implode($invalidItems,' ,').')';
                return response()->json($message, 400);
            }

            if ($className == 'StoreTransfer' && $concession->type == 'add') {

                if (!$this->concessionService->checkStoreTransferHasConcession($concession)) {

                    return response()->json( __('sorry, please create withdrawal concession first'), 400);
                }
            }

            $concession->update($concessionData);

            $this->concessionService->createConcessionItems($concession);

            if ($concession->status == 'accepted') {

                $acceptQuantityData = $this->concessionService->acceptQuantity($concession);

                if (isset($acceptQuantityData['status']) && !$acceptQuantityData['status']) {

                    $message = isset($acceptQuantityData['message']) ? $acceptQuantityData['message'] : __('sorry, please try later');

                    return response()->json( __($message), 400);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json( __('sorry, please try later'), 400);
        }

        return response()->json( ['message' => __('Concession updated successfully'), 'link'=> route('admin:concessions.index') ], 200);
    }

    public function show(Request $request)
    {

        $concession = Concession::findOrFail($request['concession_id']);

        $view = view('admin.concessions.show', compact('concession'))->render();

        return response()->json(['view' => $view]);
    }

    public function destroy(Concession $concession)
    {
        if ($concession->status == 'accepted') {
            return redirect()->back()->with([
                'message' => __('sorry, this item accepted'),
                'alert-type' => 'error'
            ]);
        }
        $concession->forceDelete();
        return redirect()->back()->with(['message' => 'Concession deleted successfully', 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids) && isset($request->archive)) {
            $concessions = Concession::whereIn('id', $request->ids)->get();
            foreach ($concessions as $concession) {
                $concession->delete();
            }
            return back()->with(['message' => __('words.selected-row-archived'), 'alert-type' => 'success']);
        }

        if (isset($request->ids) && isset($request->restore)) {
            $concessions = Concession::withTrashed()->whereIn('id', $request->ids)->get();
            foreach ($concessions as $concession) {
                $concession->restore();
            }
            return back()->with(['message' => __('words.selected-row-restored'), 'alert-type' => 'success']);
        }

        if (isset($request->ids)) {
            $concessions = Concession::whereIn('id', $request->ids)->get();
            foreach ($concessions as $concession) {
                if ($concession->status == 'accepted') {
                    return redirect()->back()->with([
                        'message' => __('sorry, this item accepted'),
                        'alert-type' => 'error'
                    ]);
                }
                $concession->forceDelete();
            }
            return redirect()->back()->with(['message' => 'Concession deleted successfully', 'alert-type' => 'success']);
        }
        return redirect(route('admin:damaged-stock.index'))->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function restoreDelete(int $id)
    {
        try {
            $concession = Concession::withTrashed()->findOrFail($id);
            $concession->restore();
            return back()->with(['message' => __('words.data-restored-successfully'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            return back()->with(['message' => __('words.can-not-restore-date-from-archive'), 'alert-type' => 'error']);
        }
    }

    public function archiveData(Concession $concession)
    {
        $concession->delete();
        return redirect()->back()->with(['message' => __('words.words.data-archived-success'), 'alert-type' => 'success']);
    }

    public function showData (Concession $concession) {

        $branch_id = $concession->branch_id;

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

        $totalPrice = 0;

        foreach ($concession->concessionItems as $item) {
            $totalPrice += $item->price * $item->quantity;
        }

        return view('admin.concessions.info.show', compact('concession', 'data', 'totalPrice'));
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.concessions.options-datatable.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('date', function ($item) use ($viewPath) {
                $withDate = true;
                return view($viewPath, compact('item', 'withDate'))->render();
            })
            ->addColumn('numberType', function ($item) use ($viewPath) {
                $withNumber = true;
                return view($viewPath, compact('item', 'withNumber'))->render();
            })
            ->addColumn('total', function ($item) use ($viewPath) {
                $withTotal = true;
                return view($viewPath, compact('item', 'withTotal'))->render();
            })
            ->addColumn('type', function ($item) use ($viewPath) {
                $withType = true;
                return view($viewPath, compact('item', 'withType'))->render();
            })
            ->addColumn('number', function ($item) {
                return optional($item->concessionable)->number;
            })
            ->addColumn('ConcessionType', function ($item) use ($viewPath) {
                $withConcessionType = true;
                return view($viewPath, compact('item', 'withConcessionType'))->render();
            })
            ->addColumn('status', function ($item) use ($viewPath) {
                $withStatus = true;
                return view($viewPath, compact('item', 'withStatus'))->render();
            })
            ->addColumn('concessionStatus', function ($item) use ($viewPath) {
                $withConcessionStatus = true;
                return view($viewPath, compact('item', 'withConcessionStatus'))->render();
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

    public function partStoreQuantity (Request $request) {

        $rules = [
            'part_id' => 'required|integer|exists:parts,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $part = Part::find($request['part_id']);

            $view = view('admin.concessions.show_part_quantity', compact( 'part'))->render();

        } catch (\Exception $e) {
            return response()->json('sorry, please try later', 400);
        }

        return response()->json(['view' => $view], 200);

    }
}
