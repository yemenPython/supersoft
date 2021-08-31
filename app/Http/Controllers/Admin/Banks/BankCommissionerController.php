<?php

namespace App\Http\Controllers\Admin\Banks;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankCommissionerRequest;
use App\Http\Requests\BankOfficialRequest;
use App\Models\Banks\BankCommissioner;
use App\Models\Banks\BankData;
use App\Models\EmployeeData;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

class BankCommissionerController extends Controller
{
    const ViewPath = 'admin.banks.bank_commissioners.';

    public function index(BankData $bankData, Request $request)
    {
        $items = $bankData->bankcommissioners;
        $items = $this->filter($request, $items);
        $employeesData = EmployeeData::where("branch_id", $bankData->branch_id)->select(['id', 'name_ar', 'name_en'])->get();
        if ($request->isDataTable) {
            return $this->dataTableColumns($items);
        } else {
            return view(self::ViewPath . 'index', [
                'items' => $items,
                'bankData' => $bankData,
                'employeesData' => $employeesData,
                'js_columns' => BankCommissioner::getJsDataTablesColumns(),
            ]);
        }
    }

    public function store(BankCommissionerRequest $request): RedirectResponse
    {
        if ($request->old_bank_commissioner_id) {
            $item = BankCommissioner::find($request->old_bank_commissioner_id);
            if ($item) {
                $item->update($request->all());
            }
            return redirect()->back()->with(['message' => __('words.bank-commissioner-updated'), 'alert-type' => 'success']);

        } else {
            BankCommissioner::create($request->all());
            return redirect()->back()->with(['message' => __('words.bank-commissioner-created'), 'alert-type' => 'success']);
        }
    }

    public function destroy(BankCommissioner $bankCommissioner): RedirectResponse
    {
        $bankCommissioner->delete();
        return redirect()->back()->with(['message' => __('words.bank-commissioner-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            BankCommissioner::whereIn('id', $request->ids)->delete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
    }

    private function filter(Request $request, Collection $items): Collection
    {
        if ($request->filled('bank_commissioner_id')) {
            $items = $items->where('id', $request['bank_commissioner_id']);
        }
        if ($request->has('start') && $request['start'] != '') {
            $items = $items->where('date_from', $request->start_date);
        }
        if ($request->has('end') && $request['end'] != '') {
            $items = $items->where('date_to', $request->end_date);
        }
        if ($request->has('active') && $request['active'] != '') {
            $items = $items->where('status', '1');
        }
        if ($request->has('inactive') && $request['inactive'] != '') {
            $items = $items->where('status', '0');
        }
        return $items;
    }

    /**
     * @param Collection $items
     * @return mixed
     * @throws \Throwable
     */
    private function dataTableColumns(Collection $items)
    {
        $view = self::ViewPath . 'datatables-options';

        return DataTables::of($items)->addIndexColumn()
            ->addColumn('status', function ($item) use ($view) {
                $withStatus = true;
                return view($view, compact('item', 'withStatus'))->render();
            })
            ->addColumn('name', function ($item) use ($view) {
                return optional($item->employee)->name;
            })
            ->addColumn('email', function ($item) use ($view) {
                return optional($item->employee)->email;
            })
            ->addColumn('phones', function ($item) use ($view) {
                $withPhones = true;
                return view($view, compact('item', 'withPhones'))->render();
            })
            ->addColumn('date_from', function ($item) use ($view) {
                $withStartData = true;
                return view($view, compact('item', 'withStartData'))->render();
            })
            ->addColumn('date_to', function ($item) use ($view) {
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
