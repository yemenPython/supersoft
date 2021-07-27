<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\store\StoreEmployeeRequest;
use App\Models\EmployeeData;
use App\Models\Store;
use App\Models\StoreEmployeeHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Throwable;
use Yajra\DataTables\DataTables;

class StoreEmployeeHistoryController extends Controller
{
    public function index(Store $store, Request $request)
    {
        $employees = $store->storeEmployeeHistories;
        $employees = $this->filter($request, $employees);
        if ($request->isDataTable) {
          return $this->dataTableColumns($employees);
        } else {
            $employeesData = EmployeeData::where("branch_id", $store->branch_id)->select(['id', 'name_ar', 'name_en'])->get();
            return view('admin.stores.employees.index', [
                'employeesData' => $employeesData,
                'employees' => $employees,
                'store' => $store,
                'js_columns' => StoreEmployeeHistory::getJsDataTablesColumns(),
            ]);
        }
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        if ($request->asset_employee_id) {
            $getEmployee = StoreEmployeeHistory::find($request->asset_employee_id);
            if ($getEmployee) {
                StoreEmployeeHistory::where( "id", $request->asset_employee_id )->update( [
                    "employee_id" => $request->employee_id,
                    "store_id" => $request->store_id,
                    "start" => $request->start_date,
                    "end" => $request->end_date,
                    'status' => $request->status
                ] );
            }
            return redirect()->back()->with( ['message' => __( 'words.store-employee-updated' ), 'alert-type' => 'success'] );

        } else {
            StoreEmployeeHistory::create( [
                "employee_id" => $request->employee_id,
                "store_id" => $request->store_id,
                "start" => $request->start_date,
                "end" => $request->end_date,
                'status' => $request->status
            ] );
            return redirect()->back()->with( ['message' => __( 'words.store-employee-created' ), 'alert-type' => 'success'] );
        }
    }

    public function destroy(StoreEmployeeHistory $employeeHistory): RedirectResponse
    {
        $employeeHistory->delete();
        return redirect()->back()->with( ['message' => __( 'words.store-employee-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset( $request->ids )) {
             StoreEmployeeHistory::whereIn( 'id', $request->ids )->delete();
            return redirect()->back()
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
    }

    private function filter(Request $request, Collection $employees): Collection
    {
        if ($request->has('employee_id') && $request['employee_id'] != 0) {
            $employees = $employees->where('employee_id', $request['employee_id']);
        }
        if ($request->has('start') && $request['start'] != '') {
            $employees =$employees->where('start', $request->start_date);
        }
        if ($request->has('end') && $request['end'] != '') {
            $employees = $employees->where('end', $request->end_date);
        }
        if ($request->has('active') && $request['active'] != '') {
            $employees = $employees->where('status', '1');
        }
        if ($request->has('inactive') && $request['inactive'] != '') {
            $employees = $employees->where('status', '0');
        }
        return $employees;
    }

    /**
     * @param Collection $employees
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Collection $employees)
    {
        return DataTables::of($employees)->addIndexColumn()
            ->addColumn('status', function ($storeEmployee) {
                $withStatus = true;
                return view('admin.stores.employees.datatables-options', compact('storeEmployee', 'withStatus'))->render();
            })
            ->addColumn('name', function ($storeEmployee) {
                return optional($storeEmployee->employee)->name;
            })
            ->addColumn('phone1', function ($storeEmployee) {
                return optional($storeEmployee->employee)->phone1;
            })
            ->addColumn('start', function ($storeEmployee) {
                $withStartData = true;
                return view('admin.stores.employees.datatables-options', compact('storeEmployee', 'withStartData'))->render();
            })
            ->addColumn('end', function ($storeEmployee) {
                $withEndData = true;
                return view('admin.stores.employees.datatables-options', compact('storeEmployee', 'withEndData'))->render();
            })
            ->addColumn('action', function ($storeEmployee) {
                $withActions = true;
                return view('admin.stores.employees.datatables-options', compact('storeEmployee', 'withActions'))->render();
            })->addColumn('options', function ($storeEmployee) {
                $withOptions = true;
                return view('admin.stores.employees.datatables-options', compact('storeEmployee', 'withOptions'))->render();
            })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
    }
}
