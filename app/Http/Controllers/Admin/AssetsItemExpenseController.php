<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use App\Traits\LoggerError;
use Illuminate\Http\Request;
use App\Models\AssetsItemExpense;
use App\Models\AssetsTypeExpense;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\Asset\ExpenseItemRequest;
use App\Http\Requests\Admin\Asset\UpdateExpenseItemRequest;

/**
 * Class AssetsItemExpenseController
 * @package App\Http\Controllers\Admin
 * @author Eng-Hamdi Antar <hamdiantar27@gmail.com>
 */
class AssetsItemExpenseController extends Controller
{
    use LoggerError;

    public function index(): View
    {
        $expenseItems = AssetsItemExpense::query();
        return view('admin.assets_expenses_items.index', ['expenseItems' => $expenseItems->orderBy('id', 'desc')->get()]);
    }

    public function create(): View
    {
        $expensesTypes = AssetsTypeExpense::all();
        return view('admin.assets_expenses_items.create', compact('expensesTypes'));
    }

    public function store(ExpenseItemRequest $request): RedirectResponse
    {
        try {
            $data = $request->all();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            AssetsItemExpense::create($data);
            return redirect()->to('admin/assets_expenses_items')
                ->with(['message' => __('words.expense-item-created'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function edit(int $id)
    {
        $expensesItem = AssetsItemExpense::findOrFail($id);
        $expensesTypes = AssetsTypeExpense::all();
        return view('admin.assets_expenses_items.edit', compact('expensesItem', 'expensesTypes'));
    }

    public function update(UpdateExpenseItemRequest $request, int $id): RedirectResponse
    {
        $expensesItem = AssetsItemExpense::findOrFail($id);
        try {
            $data = $request->all();
            if (!authIsSuperAdmin()) {
                $data['branch_id'] = auth()->user()->branch_id;
            }
            $expensesItem->update($data);
            return redirect()->to('admin/assets_expenses_items')
                ->with(['message' => __('words.expense-item-updated'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->with(['message' => __('words.something-went-wrong'), 'alert-type' => 'error']);
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        $expensesItem = AssetsItemExpense::findOrFail($id);
        if (count($expensesItem->assetExpenseItems)  > 0) {
            return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        }
        $expensesItem->delete();
        return redirect()->to('admin/assets_expenses_items')
            ->with(['message' => __('words.expense-type-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            $items = AssetsItemExpense::whereIn('id', $request->ids)->get();
            foreach ($items as $item) {
                if (count($item->assetExpenseItems)  > 0) {
                    return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
                }
                $item->delete();
            }
            return redirect()->to('admin/assets_expenses_items')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/assets_expenses_items')
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getAssetItemsExpenseById(Request $request): JsonResponse
    {
        $items = AssetsItemExpense::query();
        $branch_id = $request->filled('branch_id') ? $request->branch_id : auth()->user()->branch_id;
        if ($request->assets_type_expenses_id === '*') {
            $items = $items->where('branch_id', $branch_id);
        } else {
            $items = $items->where([
                'assets_type_expenses_id' => $request->assets_type_expenses_id,
                'branch_id' => $branch_id,
            ]);
        }
        $items = $items->get();
        $view = view('admin.assets_expenses_items.options', compact('items'))->render();
        return response()->json([
           'items' => $view
        ]);
    }
}
