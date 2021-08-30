<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Currency\CurrencyRequest;
use App\Models\Currency;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CurrenciesController extends Controller
{
    /**
     * @var Setting
     */
    protected $setting;

    public function __construct()
    {
        $this->setting = Setting::first();
    }

    public function index()
    {
        if (!auth()->user()->can('view_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $currencies = Currency::orderBy('id' ,'desc')->get();
        $setting = $this->setting;
        return view('admin.currencies.index', compact('currencies', 'setting'));
    }

    public function create()
    {
        if (!auth()->user()->can('create_currencies')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $setting = $this->setting;
        return view('admin.currencies.create', compact('setting'));
    }

    public function store(CurrencyRequest $request)
    {
        if (!auth()->user()->can('create_currencies')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if ($request->is_main_currency && $this->checkCurrenciesHasOneMain()) {
            return redirect()->back()->withInput()->withErrors(['message' => __('you can only detect one currency as a Main Currency'), 'alert-type' => 'error']);
        }
        if ($request->is_main_currency && !$request->status) {
            return redirect()->back()->with(['message' => __('If The Currency Is Main should be activate'), 'alert-type' => 'error']);
        }

        Currency::create($request->all());
        return redirect()->to('admin/currencies')
            ->with(['message' => __('words.currency-created'), 'alert-type' => 'success']);
    }

    public function edit(Currency $currency)
    {
        if (!auth()->user()->can('update_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }
        $setting = $this->setting;
        return view('admin.currencies.edit', compact('currency', 'setting'));
    }

    public function update(CurrencyRequest $request, Currency $currency)
    {
        if (!auth()->user()->can('update_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }
        if ($request->is_main_currency && $this->checkCurrenciesHasOneMain($currency->id)) {
            return redirect()->back()->with(['message' => __('you can only detect one currency as a Main Currency'), 'alert-type' => 'error']);
        }
        if ($request->is_main_currency && !$request->status) {
            return redirect()->back()->with(['message' => __('If The Currency Is Main should be activate'), 'alert-type' => 'error']);
        }

        $currency->update($request->all());
        return redirect()->to('admin/currencies')
            ->with(['message' => __('words.currency-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Currency $currency)
    {
        if ($currency->seeder) {
            return redirect()->back()->with(['message' => __('you can not delete this item, it is default value, you can only edit it'), 'alert-type' => 'warning']);
        }
        if ($currency->countries()->exists()) {
            return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        }
        if (!auth()->user()->can('delete_currencies')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $currency->forceDelete();
        return redirect()->to('admin/currencies')
            ->with(['message' => __('words.currency-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {
            $currencies = Currency::whereIn('id', $request->ids)->get();
            foreach ($currencies as $currency) {
                if ($currency->seeder) {
                    return redirect()->back()->with(['message' => __('you can not delete this item, it is default value, you can only edit it'), 'alert-type' => 'warning']);
                }
                if ($currency->countries()->exists()) {
                    return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
                }
            }
            foreach ($currencies as $currency) {
                $currency->forceDelete();
            }
            return redirect()->to('admin/currencies')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/currencies')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }


    public function checkCurrenciesHasOneMain(int $id = null): ?Currency
    {
        $query = Currency::where('is_main_currency', 1);
        if ($id) {
            return $query->where('id', '!=', $id)->first();
        }
        return $query->first();
    }
}
