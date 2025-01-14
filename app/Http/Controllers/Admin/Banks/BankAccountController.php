<?php

namespace App\Http\Controllers\Admin\Banks;

use Throwable;
use JsValidator;
use Exception;
use App\Models\Currency;
use App\Traits\LoggerError;
use Illuminate\Http\Request;
use App\Models\Banks\BankData;
use Yajra\DataTables\DataTables;
use App\Models\Banks\BankAccount;
use App\Models\Banks\BranchProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Banks\TypeBankAccount;
use App\Http\Requests\BankAccountRequest;
use Illuminate\Database\Eloquent\Builder;

class BankAccountController extends Controller
{
    use LoggerError;

    const ViewPath = 'admin.banks.banks_accounts.';

    public function index(Request $request)
    {
        $items = BankAccount::latest();
        $mainTypes = TypeBankAccount::getMainTypes();
        $subTypes = TypeBankAccount::where('parent_id', '!=', null)->get();
        $currencies = Currency::all();
        if ($request->filled('filter')) {
            $items = $this->filter($request, $items);
        }
        if ($request->isDataTable) {
            return $this->dataTableColumns($items);
        } else {
            return view(self::ViewPath . 'index', [
                'items' => $items,
                'currencies' => $currencies,
                'mainTypes' => $mainTypes,
                'subTypes' => $subTypes,
                'js_columns' => BankAccount::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create(Request $request)
    {
        $branchId = $request->filled('branch_id') ? $request->branch_id : Auth::user()->branch_id;
        $mainTypes = TypeBankAccount::getMainTypes($branchId);
        $subTypes = TypeBankAccount::where('parent_id', '!=', null)->where('branch_id', $branchId)->get();
        $banksData = BankData::where('branch_id', $branchId)->get();
        $currencies = Currency::all();
        $products = BranchProduct::all();
        $bankAccounts = BankAccount::where('branch_id', $branchId)->get();
        return view(self::ViewPath . 'create',
            compact('mainTypes', 'subTypes', 'banksData', 'currencies', 'products', 'bankAccounts'));
    }

    public function store(BankAccountRequest $request)
    {
        try {
            BankAccount::create($request->all());
            return redirect()->route('admin:banks.banks_accounts.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->withInput($request->all())->with(['message' => __('something went wrong'), 'alert-type' => 'error']);
        }
    }

    public function show(int $id)
    {
        $item = BankAccount::findOrFail($id);
        $view = view(self::ViewPath . 'show', compact('item'))->render();
        return response()->json([
            'data' => $view
        ]);
    }

    public function edit(int $id)
    {
        $item = BankAccount::findOrfail($id);
        $mainTypes = TypeBankAccount::getMainTypes($item->branch_id, $item);
        $subTypes = TypeBankAccount::where('parent_id', '!=', null)->where('branch_id', $item->branch_id)->get();
        $banksData = BankData::where('branch_id', $item->branch_id)->get();
        $currencies = Currency::all();
        $products = BranchProduct::all();
        $bankAccounts = BankAccount::where('branch_id', $item->branch_id)->get();
        return view(self::ViewPath . 'edit',
            compact('item', 'mainTypes', 'banksData', 'currencies', 'subTypes', 'products'));
    }

    public function update(BankAccountRequest $request, int $id)
    {
        try {
            $item = BankAccount::findOrfail($id);
            $item->update($request->all());
            if ($item->mainType->name == 'حسابات ودائع وشهادات') {
                $item->update([
                    'sub_type_bank_account_id' => null
                ]);
            }
            return redirect()->route('admin:banks.banks_accounts.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->withInput($request->all())->with(['message' => __('something went wrong'), 'alert-type' => 'error']);
        }
    }

    public function destroy(int $id)
    {
        $item = BankAccount::findOrFail($id);
        $item->delete();
        return redirect()->route('admin:banks.banks_accounts.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {
            $items = BankAccount::whereIn('id', array_unique($request->ids))->delete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->back()->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getCreditForm(Request $request)
    {
        $branchId = $request->filled('branch_id') ? $request->branch_id : auth()->user()->brnach_id;
        $banksData = BankData::where('branch_id', $branchId)->get();
        $currencies = Currency::all();
        $products = BranchProduct::all();
        $bankAccounts = BankAccount::where('branch_id', $branchId)->get();
        if ($request->filled('sub_type_bank_account_id')) {
            $subTypeBank = TypeBankAccount::find($request->sub_type_bank_account_id);
        }
        if ($request->filled('bank_account_id')) {
            $item = BankAccount::find($request->bank_account_id);
        }
        $form = $request->filled('main_bank_account_type_id') ? 'forms.cert_form' : 'forms.credit_form';
        $view = view(self::ViewPath . $form, [
            'banksData' => $banksData,
            'currencies' => $currencies,
            'products' => $products,
            'subTypeBank' => $subTypeBank ?? null,
            'item' => $item ?? null,
            'bankAccounts' => $bankAccounts,
        ])->render();
        return response()->json([
            'data' => $view
        ]);
    }


    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.banks.banks_accounts.datatables.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('branch_id', function ($item) use ($viewPath) {
                $withBranch = true;
                return view($viewPath, compact('item', 'withBranch'))->render();
            })
            ->addColumn('type_bank_account', function ($item) use ($viewPath) {
                $type_bank_account = true;
                return view($viewPath, compact('item', 'type_bank_account'))->render();
            })
            ->addColumn('bank_name', function ($item) {
                return optional($item->bankData)->name;
            })
            ->addColumn('balance', function ($item) use ($viewPath) {
                $balance = true;
                return view($viewPath, compact('item', 'balance'))->render();
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

    private function filter(Request $request, Builder $items)
    {
        if ($request->filled('branchId')) {
            $items = $items->where('branch_id', $request['branchId']);
        }
        if ($request->filled('bank_data_id')) {
            $items = $items->where('bank_data_id', $request['bank_data_id']);
        }
        if ($request->filled('branch')) {
            $items = $items->where('bank_data_id', $request['branch']);
        }
        if ($request->filled('branch_product_id')) {
            $items = $items->where('branch_product_id', $request['branch_product_id']);
        }

        if ($request->filled('main_type_bank_account_id')) {
            $items = $items->where('main_type_bank_account_id', $request['main_type_bank_account_id']);
        }

        if ($request->filled('sub_type_bank_account_id')) {
            $items = $items->where('sub_type_bank_account_id', $request['sub_type_bank_account_id']);
        }

        if ($request->filled('currency_id')) {
            $items = $items->where('currency_id', $request['currency_id']);
        }

        if ($request->filled('type')) {
            $items = $items->where('type', $request['type']);
        }

        if ($request->filled('iban')) {
            $items = $items->where('id', $request['iban']);
        }

        if ($request->filled('customer_number')) {
            $items = $items->where('id', $request['customer_number']);
        }

        if ($request->filled('yield_rate_type')) {
            $items = $items->where('yield_rate_type', $request['yield_rate_type']);
        }

        if ($request->filled('status')) {
            if ($request->status == 'all') {
                $items = $items->whereIn('status', [1, 0]);
            } else {
                $items = $items->where('status', $request->status);
            }
        }

        if ($request->filled('auto_renewal')) {
            if ($request->auto_renewal == 'all') {
                $items = $items->whereIn('auto_renewal', [1, 0]);
            } else {
                $items = $items->where('auto_renewal', $request->auto_renewal);
            }
        }

        if ($request->filled('with_returning')) {
            if ($request->with_returning == 'all') {
                $items = $items->whereIn('with_returning', [1, 0]);
            } else {
                $items = $items->where('with_returning', $request->with_returning);
            }
        }

        if ($request->filled('check_books')) {
            if ($request->with_returning == 'all') {
                $items = $items->whereIn('check_books', [1, 0]);
            } else {
                $items = $items->where('check_books', $request->check_books);
            }
        }

        if ($request->filled('overdraft')) {
            if ($request->overdraft == 'all') {
                $items = $items->whereIn('overdraft', [1, 0]);
            } else {
                $items = $items->where('overdraft', $request->overdraft);
            }
        }

        if ($request->filled('Last_renewal_date_from') && $request->filled('Last_renewal_date_from')) {
            $items = $items->whereBetween('Last_renewal_date', [
                $request->Last_renewal_date_from,
                $request->Last_renewal_date_from,
            ]);
        }

        if ($request->filled('deposit_opening_date_from') && $request->filled('deposit_opening_date_to')) {
            $items = $items->whereBetween('deposit_opening_date', [
                $request->deposit_opening_date_from,
                $request->deposit_opening_date_to,
            ]);
        }

        if ($request->filled('deposit_expiry_date_from') && $request->filled('deposit_expiry_date_to')) {
            $items = $items->whereBetween('deposit_expiry_date', [
                $request->deposit_expiry_date_from,
                $request->deposit_expiry_date_to,
            ]);
        }

        if ($request->filled('account_open_date_from') && $request->filled('account_open_date_to')) {
            $items = $items->whereBetween('account_open_date', [
                $request->account_open_date_from,
                $request->account_open_date_to,
            ]);
        }

        if ($request->filled('expiry_date_from') && $request->filled('expiry_date_to')) {
            $items = $items->whereBetween('expiry_date', [
                $request->expiry_date_from,
                $request->expiry_date_to,
            ]);
        }
        return $items;
    }
}
