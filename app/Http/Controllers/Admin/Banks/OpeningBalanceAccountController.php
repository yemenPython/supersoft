<?php

namespace App\Http\Controllers\Admin\Banks;

use App\Enum\Status;
use App\Models\Banks\BankAccount;
use App\Models\Banks\TypeBankAccount;
use App\Models\Branch;
use App\Models\Currency;
use App\Http\Controllers\Controller;
use App\Models\Locker;
use App\Models\LockerOpeningBalance;
use App\Models\LockerOpeningBalanceItem;
use App\Models\Setting;
use App\Traits\LoggerError;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Banks\OpeningBalanceAccount;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Banks\OpeningBalanceAccountItem;
use App\Http\Requests\LockerOpeningBalanceRequest;
use App\Http\Requests\OpeningBalanceAccountRequest;
use App\Http\Requests\UpdateLockerOpeningBalanceRequest;

class OpeningBalanceAccountController extends Controller
{
    use LoggerError;

    protected $viewPath= 'admin.banks.opening_balance_accounts.';

    public function index(Request $request)
    {
        $items = OpeningBalanceAccount::query()->latest();
        if ($request->filled('filter')) {
            $items = $this->filter($request, $items);
        }
        if ($request->isDataTable) {
            return $this->dataTableColumns($items);
        } else {
            return view($this->viewPath.'index', [
                'data' => $items,
                'js_columns' => OpeningBalanceAccount::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create(Request $request): View
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;
        $branches = Branch::all();
        $lastNumber = OpeningBalanceAccount::where('branch_id', $branch_id)->latest()->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        $mainTypes = TypeBankAccount::getMainTypes($branch_id);
        $subTypes = TypeBankAccount::where('parent_id', '!=', null)->where('branch_id', $branch_id)->get();
        $bankAccounts = BankAccount::where('branch_id', $branch_id)->get();
        return view($this->viewPath.'create',
            compact('subTypes', 'branches', 'number', 'mainTypes', 'bankAccounts'));
    }

    public function store(OpeningBalanceAccountRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            $data['user_id'] = Auth::id();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $openingBalanceAccount = OpeningBalanceAccount::create($data);
            if ($openingBalanceAccount) {
                foreach ($request->items as $item) {
                    $openingBalanceAccountItem = OpeningBalanceAccountItem::create([
                        'opening_balance_account_id' => $openingBalanceAccount->id,
                        'bank_account_id' => $item['bank_account_id'],
                        'total' => $item['added_balance'],
                    ]);
                    $this->updateBalanceInBankAccount($openingBalanceAccountItem, $request->status);
                }
            }
            return redirect()->to('admin/banks/opening_balance_accounts')
                ->with(['message' => __('words.bank_accounts_opening_balance_created'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function edit(Request $request, int $id)
    {
        $openingBalanceAccount = OpeningBalanceAccount::findOrFail($id);
        if ($openingBalanceAccount->status == Status::Accepted || $openingBalanceAccount->status == Status::Rejected) {
            return back()->with(['message' => __('words.edit_not_allowed'), 'alert-type' => 'error']);
        }
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : $openingBalanceAccount->branch_id;
        $lastNumber = OpeningBalanceAccount::where('branch_id', $branch_id)->latest()->first();
        $number = $lastNumber ? $lastNumber->number + 1 : 1;
        $mainTypes = TypeBankAccount::getMainTypes($branch_id);
        $subTypes = TypeBankAccount::where('parent_id', '!=', null)->where('branch_id', $branch_id)->get();
        $bankAccounts = BankAccount::where('branch_id', $branch_id)->get();
        $branches = Branch::all();
        return view($this->viewPath.'edit',
            compact('openingBalanceAccount', 'number', 'mainTypes', 'subTypes', 'bankAccounts', 'branches'));
    }

    public function show(int $id): JsonResponse
    {
        $item = OpeningBalanceAccount::findOrFail($id);
        $view = view('admin.banks.opening_balance_accounts.show', compact('item'))->render();
        return response()->json([
            'view' => $view
        ]);
    }

    public function update(OpeningBalanceAccountRequest $request, int $id): RedirectResponse
    {
        try {
            $openingBalanceAccount = OpeningBalanceAccount::findOrFail($id);
            if ($openingBalanceAccount->status == Status::Accepted || $openingBalanceAccount->status == Status::Rejected) {
                return back()->with(['message' => __('words.edit_not_allowed'), 'alert-type' => 'error']);
            }
            $data = $request->all();
            $data['user_id'] = Auth::id();
            $assetExpenseUpdated = $openingBalanceAccount->update($data);
            $idsWillDelete = array_diff($openingBalanceAccount->items->pluck('id')->toArray(),
                array_column($request->items, 'id'));
            OpeningBalanceAccountItem::whereIn('id', $idsWillDelete)->delete();
            if ($assetExpenseUpdated) {
                foreach ($request->items as $item) {
                    if (isset($item['id'])) {
                        $oldLockerOpeningBalance = OpeningBalanceAccountItem::find($item['id']);
                        if ($oldLockerOpeningBalance) {
                            $oldLockerOpeningBalance->update([
                                'opening_balance_account_id' => $openingBalanceAccount->id,
                                'bank_account_id' => $item['bank_account_id'],
                                'total' => $item['added_balance'],
                            ]);
                            $this->updateBalanceInBankAccount($oldLockerOpeningBalance, $request->status);
                        }
                    } else {
                        $openingBalanceAccountItem =OpeningBalanceAccountItem::create([
                            'opening_balance_account_id' => $openingBalanceAccount->id,
                            'bank_account_id' => $item['bank_account_id'],
                            'total' => $item['added_balance'],
                        ]);
                        $this->updateBalanceInBankAccount($openingBalanceAccountItem, $request->status);
                    }
                }
            }
            return redirect()->to('admin/banks/opening_balance_accounts')
                ->with(['message' => __('words.lockers_opening_balance_updated'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $item = OpeningBalanceAccount::findOrFail($id);
        if ($item->status == Status::Accepted) {
            return redirect()->to('admin/banks/opening_balance_accounts')
                ->with(['message' => __('words.can_not_delete_this_operation_cause_it_has_been_accepted'), 'alert-type' => 'error']);
        }
        $item->items()->delete();
        $item->delete();
        return redirect()->to('admin/banks/opening_balance_accounts')
            ->with(['message' => __('words.lockers_opening_balance_deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            $items = OpeningBalanceAccount::whereIn('id', $request->ids)->get();
            foreach ($items as $item) {
                if ($item->status == Status::Accepted) {
                    return redirect()->to('admin/banks/opening_balance_accounts')
                        ->with(['message' => __('words.can_not_delete_this_operation_cause_it_has_been_accepted'), 'alert-type' => 'error']);
                }
                $item->items()->delete();
                $item->delete();
            }
            return redirect()->to('admin/banks/opening_balance_accounts')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/banks/opening_balance_accounts')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getBankDataById(Request $request): JsonResponse
    {
        if (is_null($request->bank_account_id)) {
            return response()->json(__('please select valid Asset'), 400);
        }
        if (authIsSuperAdmin() && is_null($request->branch_id)) {
            return response()->json(__('please select valid branch'), 400);
        }
        $index = $request->index;
        $bankAccount = BankAccount::find($request->bank_account_id);
        $view = view($this->viewPath.'row', compact('bankAccount', 'index')
        )->render();
        return response()->json([
            'items' => $view
        ]);
    }

    private function dataTableColumns(Builder $items)
    {
        $view = 'admin.banks.opening_balance_accounts.dataTable.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn( 'bank_account', function ($item) use ($view) {
                $bank_account = true;
                return view($view, compact('item', 'bank_account'))->render();
            })
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

        if ($request->filled('bank_data_id')) {
            $items = $items->whereHas('items', function ($q) use ($request) {
                $q->whereHas('bankAccount', function ($qq) use ($request) {
                    $qq->where('bank_data_id', $request->bank_data_id);
                });
            }) ;
        }

        if ($request->filled('customer_number')) {
            $items = $items->whereHas('items', function ($q) use ($request) {
                $q->where('bank_account_id', $request->customer_number);
            }) ;
        }

        if ($request->filled('iban')) {
            $items = $items->whereHas('items', function ($q) use ($request) {
                $q->where('bank_account_id', $request->iban);
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

    public function getBanksAccount(Request $request): JsonResponse
    {
        $branchId = $request->branch_id == "undefined" ?  auth()->user()->branch_id : $request->branch_id;
        $mainTypeBankId = $request->filled('main_bank_account_type_id') ?  $request->main_bank_account_type_id : null;
        $subTypeBankId = $request->filled('sub_bank_account_type_id') ? $request->sub_bank_account_type_id : null;
        $bankAccounts = BankAccount::where('branch_id', $branchId);
        if ($mainTypeBankId) {
            $bankAccounts = $bankAccounts->where('main_type_bank_account_id', $mainTypeBankId);
        }
        if ($subTypeBankId) {
            $bankAccounts = $bankAccounts->where('sub_type_bank_account_id', $subTypeBankId);
        }
        return response()->json([
           'data' => view($this->viewPath.'ajax.options-bank-accounts', [
               'bankAccounts' => $bankAccounts->get()
           ])->render()
        ]);
    }

    private function updateBalanceInBankAccount(OpeningBalanceAccountItem $openingBalanceAccountItem, string $status)
    {
        if ($status == Status::Accepted) {
            logger('>>>>>>>>>>>>>>>', [
                '$status' => $status
            ]);
            $bankAccount = BankAccount::find($openingBalanceAccountItem->bank_account_id);
            if ($bankAccount) {
                logger('>>>>>>>>>>>>>>>', [
                    'balance' => $openingBalanceAccountItem->total
                ]);
                $bankAccount->update([
                    'balance' => $bankAccount->balance +  $openingBalanceAccountItem->total
                ]);
            }
        }
    }
}

