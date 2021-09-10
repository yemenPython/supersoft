<?php

namespace App\Http\Controllers\Admin;

use App\Enum\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\LockerOpeningBalanceRequest;
use App\Http\Requests\UpdateLockerOpeningBalanceRequest;
use App\Models\AssetReplacement;
use App\Models\Branch;
use App\Models\Currency;
use App\Models\Locker;
use App\Models\LockerOpeningBalance;
use App\Models\LockerOpeningBalanceItem;
use App\Models\Setting;
use App\Traits\LoggerError;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;

class LockerOpeningBalanceController extends Controller
{
    use LoggerError;

    public function index(Request $request)
    {
        $items = LockerOpeningBalance::query()->latest();
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
        $setting = Setting::first();
        return view('admin.locker_opening_balance.create',
            compact('lockers', 'branches', 'number', 'setting'));
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
                    $currencyId =  isset($item['currency_id']) ? $item['currency_id'] : null;
                    $currency = Currency::find($currencyId);
                    $conversion_factor = $currency && $currency->conversion_factor ? $currency->conversion_factor : 1;
                    $lockerOpeningBItem = LockerOpeningBalanceItem::create([
                        'locker_opening_balance_id' => $lockerOpeningBalance->id,
                        'locker_id' => $item['locker_id'],
                        'current_balance' => $item['current_balance'],
                        'added_balance' => $item['added_balance'] * $conversion_factor,
                        'total' =>  $item['current_balance'] +( $item['added_balance'] * $conversion_factor),
                        'currency_id' =>  $currencyId,
                    ]);
                    $this->updateLocker($lockerOpeningBItem, $request->status, $currency);
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
        if ($lockerOpeningBalance->status == Status::Accepted || $lockerOpeningBalance->status == Status::Rejected) {
            return back()->with(['message' => __('words.edit_not_allowed'), 'alert-type' => 'error']);
        }
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : $lockerOpeningBalance->branch_id;
        $lockers = Locker::where('branch_id', $branch_id)->get();
        $branches = Branch::all();
        $setting = Setting::first();
        $currencies = Currency::all();
        return view('admin.locker_opening_balance.edit',
            compact('lockers', 'branches', 'lockerOpeningBalance', 'setting', 'currencies'));
    }

    public function show(int $id): JsonResponse
    {
        $item = LockerOpeningBalance::findOrFail($id);
        $view = view('admin.locker_opening_balance.show', compact('item'))->render();
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
            $idsWillDelete = array_diff($lockerOpeningBalance->items->pluck('id')->toArray(),
                array_column($request->items, 'id'));
            LockerOpeningBalanceItem::whereIn('id', $idsWillDelete)->delete();
            if ($assetExpenseUpdated) {
                foreach ($request->items as $item) {
                    if (isset($item['id'])) {
                        $oldLockerOpeningBalance = LockerOpeningBalanceItem::find($item['id']);
                        if ($oldLockerOpeningBalance) {
                            $currencyId =  isset($item['currency_id']) ? $item['currency_id'] : null;
                            $currency = Currency::find($currencyId);
                            $conversion_factor = $currency && $currency->conversion_factor ? $currency->conversion_factor : 1;
                            $oldLockerOpeningBalance->update([
                                'current_balance' => $item['current_balance'],
                                'added_balance' => $item['added_balance'] * $conversion_factor,
                                'total' =>  $item['current_balance'] +( $item['added_balance'] * $conversion_factor),
                                'currency_id' =>  $currencyId,
                            ]);
                            $this->updateLocker($oldLockerOpeningBalance, $request->status, $currency);
                        }
                    } else {
                        $currencyId =  isset($item['currency_id']) ? $item['currency_id'] : null;
                        $lockerOpeningBalance =LockerOpeningBalanceItem::create([
                            'locker_opening_balance_id' => $lockerOpeningBalance->id,
                            'locker_id' => $item['locker_id'],
                            'current_balance' => $item['current_balance'],
                            'added_balance' => $item['added_balance'],
                            'total' =>  $item['current_balance'] + $item['added_balance'],
                            'currency_id' =>  $currencyId,
                        ]);
                        $this->updateLocker($lockerOpeningBalance, $request->status,  $currency);
                    }
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
        if ($item->status == Status::Accepted) {
            return redirect()->to('admin/lockers_opening_balance')
                ->with(['message' => __('words.can_not_delete_this_operation_cause_it_has_been_accepted'), 'alert-type' => 'error']);
        }
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
                if ($item->status == Status::Accepted) {
                    return redirect()->to('admin/lockers_opening_balance')
                        ->with(['message' => __('words.can_not_delete_this_operation_cause_it_has_been_accepted'), 'alert-type' => 'error']);
                }
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
        if (is_null($request->locker_id)) {
            return response()->json(__('please select valid Asset'), 400);
        }
        if (authIsSuperAdmin() && is_null($request->branch_id)) {
            return response()->json(__('please select valid branch'), 400);
        }
        $index = $request->index;
        $locker = Locker::find($request->locker_id);
        $currencies = Currency::all();
        $setting = Setting::first();
        $view = view('admin.locker_opening_balance.row', compact('locker', 'index', 'currencies', 'setting')
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

    private function updateLocker(LockerOpeningBalanceItem $lockerOpeningBalanceItem, string $status, Currency $currency = null)
    {
        if ($status == Status::Accepted) {
            $locker = Locker::find($lockerOpeningBalanceItem->locker_id);
            $locker->update([
               'balance' => $locker->balance +  $lockerOpeningBalanceItem->added_balance
            ]);
        }
    }
}
