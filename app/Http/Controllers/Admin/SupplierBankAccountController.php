<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierBankAccountRequest;
use App\Http\Requests\SupplierContactRequest;
use App\Models\BankAccount;
use App\Models\Supplier;
use App\Models\SupplierContact;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SupplierBankAccountController extends Controller
{
    public function index(Supplier $supplier, Request $request)
    {
        $bankAccounts = $supplier->bankAccounts;
        $bankAccounts = $this->filter($request, $bankAccounts);
        if ($request->isDataTable) {
            return $this->dataTableColumns($bankAccounts);
        } else {
            return view('admin.suppliers.bank_accounts.index', [
                'bankAccounts' => $bankAccounts,
                'supplier' => $supplier,
                'js_columns' => BankAccount::getJsDataTablesColumns(),
            ]);
        }
    }

    public function store(SupplierBankAccountRequest $request): RedirectResponse
    {
        if ($request->filled('supplier_bank_account_id')) {
            if ($item = BankAccount::find($request->supplier_bank_account_id)) {
                $item->update($request->all());
                return redirect()->back()->with(['message' => __('words.supplier-bank-account-updated'), 'alert-type' => 'success']);
            }
            return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);

        } else {
            BankAccount::create($request->all());
            return redirect()->back()->with(['message' => __('words.supplier-contact-created'), 'alert-type' => 'success']);
        }
    }

    public function destroy(BankAccount $bankAccount): RedirectResponse
    {
        $bankAccount->delete();
        return redirect()->back()->with(['message' => __('words.supplier-bank-account-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            BankAccount::whereIn('id', $request->ids)->delete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
    }


    private function filter(Request $request, Collection $contacts): Collection
    {
        if ($request->filled('bank_account')) {
            $contacts = $contacts->where('id', $request->bank_account);
        }

        if ($request->has('end') && $request['end'] != '') {
            $contacts = $contacts->where('end', $request->end_date);
        }
        if ($request->has('active') && $request['active'] != '') {
            $contacts = $contacts->where('status', '1');
        }
        if ($request->has('inactive') && $request['inactive'] != '') {
            $contacts = $contacts->where('status', '0');
        }
        return $contacts;
    }

    /**
     * @param Collection $items
     * @return mixed
     * @throws \Throwable
     */
    private function dataTableColumns(Collection $items)
    {
        $view = 'admin.suppliers.bank_accounts.datatables-options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('status', function ($item) use ($view) {
                $withStatus = true;
                return view($view, compact('item', 'withStatus'))->render();
            })
            ->addColumn('bank_name', function ($item) use ($view) {
                return $item->bank_name;
            })
            ->addColumn('account_name', function ($item) use ($view) {
                return $item->account_name;
            })
            ->addColumn('branch', function ($item) use ($view) {
                return $item->branch;
            })
            ->addColumn('account_number', function ($item) use ($view) {
                return $item->account_number;
            })
            ->addColumn('iban', function ($item) use ($view) {
                return $item->iban;
            })
            ->addColumn('swift_code', function ($item) use ($view) {
                return $item->swift_code;
            })
            ->addColumn('start_date', function ($item) use ($view) {
                $withStartData = true;
                return view($view, compact('item', 'withStartData'))->render();
            })
            ->addColumn('end_date', function ($item) use ($view) {
                $withEndData = true;
                return view($view, compact('item', 'withEndData'))->render();
            })
            ->addColumn('action', function ($item) use ($view) {
                $withActions = true;
                return view($view, compact('item', 'withActions'))->render();
            })->addColumn('options', function ($item) use ($view) {
                $withOptions = true;
                return view($view, compact('item', 'withOptions'))->render();
            })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
    }
}
