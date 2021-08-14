<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssetMaintenanceRequest;
use App\Models\Asset;
use App\Models\AssetMaintenance;
use App\Models\MaintenanceDetection;
use App\Models\MaintenanceDetectionType;
use App\Models\itemHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

class AssetMaintenanceController extends Controller
{
    public function index(Asset $asset, Request $request)
    {
        $items = $asset->assetMaintenances;
        $maintenanceDetections = MaintenanceDetection::all();
        $maintenanceDetectionsType = MaintenanceDetectionType::all();
//        $items = $this->filter($request, $items);
        if ($request->isDataTable) {
            return $this->dataTableColumns($items);
        } else {
            return view('admin.assets.assets_maintenance.index', [
                'items' => $items,
                'asset' => $asset,
                'maintenanceDetections' => $maintenanceDetections,
                'maintenanceDetectionsType' => $maintenanceDetectionsType,
                'js_columns' => AssetMaintenance::getJsDataTablesColumns(),
            ]);
        }
    }

    public function store(AssetMaintenanceRequest $request, Asset $asset): RedirectResponse
    {
        $data = $request->all();
        $data['asset_id'] = $asset->id;
        if ($request->asset_maintenance_id) {
            $assetMaintenance = AssetMaintenance::find($request->asset_employee_id);
            if ($assetMaintenance) {
                $assetMaintenance->update($data);
            }
            return redirect()->back()->with(['message' => __('words.asset-maintenance-updated'), 'alert-type' => 'success']);

        } else {
            AssetMaintenance::create($data);
            return redirect()->back()->with(['message' => __('words.asset-maintenance-created'), 'alert-type' => 'success']);
        }
    }

    public function destroy(itemHistory $employeeHistory): RedirectResponse
    {
        $employeeHistory->delete();
        return redirect()->back()->with(['message' => __('words.store-employee-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            itemHistory::whereIn('id', $request->ids)->delete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
    }

    private function filter(Request $request, Collection $employees): Collection
    {
        if ($request->has('employee_id') && $request['employee_id'] != 0) {
            $employees = $employees->where('employee_id', $request['employee_id']);
        }
        if ($request->has('start') && $request['start'] != '') {
            $employees = $employees->where('start', $request->start_date);
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
    private function dataTableColumns(Collection $items)
    {
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('name', function ($item) {
                return $item->name;
            })
            ->addColumn('maintenance_detection_type', function ($item) {
                return optional($item->maintenanceDetectionType)->name;
            })
            ->addColumn('maintenance_detection', function ($item) {
                return optional($item->maintenanceDetection)->name;
            })
            ->addColumn('number_of_km_hour', function ($item) {
                return $item->maintenance_type == 'km' ? $item->number_of_km_h . 'كيلو متر' : $item->number_of_km_h .'ساعة';
            })
            ->addColumn('period', function ($item) {
                return $item->period;
            })
            ->addColumn('status', function ($item) {
                $withStatus = true;
                return view('admin.assets.assets_maintenance.datatables-options', compact('item', 'withStatus'))->render();
            })
            ->addColumn('created_at', function ($item) {
                $withStartData = true;
                return view('admin.assets.assets_maintenance.datatables-options', compact('item', 'withStartData'))->render();
            })
            ->addColumn('updated_at', function ($item) {
                $withEndData = true;
                return view('admin.assets.assets_maintenance.datatables-options', compact('item', 'withEndData'))->render();
            })
            ->addColumn('action', function ($item) {
                $withActions = true;
                return view('admin.assets.assets_maintenance.datatables-options', compact('item', 'withActions'))->render();
            })->addColumn('options', function ($item) {
                $withOptions = true;
                return view('admin.assets.assets_maintenance.datatables-options', compact('item', 'withOptions'))->render();
            })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
    }
}
