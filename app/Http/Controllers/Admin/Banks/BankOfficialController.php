<?php

namespace App\Http\Controllers\Admin\Banks;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankOfficialRequest;
use App\Models\Banks\BankData;
use App\Models\Banks\BankOfficial;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

class BankOfficialController extends Controller
{
    const ViewPath = 'admin.banks.bank_officials.';

    public function index(BankData $bankData, Request $request)
    {
        $items = $bankData->bankOfficials;
        $items = $this->filter($request, $items);
        if ($request->isDataTable) {
            return $this->dataTableColumns($items);
        } else {
            return view(self::ViewPath . 'index', [
                'items' => $items,
                'bankData' => $bankData,
                'js_columns' => BankOfficial::getJsDataTablesColumns(),
            ]);
        }
    }

    public function store(BankOfficialRequest $request): RedirectResponse
    {
        if ($request->old_bank_official_id) {
            $item = BankOfficial::find($request->old_bank_official_id);
            if ($item) {
                $item->update($request->all());
            }
            return redirect()->back()->with(['message' => __('words.bank-official-updated'), 'alert-type' => 'success']);

        } else {
            BankOfficial::create($request->all());
            return redirect()->back()->with(['message' => __('words.bank-official-created'), 'alert-type' => 'success']);
        }
    }

    public function destroy(BankOfficial $bankOfficial): RedirectResponse
    {
        $bankOfficial->delete();
        return redirect()->back()->with(['message' => __('words.bank-official-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            BankOfficial::whereIn('id', $request->ids)->delete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
    }

    private function filter(Request $request, Collection $items): Collection
    {
        if ($request->filled('bank_official_id')) {
            $items = $items->where('id', $request['bank_official_id']);
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
                return $item->name;
            })
            ->addColumn('email', function ($item) use ($view) {
                return $item->email;
            })
            ->addColumn('job', function ($item) use ($view) {
                return $item->job;
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
