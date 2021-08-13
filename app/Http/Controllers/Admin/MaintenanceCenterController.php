<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CreateMaintenanceCenterRequest;
use App\Models\Branch;
use App\Models\MaintenanceCenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Throwable;
use Yajra\DataTables\DataTables;

class MaintenanceCenterController extends Controller
{
    const VIEW = 'admin.maintenance_centers.';

    /**
     * @var Collection
     */
    protected $branches;

    public function __construct()
    {
        $this->branches = Branch::all()->pluck('name', 'id');
    }

    public function index(Request $request)
    {
        $items = MaintenanceCenter::query();
        $items = $this->filter($request, $items);
        if ($request->isDataTable) {
            return $this->dataTableColumns($items);
        } else {
            return view(self::VIEW . 'index', [
                'items' => $items,
                'branches' => $this->branches,
                'js_columns' => MaintenanceCenter::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create(): View
    {
        return view(self::VIEW . 'create', [
            'branches' => $this->branches,
        ]);
    }

    public function store(CreateMaintenanceCenterRequest $request): RedirectResponse
    {
        MaintenanceCenter::create($request->all());
        return redirect()->route('admin:maintenance_centers.index')->with(['message' => __('words.maintenance_centers_created_successfully'), 'alert-type' => 'success']);
    }

    public function Edit(MaintenanceCenter $maintenanceCenter): View
    {
        return view(self::VIEW . 'edit', [
            'branches' => $this->branches,
            'item' => $maintenanceCenter,
        ]);
    }

    public function update(CreateMaintenanceCenterRequest $request, MaintenanceCenter $maintenanceCenter): RedirectResponse
    {
        $maintenanceCenter->update($request->all());
        return redirect()->route('admin:maintenance_centers.index')->with(['message' => __('words.maintenance_centers_updated_successfully'), 'alert-type' => 'success']);
    }

    public function destroy(MaintenanceCenter $maintenanceCenter): RedirectResponse
    {
        $maintenanceCenter->delete();
        return redirect()->back()->with( ['message' => __( 'words.item-deleted' ), 'alert-type' => 'success'] );
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset( $request->ids )) {
            MaintenanceCenter::whereIn('id', array_unique( $request->ids))->delete();
            return redirect()->back()
                ->with( ['message' => __( 'words.selected-row-deleted' ), 'alert-type' => 'success'] );
        }
        return redirect()->back()
            ->with( ['message' => __( 'words.select-one-least' ), 'alert-type' => 'error'] );
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = self::VIEW . '.options-datatable.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('name', function ($item) {
                return $item->name;
            })
            ->addColumn('branch', function ($item) use ($viewPath) {
                $branch = true;
                return view($viewPath, compact('item', 'branch'))->render();
            })
            ->addColumn('country', function ($item) use ($viewPath) {
                return optional($item->country)->name;
            })
            ->addColumn('city', function ($item) use ($viewPath) {
                return optional($item->city)->name;
            })
            ->addColumn('phone', function ($item) {
                return $item->phone_1 . '|' . $item->phone_2;
            })
            ->addColumn('email', function ($item) use ($viewPath) {
                return $item->email;
            })
            ->addColumn('commercial_number', function ($item) use ($viewPath) {
                return $item->commercial_number;
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

    private function filter(Request $request, Builder $data): Builder
    {
        return $data->where(function ($query) use ($request) {
            if ($request->filled('branchId')) {
                $query->where('branch_id', $request->branchId);
            }

            if ($request->filled('maintenance_id')) {
                $query->where('id', $request->maintenance_id);
            }

            if ($request->filled('phone')) {
                $query->where('id', $request->phone);
            }

            if ($request->filled('commercial_number')) {
                $query->where('id', $request->commercial_number);
            }

            if ($request->filled('active')) {
                $query->where('status', 1);
            }

            if ($request->filled('inactive')) {
                $query->where('status', 0);
            }

            if ($request->filled('inactive') && $request->filled('active')) {
                $query->whereIn('status', [0,1]);
            }
        });
    }
}
