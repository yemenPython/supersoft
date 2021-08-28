<?php

namespace App\Http\Controllers\Admin\Banks;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankDataRequest;
use App\Models\Banks\BankData;
use App\Traits\LoggerError;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\DataTables;

class BankDataController extends Controller
{
    use LoggerError;
    const ViewPath = 'admin.banks.bank_data.';

    public function index(Request $request)
    {
        $items = BankData::latest();
        if ($request->filled('filter')) {
            $items = $this->filter($request, $items);
        }
        if ($request->isDataTable) {
            return $this->dataTableColumns($items);
        } else {
            return view(self::ViewPath.'index', [
                'items' => $items,
                'js_columns' => BankData::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create()
    {
        return view(self::ViewPath . 'create');
    }

    public function store(BankDataRequest $request)
    {
        try {
            BankData::create($request->all());
            return redirect()->route('admin:banks.bank_data.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
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
            return redirect()->route('admin:banks.bank_data.index')->with(['message' => __('Item has been created successfully ...'), 'alert-type' => 'success']);
        } catch (Exception $exception) {
            $this->logErrors($exception);
            return back()->withInput($request->all())->with(['message' => __('something went wrong'), 'alert-type' => 'error']);
        }
    }

    public function StartDealing(int $bankDataId)
    {
        $item = BankData::findOrFail($bankDataId);
        if ($item->status) {
            $item->update([
               'status' => 0
            ]);
            return redirect()->route('admin:banks.bank_data.index')->with(['message' => __('تم إايقاف التعامل مع البنك بنجاح'), 'alert-type' => 'success']);
        }
        $item->update([
            'status' => 1
        ]);
        return redirect()->route('admin:banks.bank_data.index')->with(['message' => __('تم بدء التعامل مع البنك بنجاح'), 'alert-type' => 'success']);
    }

    public function assignProducts(Request $request)
    {
        dd($request->all());
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable|Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = self::ViewPath.'datatables.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('name', function ($item) use ($viewPath) {
                $withName = true;
                return view($viewPath, compact('item', 'withName'))->render();
            })
            ->addColumn('branch', function ($item) use ($viewPath) {
                return $item->branch;
            })
            ->addColumn('code', function ($item) use ($viewPath) {
                return $item->code;
            })
            ->addColumn('swift_code', function ($item) use ($viewPath) {
                return $item->swift_code;
            })
            ->addColumn('date', function ($item) use ($viewPath) {
                $withDate = true;
                return view($viewPath, compact('item', 'withDate'))->render();
            })
            ->addColumn('status', function ($item) use ($viewPath) {
                $withStatus = true;
                return view($viewPath, compact('item', 'withStatus'))->render();
            })
            ->addColumn('action', function ($item) use ($viewPath) {
                $withActions = true;
                return view($viewPath, compact('item', 'withActions'))->render();
            })->addColumn('options', function ($item) use ($viewPath) {
                $withOptions = true;
                return view($viewPath, compact('item', 'withOptions'))->render();
            })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
    }
}
