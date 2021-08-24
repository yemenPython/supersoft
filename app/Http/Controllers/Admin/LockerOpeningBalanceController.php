<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LockerOpeningBalanceRequest;
use App\Http\Requests\UpdateLockerOpeningBalanceRequest;
use App\Models\AssetReplacement;
use App\Models\Branch;
use App\Models\Locker;
use App\Models\LockerOpeningBalance;
use App\Models\LockerOpeningBalanceItem;
use App\Traits\LoggerError;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class LockerOpeningBalanceController extends Controller
{
    use LoggerError;

    public function index(Request $request)
    {
        $items = LockerOpeningBalance::query();
        if ($request->filled('filter')) {
            $items = $this->filter($request, $items);
        }
        if ($request->isDataTable) {
            return $this->dataTableColumns($items);
        } else {
            return view('admin.locker_opening_balance.index', [
                'data' => $items,
                'js_columns' => LockerOpeningBalance::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create(Request $request): View
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;
        $lockers = Locker::where('branch_id', $branch_id)->get();
        $branches = Branch::all();
        $lastNumber = LockerOpeningBalance::where('branch_id', $branch_id)->latest()->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        return view('admin.locker_opening_balance.create',
            compact('lockers', 'branches', 'number'));
    }

    public function store(LockerOpeningBalanceRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $data['user_id'] = Auth::id();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $lockerOpeningBalance = LockerOpeningBalance::create($data);
            if ($lockerOpeningBalance) {
                foreach ($request->items as $item) {
                    LockerOpeningBalanceItem::create([
                        'locker_opening_balance_id' => $lockerOpeningBalance->id,
                        'locker_id' => $item['locker_id'],
                        'current_balance' => $item['current_balance'],
                        'added_balance' => $item['added_balance'],
                        'total' =>  $item['current_balance'] + $item['added_balance'],
                    ]);
                }
            }
            return redirect()->to('admin/lockers_opening_balance')
                ->with(['message' => __('words.lockers_opening_balance_created'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function edit(Request $request, int $id)
    {
        $lockerOpeningBalance = LockerOpeningBalance::findOrFail($id);
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : $lockerOpeningBalance->branch_id;
        $lockers = Locker::where('branch_id', $branch_id)->get();
        $branches = Branch::all();
        return view('admin.locker_opening_balance.edit',
            compact('lockers', 'branches', 'lockerOpeningBalance'));
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $assetReplacement = AssetReplacement::findOrFail($id);
        $isOnlyShow = $request->show;
        if ($isOnlyShow) {
            $view = view('admin.assets_replacements.onlyShow', compact('assetReplacement'))->render();

        } else {
            $view = view('admin.assets_replacements.show', compact('assetReplacement'))->render();
        }
        return response()->json([
            'view' => $view
        ]);

    }

    public function update(UpdateLockerOpeningBalanceRequest $request, int $id): RedirectResponse
    {
        try {
            $lockerOpeningBalance = LockerOpeningBalance::findOrFail($id);
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $assetExpenseUpdated = $lockerOpeningBalance->update($data);
            $lockerOpeningBalance->items()->delete();
            if ($assetExpenseUpdated) {
                foreach ($request->items as $item) {
                    LockerOpeningBalanceItem::create([
                        'locker_opening_balance_id' => $lockerOpeningBalance->id,
                        'locker_id' => $item['locker_id'],
                        'current_balance' => $item['current_balance'],
                        'added_balance' => $item['added_balance'],
                        'total' =>  $item['current_balance'] + $item['added_balance'],
                    ]);
                }
            }
            return redirect()->to('admin/lockers_opening_balance')
                ->with(['message' => __('words.lockers_opening_balance_updated'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = LockerOpeningBalance::findOrFail($id);
        $item->items()->delete();
        $item->delete();
        return redirect()->to('admin/lockers_opening_balance')
            ->with(['message' => __('words.lockers_opening_balance_deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            $items = LockerOpeningBalance::whereIn('id', $request->ids)->get();
            foreach ($items as $item) {
                $item->items()->delete();
                $item->delete();
            }
            return redirect()->to('admin/lockers_opening_balance')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/lockers_opening_balance')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getLockers(Request $request): JsonResponse
    {
        if (is_null($request->asset_id)) {
            return response()->json(__('please select valid Asset'), 400);
        }
        if (authIsSuperAdmin() && is_null($request->branch_id)) {
            return response()->json(__('please select valid branch'), 400);
        }
        $index = $request->index;
        $locker = Locker::find($request->asset_id);
        $view = view('admin.locker_opening_balance.row', compact('locker', 'index')
        )->render();
        return response()->json([
            'items' => $view
        ]);
    }

    private function dataTableColumns(Builder $items)
    {
        $view = 'admin.locker_opening_balance.dataTable.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn( 'branch_id', function ($item) use ($view) {
                $withBranch = true;
                return view($view, compact('item', 'withBranch'))->render();
            })
            ->addColumn( 'number', function ($item) use ($view) {
                return $item->number;
            })
            ->addColumn('total', function ($item) use ($view) {
                $withTotal = true;
                return view($view, compact('item', 'withTotal'))->render();
            })
            ->addColumn('status', function ($item) use ($view) {
                $withStatus = true;
                return view($view, compact('item', 'withStatus'))->render();
            })
            ->addColumn('created_at', function ($item) use ($view) {
                return $item->created_at->format('y-m-d h:i:s A');
            })
            ->addColumn('updated_at', function ($item) use ($view) {
                return $item->updated_at->format('y-m-d h:i:s A');
            })
            ->addColumn('action', function ($item) use ($view) {
                $withActions = true;
                return view($view, compact('item', 'withActions'))->render();
            })->addColumn('options', function ($item) use ($view) {
                $withOptions = true;
                return view($view, compact('item', 'withOptions'))->render();
            })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
    }

    private function filter(Request $request, Builder $items): Builder
    {
        if ($request->filled('branchId')) {
            $items = $items->where('branch_id', $request->branchId);
        }

        if ($request->filled('number')) {
            $items = $items->where('id', $request->number);
        }

        if ($request->filled('locker_id')) {
            $items = $items->whereHas('items', function ($q) use ($request) {
                $q->where('locker_id', $request->locker_id);
            }) ;
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $items = $items->whereBetween('date', [$request->date_from, $request->date_to]);
        }

        if ($request->filled('status')) {
            $items = $items->where('status', $request->status);
        }
        return $items;
    }
}
