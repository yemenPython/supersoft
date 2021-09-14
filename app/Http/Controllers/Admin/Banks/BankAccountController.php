<?php

namespace App\Http\Controllers\Admin\Banks;

use Throwable;
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
            return view(self::ViewPath.'index', [
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
        return view(self::ViewPath . 'create',
            compact('mainTypes', 'subTypes', 'banksData', 'currencies', 'products'));
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
        $view =  view(self::ViewPath . 'show', compact('item'))->render();
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

        if ($request->filled('sub_type_bank_account_id')) {
            $subTypeBank = TypeBankAccount::find($request->sub_type_bank_account_id);
        }
         if ($request->filled('bank_account_id')) {
             $item = BankAccount::find($request->bank_account_id);
         }
        $form = $request->filled('main_bank_account_type_id') ?  'forms.cert_form' : 'forms.credit_form';
        $view = view(self::ViewPath.$form, [
            'banksData' => $banksData,
            'currencies' => $currencies,
            'products' => $products,
            'subTypeBank' => $subTypeBank ?? null,
            'item' => $item ?? null,
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
            ->addColumn('balance', function ($item) use ($viewPath){
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

        if ($request->filled('yield_rate_type')) {
            $items = $items->where('yield_rate_type', $request['yield_rate_type']);
        }

        if ($request->has('active') && $request['active'] != '') {
            $items = $items->where('status', '1');
        }
        if ($request->has('inactive') && $request['inactive'] != '') {
            $items = $items->where('status', '0');
        }

        if ($request->has('with_returning') && $request['with_returning'] != '') {
            $items = $items->where('with_returning', '1');
        }
        if ($request->has('without_returning') && $request['without_returning'] != '') {
            $items = $items->where('without_returning', '0');
        }

        if ($request->has('with_check_books') && $request['with_check_books'] != '') {
            $items = $items->where('check_books', '1');
        }

        if ($request->has('without_check_books') && $request['without_check_books'] != '') {
            $items = $items->where('check_books', '0');
        }

        if ($request->has('with_overdraft') && $request['with_overdraft'] != '') {
            $items = $items->where('overdraft', '1');
        }

        if ($request->has('without_overdraft') && $request['without_overdraft'] != '') {
            $items = $items->where('overdraft', '0');
        }

        if ($request->has('with_auto_renewal') && $request['with_auto_renewal'] != '') {
            $items = $items->where('auto_renewal', '1');
        }

        if ($request->has('without_auto_renewal') && $request['without_auto_renewal'] != '') {
            $items = $items->where('auto_renewal', '0');
        }


        return $items;
    }
}
