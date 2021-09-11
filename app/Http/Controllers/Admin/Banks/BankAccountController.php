<?php

namespace App\Http\Controllers\Admin\Banks;

use App\Models\Banks\BranchProduct;
use App\Models\Currency;
use App\Traits\LoggerError;
use Illuminate\Http\Request;
use App\Models\Banks\BankData;
use App\Http\Controllers\Controller;
use App\Models\Banks\TypeBankAccount;
use App\Http\Requests\BankDataRequest;

class BankAccountController extends Controller
{
    use LoggerError;

    const ViewPath = 'admin.banks.banks_accounts.';

    public function index(Request $request)
    {
        $items = BankData::latest();
//        if ($request->filled('filter')) {
//        $items = $this->filter($request, $items);
//        }
        if ($request->isDataTable) {
            return $this->dataTableColumns($items);
        } else {
            return view(self::ViewPath.'index', [
                'items' => $items,
                'js_columns' => BankData::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create(Request $request)
    {
        $branchId = $request->filled('branch_id') ? $request->branch_id : auth()->user()->brnach_id;
        $mainTypes = TypeBankAccount::where('parent_id', null)->where('branch_id', $branchId)->get();
        $subTypes = TypeBankAccount::where('parent_id', '!=', null)->where('branch_id', $branchId)->get();
        return view(self::ViewPath . 'create', compact('mainTypes', 'subTypes'));
    }

    public function store(BankDataRequest $request)
    {
        try {
            BankData::create($request->all());
            return redirect()->route('admin:banks.banks_accounts.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->withInput($request->all())->with(['message' => __('something went wrong'), 'alert-type' => 'error']);
        }
    }

    public function show(int $bankDataId)
    {
        $item = BankData::findOrFail($bankDataId);
        $view =  view(self::ViewPath . 'show', compact('item'))->render();
        return response()->json([
            'data' => $view
        ]);
    }

    public function edit(int $bankDataId)
    {
        $item = BankData::findOrFail($bankDataId);
        return view(self::ViewPath . 'edit', compact('item'));
    }

    public function update(BankDataRequest $request, int $bankDataId)
    {
        try {
            $item = BankData::findOrFail($bankDataId);
            $item->update($request->all());
            return redirect()->route('admin:banks.banks_accounts.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->withInput($request->all())->with(['message' => __('something went wrong'), 'alert-type' => 'error']);
        }
    }

    public function destroy(int $id)
    {
        $item = BankData::findOrFail($id);
        $item->products()->detach();
        $item->bankOfficials()->delete();
        $item->bankcommissioners()->delete();
        $item->delete();
        return redirect()->route('admin:banks.banks_accounts.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {
            $items = BankData::whereIn('id', array_unique($request->ids))->get();
            foreach ($items as $item) {
                $item->products()->detach();
                $item->bankOfficials()->delete();
                $item->bankcommissioners()->delete();
                $item->delete();
            }
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
        $view = view(self::ViewPath.'forms.credit_form', [
            'banksData' => $banksData,
            'currencies' => $currencies,
            'products' => $products,
        ])->render();
        return response()->json([
           'data' => $view
        ]);
    }
}
