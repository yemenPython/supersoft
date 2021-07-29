<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetEmployeeRequest;
use App\Models\AssetEmployee;
use App\Models\Asset;
use App\Models\EmployeeData;
use App\Models\Settlement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\DataTables;

class AssetsEmployeesController extends Controller
{
    public function index(asset $asset, Request $request)
    {
        $assetsEmployees = AssetEmployee::where("asset_id", $asset->id)->orderBy('id', 'desc');

        if ($request->has('employee_id') && $request['employee_id'] != 0)
            $assetsEmployees->where('employee_id', $request['employee_id']);

        if ($request->has('start_date') && $request['start_date'] != '')
            $assetsEmployees->where('start_date', $request->start_date);

        if ($request->has('end_date') && $request['end_date'] != '')
            $assetsEmployees->where('end_date', $request->end_date);

        if ($request->has('active') && $request['active'] != '')
            $assetsEmployees->where('status', '1');
        if ($request->has('inactive') && $request['inactive'] != '')
            $assetsEmployees->where('status', '0');
        $employees = EmployeeData::where("branch_id", $asset->branch_id)->select(['id', 'name_ar', 'name_en'])->get();
        if ($request->isDataTable) {
            try {
                return $this->dataTableColumns($assetsEmployees);
            } catch (Throwable $e) {
            }
        } else {
            return view('admin.assetsEmployees.index', [
                'asset' => $asset,
                'assetsEmployees' => $assetsEmployees,
                'employees' => $employees,
                'js_columns' => AssetEmployee::getJsDataTablesColumns(),
            ]);
        }
    }

    public function store(AssetEmployeeRequest $request)
    {

        if ($request->asset_employee_id) {

            $getEmployee = AssetEmployee::find($request->asset_employee_id);
            if ($getEmployee) {
                AssetEmployee::where("id", $request->asset_employee_id)->update([
                    "employee_id" => $request->employee_id,
                    "asset_id" => $request->asset_id,
                    "start_date" => $request->start_date,
                    "end_date" => $request->end_date,
                    'status' => $request->status
                ]);
            }
            return redirect()->to('admin/assets-employees/' . $request->asset_id)
                ->with(['message' => __('words.asset-employee-updated'), 'alert-type' => 'success']);

        } else {
            AssetEmployee::create([
                "employee_id" => $request->employee_id,
                "asset_id" => $request->asset_id,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                'status' => $request->status
            ]);
            return redirect()->to('admin/assets-employees/' . $request->asset_id)
                ->with(['message' => __('words.asset-employee-created'), 'alert-type' => 'success']);
        }
    }


    public function destroy(AssetEmployee $assetEmployee)
    {
        $assetEmployee->delete();
        return redirect()->to('admin/assets-employees/' . $assetEmployee->asset_id)
            ->with(['message' => __('words.asset-group-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {
            $assetsEmployees = AssetEmployee::whereIn('id', $request->ids)->delete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
    }

    public function getAssetsEmployeePhone(Request $request)
    {
        return ['status' => true, 'phone' => EmployeeData::where('id', $request->employee_id)->value('phone1')];

    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.assetsEmployees.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('status', function ($item) use ($viewPath) {
                $withStatus = true;
                return view($viewPath, compact('item', 'withStatus'))->render();
            })
            ->addColumn('name', function ($item) use ($viewPath) {
                return  $item->employee->name;
            })
            ->addColumn('phone', function ($item) use ($viewPath) {
                return  $item->employee->phone1;
            })
            ->addColumn('start_date', function ($item) {
                return $item->start_date;
            })
            ->addColumn('end_date', function ($item) {
                return $item->end_date;
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
