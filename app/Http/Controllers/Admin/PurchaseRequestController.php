<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PurchaseRequest\CreateRequest;
use App\Models\Branch;
use App\Models\Part;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use App\Models\Settlement;
use App\Models\SparePart;
use App\Services\PurchaseRequestService;
use App\Traits\SubTypesServices;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PurchaseRequestController extends Controller
{
    use SubTypesServices;

    public $lang;
    public $purchaseRequestServices;

    public function __construct()
    {
        $this->lang = App::getLocale();
        $this->purchaseRequestServices = new PurchaseRequestService();
    }

    public function index(Request $request)
    {
        $data = PurchaseRequest::query()->latest();
        if ($request->filled('filter')) {
            $data = $this->filter($request, $data);
        }

        if ($request->isDataTable) {
            return $this->dataTableColumns($data);
        } else {
            return view('admin.purchase_requests.index', [
                'data' => $data,
                'js_columns' => PurchaseRequest::getJsDataTablesColumns(),
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

        $lastNumber = PurchaseRequest::where('branch_id', $branch_id)->orderBy('id', 'desc')->first();

        $number = $lastNumber ? $lastNumber->number + 1 : 1;

        return view('admin.purchase_requests.create', compact('branches', 'mainTypes', 'subTypes', 'parts', 'number'));
    }

    public function store(CreateRequest $request)
    {
        try {

            DB::beginTransaction();

            $data = $request->validated();

            $purchaseRequestData = $this->purchaseRequestServices->purchaseRequestData($data);

            $purchaseRequestData['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;;

            $lastNumber = PurchaseRequest::where('branch_id', $purchaseRequestData['branch_id'])->orderBy('id', 'desc')->first();

            $purchaseRequestData['number'] = $lastNumber ? $lastNumber->number + 1 : 1;

            $purchaseRequestData['user_id'] = auth()->id();

            $purchaseRequest = PurchaseRequest::create($purchaseRequestData);

            if (isset($data['items'])) {

                foreach ($data['items'] as $item) {

                    $itemData = $this->purchaseRequestServices->purchaseRequestItemData($item);

                    $itemData['purchase_request_id'] = $purchaseRequest->id;

                    $purchaseRequestItem = PurchaseRequestItem::create($itemData);

                    if (isset($item['item_types'])) {

                        $purchaseRequestItem->spareParts()->attach($item['item_types']);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:purchase-requests.index'))->with(['message' => __('words.purchase-request-created'), 'alert-type' => 'success']);
    }

    public function show (PurchaseRequest $purchaseRequest) {
        return view('admin.purchase_requests.show', compact('purchaseRequest'));
    }

    public function print(Request $request)
    {
        $purchaseRequest = PurchaseRequest::findOrFail($request['purchase_request_id']);

        $view = view('admin.purchase_requests.print', compact('purchaseRequest'))->render();

        return response()->json(['view' => $view]);
    }

    public function shortPrint(Request $request)
    {

        $purchaseRequest = PurchaseRequest::findOrFail($request['purchase_request_id']);

        $view = view('admin.purchase_requests.short_print', compact('purchaseRequest'))->render();

        return response()->json(['view' => $view]);
    }

    public function edit(Request $request, PurchaseRequest $purchaseRequest)
    {
        if (!$request->has('request_type') && $purchaseRequest->status != 'under_processing') {
            return redirect()->back()->with(['message' => __('you can not update, finished for editing'), 'alert-type' => 'error']);
        }

        if ($request->has('request_type') && $purchaseRequest->status != 'ready_for_approval') {
            return redirect()->back()->with(['message' => __('you can not update, finished for approval'), 'alert-type' => 'error']);
        }

        $request_type = $request->has('request_type') ? 'approval' : 'edit';

        $branches = Branch::select('id', 'name_' . $this->lang)->get();

        $branch_id = $purchaseRequest->branch_id;

        $mainTypes = SparePart::where('branch_id', $branch_id)
            ->where('status', 1)
            ->where('spare_part_id', null)
            ->select('id', 'type_' . $this->lang)
            ->get();

        $subTypes = $this->getSubPartTypes($mainTypes);

        $parts = Part::where('branch_id', $branch_id)
            ->where('status', 1)
            ->select('name_' . $this->lang, 'id')
            ->get();

        return view('admin.purchase_requests.edit',
            compact('branches', 'mainTypes', 'subTypes', 'parts', 'purchaseRequest', 'request_type'));
    }

    public function update(CreateRequest $request, PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status == 'ready_for_approval') {
            return redirect()->back()->with(['message' => 'you can not update', 'alert-type' => 'error']);
        }

        try {

            DB::beginTransaction();

            $data = $request->validated();

            $this->purchaseRequestServices->resetItems($purchaseRequest);

            $purchaseRequestData = $this->purchaseRequestServices->purchaseRequestData($data);

            $purchaseRequest->update($purchaseRequestData);

            if (isset($data['items'])) {

                foreach ($data['items'] as $item) {

                    $itemData = $this->purchaseRequestServices->purchaseRequestItemData($item);

                    $itemData['purchase_request_id'] = $purchaseRequest->id;

                    $purchaseRequestItem = PurchaseRequestItem::create($itemData);

                    if (isset($item['item_types'])) {

                        $purchaseRequestItem->spareParts()->attach($item['item_types']);
                    }
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            dd($e->getMessage());

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:purchase-requests.index'))->with(['message' => __('words.purchase-request-updated'), 'alert-type' => 'success']);

    }

    public function selectPart(Request $request)
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

            $branch_id = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $partMainTypes = $part->spareParts()->where('status', 1)
                ->where('branch_id', $branch_id)->whereNull('spare_part_id')->orderBy('id', 'ASC')->get();

            $partTypes = $this->purchaseRequestServices->getPartTypes($partMainTypes, $part->id);

            $partTypesView = view('admin.purchase_requests.part_types', compact( 'part','index', 'partTypes'))->render();

            $itemNotesView = view('admin.purchase_requests.item_notes_model', compact('index'))->render();

            $view = view('admin.purchase_requests.part_raw', compact('part', 'index', 'partTypes'))->render();

            return response()->json(['parts' => $view, 'partTypesView'=> $partTypesView,
                'index' => $index, 'itemNotesView'=> $itemNotesView], 200);

        } catch (Exception $e) {

            return response()->json('sorry, please try later', 400);
        }
    }

    public function destroy(PurchaseRequest $purchaseRequest)
    {
        try {

            DB::beginTransaction();

            $this->purchaseRequestServices->deletePurchaseRequest($purchaseRequest);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:purchase-requests.index'))->with(['message' => __('words.purchase-request-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!isset($request->ids)) {
            return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
        }
        try {
            DB::beginTransaction();
            $purchaseRequests = PurchaseRequest::whereIn('id', array_unique($request->ids))->get();
            foreach ($purchaseRequests as $purchaseRequest) {
                $this->purchaseRequestServices->deletePurchaseRequest($purchaseRequest);
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:purchase-requests.index'))->with(['message' => __('words.purchase-request-deleted'), 'alert-type' => 'success']);
    }

    public function approval (CreateRequest $request, PurchaseRequest $purchaseRequest) {

        try {

            DB::beginTransaction();

            $purchaseRequest->status = $request['status'];
            $purchaseRequest->description = $request['description'];
            $purchaseRequest->save();

            if (isset($request['items'])) {

                foreach ($request['items'] as $item) {

                    $purchaseRequestItem = PurchaseRequestItem::find($item['item_id']);

                    $purchaseRequestItem->approval_quantity = $item['approval_quantity'];

                    $purchaseRequestItem->save();
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return redirect()->back()->with(['message' => __('sorry, please try later'), 'alert-type' => 'error']);
        }

        return redirect(route('admin:purchase-requests.index'))->with(['message' => __('words.purchase-request-approved'), 'alert-type' => 'success']);
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.purchase_requests.datatables.options';
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
            ->addColumn('different_days', function ($item) use ($viewPath) {
                $withDifferentDays = true;
                return view($viewPath, compact('item', 'withDifferentDays'))->render();
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

    private function filter(Request $request, Builder $data)
    {
        return $data->where(function ($query) use ($request) {
            if ($request->filled('branch_id')) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->filled('number')) {
                $query->where('id', $request->number);
            }

            if ($request->filled('date_add_from') && $request->filled('date_add_to')){
                $query->whereBetween('date', [$request->date_add_from, $request->date_add_to]);
            }

            if ($request->filled('date_request_from') && $request->filled('date_request_to')){
                $query->whereBetween('date_from', [$request->date_request_from, $request->date_request_to]);
            }
        });
    }
}
