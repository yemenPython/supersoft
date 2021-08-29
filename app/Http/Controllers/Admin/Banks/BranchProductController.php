<?php

namespace App\Http\Controllers\Admin\Banks;

use App\Models\Banks\BankData;
use Exception;
use App\Traits\LoggerError;
use Illuminate\Http\Request;
use App\Models\Banks\BranchProduct;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchProductRequest;

class BranchProductController extends Controller
{
    use LoggerError;

    const ViewPath = 'admin.banks.branch_product.';

    public function index(Request $request)
    {
        $items = BranchProduct::latest()->get();
        return view(self::ViewPath . 'index', compact('items'));
    }

    public function create()
    {
        return view(self::ViewPath . 'create');
    }

    public function store(BranchProductRequest $request)
    {
        try {
            BranchProduct::create($request->all());
            return redirect()->route('admin:banks.branch_product.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            dd($exception->getMessage());
            $this->logErrors($exception);
            return back()->withInput($request->all())->with(['message' => __('something went wrong'), 'alert-type' => 'error']);
        }
    }

    public function show(int $id)
    {
        $items = BranchProduct::latest()->get();
        $item = BankData::findOrFail($id);
        return response()->json([
           'data' => view('admin.banks.bank_data.products.show', compact('items', 'item'))->render()
        ]);
    }

    public function edit(int $id)
    {
        $item = BranchProduct::findOrFail($id);
        return view(self::ViewPath . 'edit', compact('item'));
    }

    public function update(BranchProductRequest $request, int $bankDataId)
    {
        try {
            $item = BranchProduct::findOrFail($bankDataId);
            $item->update($request->all());
            return redirect()->route('admin:banks.branch_product.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->withInput($request->all())->with(['message' => __('something went wrong'), 'alert-type' => 'error']);
        }
    }

    public function destroy(int $id)
    {
        $item = BranchProduct::findOrFail($id);
        $item->delete();
        return redirect()->route('admin:banks.branch_product.index')->with(['message' => __('Item has been Deleted successfully ...'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {
            BranchProduct::whereIn('id', $request->ids)->delete();
            return redirect()->route('admin:banks.branch_product.index')->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->route('admin:banks.branch_product.index')->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
