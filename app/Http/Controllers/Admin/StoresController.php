<?php

namespace App\Http\Controllers\Admin;

use App\Filters\StoreFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\store\StoreRequest;
use App\Http\Requests\Admin\store\UpdateStoreRequest;
use App\Models\EmployeeData;
use App\Models\Store;
use App\Models\StoreEmployeeHistory;
use App\Services\StoreEmployeeHistoryService;
use App\Traits\LoggerError;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class StoresController extends Controller
{
    use LoggerError;

    /**
     * @var StoreFilter
     */
    protected $storeFilter;

    /**
     * @var StoreEmployeeHistoryService
     */
    protected $storeEmployeeHistoryService;

    public function __construct(
        StoreFilter $storeFilter,
        StoreEmployeeHistoryService $storeEmployeeHistoryService
    ) {
        $this->storeFilter = $storeFilter;
        $this->storeEmployeeHistoryService = $storeEmployeeHistoryService;
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_stores')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $stores = Store::query()->latest();
        if ($request->hasAny((new Store())->getFillable()) || $request->filled('store_id')) {
            $stores = $this->storeFilter->filter($request);
        }
        if ($request->isDataTable) {
            return DataTables::of($stores)->addIndexColumn()
                ->addColumn('branch_id', function ($store) {
                    return '<span class="text-danger">' . optional($store->branch)->name . '</span>';
                })
                ->addColumn('name', function ($store) {
                    return $store->name;
                })
                ->addColumn('active_total_of_employee', function ($store) {
                    return view('admin.stores.datatables.count_number_of_employee', compact('store'))->render();
                })
                ->addColumn('created_at', function ($store) {
                    return $store->created_at;
                })
                ->addColumn('updated_at', function ($store) {
                    return $store->updated_at;
                })
                ->addColumn('action', function ($store) {
                    return view('admin.stores.datatables.actions', compact('store'))->render();
                })->addColumn('options', function ($store) {
                    return view('admin.stores.datatables.option-delete-selected', compact('store'))->render();
                })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
        } else {
            return view('admin.stores.index', [
                'stores' => $stores->with('storeEmployeeHistories')->orderBy('id', 'desc')->get(),
                'js_columns' => Store::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create()
    {
        if (!auth()->user()->can('create_stores')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $employees = EmployeeData::all();
        return view('admin.stores.create', compact('employees'));
    }

    public function store(StoreRequest $request)
    {
        try {
            if (!auth()->user()->can('create_stores')) {
                return redirect()->back()->with(['authorization' => 'error']);
            }
            DB::beginTransaction();
            $data = $request->all();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
           Store::create($data);
            DB::commit();
                return redirect()->to('admin/stores')
                    ->with(['message' => __('words.store-created'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            DB::rollback();
            $this->logErrors($exception);
            return redirect()->to('admin/stores')
                ->with(['message' => __('some thing went wrong'), 'alert-type' => 'error']);
        }
    }

    public function edit(Store $store)
    {
        if (!auth()->user()->can('update_stores')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $employees = EmployeeData::all();
        return view('admin.stores.edit', compact('store', 'employees'));
    }

    public function update(UpdateStoreRequest $request, Store $store)
    {
        if (!auth()->user()->can('update_stores')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $data = $request->all();
        if (!authIsSuperAdmin()) {
            $data['branch_id'] = auth()->user()->branch_id;
        }
        $store->update($data);
        return redirect()->to('admin/stores')
            ->with(['message' => __('words.store-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Store $store)
    {
        if (!auth()->user()->can('delete_stores')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        if ($this->checkIfStoreHasRelatedData($store)) {
            return redirect()->to('admin/stores')
                ->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        }

        $store->storeEmployeeHistories()->delete();
        $store->forceDelete();
        return redirect()->to('admin/stores')
            ->with(['message' => __('words.store-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_stores')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $stores = Store::whereIn('id', $request->ids)->get();
            foreach ($stores as $store) {
                if ($this->checkIfStoreHasRelatedData($store)) {
                    return redirect()->to('admin/stores')
                        ->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
                }
            }
            foreach ($stores as $store) {
                $store->storeEmployeeHistories()->delete();
                $store->forceDelete();
            }
            return redirect()->to('admin/stores')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/stores')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function newEmployee(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contacts_count' => 'required|integer|min:0'
        ]);
        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }
        try {
            $index = $request['contacts_count'] + 1;
            $employeesQuery = EmployeeData::query();
            if ($request->has('branchId') && $request->branchId !== null) {
                $employeesQuery = $employeesQuery->where('branch_id', $request->branchId);
            }
            $employees = $employeesQuery->get();
            $view = view('admin.stores.parts.ajax_form_new_employee', compact('index', 'employees'))->render();
        } catch (Exception $exception) {
            return response()->json('sorry, please try later', 400);
        }
        return response()->json([
            'view' => $view,
            'index' => $index
        ], 200);
    }

    public function getEmployeeBYId(Request $request)
    {
        try {
            if ($request->emp_id && $request->emp_id != null) {
                $employee = EmployeeData::find($request->emp_id);
                if ($employee) {
                    return response()->json([
                        'phone1' => $employee->phone1,
                        'phone2' => $employee->phone2,
                    ], 200);
                }
            }
        } catch (Exception $exception) {
            return response()->json('sorry, please try later', 400);
        }
    }

    public function checkIfStoreHasRelatedData(Store $store): bool
    {
        if ($store->parts()->exists()) {
            return true;
        }

        if ($store->openingBalanceItem()->exists()) {
            return true;
        }
        if ($store->damagedStockItem()->exists()) {
            return true;
        }
        if ($store->settlementItem()->exists()) {
            return true;
        }
        if ($store->concessionItem()->exists()) {
            return true;
        }
        if ($store->storeEmployeeHistories()->exists()) {
            return true;
        }
        return false;
    }

    public function show(Request $request)
    {
        try {
           if ($store = Store::find($request->storeId)) {
               $storeView = view('admin.stores.parts.show_data_by_ajax', compact('store'))->render();
               return response()->json([
                  'data' => $storeView,
               ]);
           }
            $storeView = view('admin.stores.parts.show_data_by_ajax')->render();
            return response()->json([
                'data' => $storeView
            ]);
        } catch (Exception $exception) {
            $storeView = view('admin.stores.parts.show_data_by_ajax')->render();
            return response()->json([
                'data' => $storeView
            ]);
        }
    }

    public function destroyEmployeeHistory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'storeEmployeeHistoryId' => 'required|integer|exists:store_employee_histories,id'
        ]);
        if ($validator->failed()) {
            return response()->json($validator->errors()->first(), 400);
        }
        try {
            $storeEmployeeHistory = StoreEmployeeHistory::find($request['storeEmployeeHistoryId']);
            if (!$storeEmployeeHistory) {
                return response()->json('sorry, store Employee History not valid', 400);
            }
            $storeEmployeeHistory->delete();
        } catch (Exception $e) {
            return response()->json('sorry, please try later', 400);
        }
        return response()->json(['id' => $request['storeEmployeeHistoryId']], 200);
    }

    public function getEmployeeByBranchId(Request $request): JsonResponse
    {
        if (is_null($request->branchId)) {
            return response()->json(__('please select valid Branch'), 400);
        }
        if ($employees = EmployeeData::where('branch_id', $request->branchId)->get()) {
            $employeeData = view('admin.stores.parts.employees_options', compact('employees'))->render();
            return response()->json([
                'data' => $employeeData,
            ]);
        }
    }
}
