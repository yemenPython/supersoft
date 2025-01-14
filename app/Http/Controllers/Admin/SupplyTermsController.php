<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SupplyTerms\CreateRequest;
use App\Models\Branch;
use App\Models\SupplyTerm;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

class SupplyTermsController extends Controller
{
    public $lang;

    public function __construct()
    {
        $this->lang = App::getLocale();
    }

    public function index () {

        $data = SupplyTerm::get();
        return view('admin.supply_terms.index', compact('data'));
    }

    public function create () {

        $branches = Branch::select('id', 'name_' . $this->lang)->get();
        return view('admin.supply_terms.create', compact('branches'));
    }

    public function store (CreateRequest $request) {

        try {

            $data = $request->validated();

            $data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $data['status'] = $request->has('status') ? 1 : 0;

            $data['for_purchase_quotation'] = $request->has('for_purchase_quotation') ? 1 : 0;
            $data['supply_order'] = $request->has('supply_order') ? 1 : 0;
            $data['purchase_invoice'] = $request->has('purchase_invoice') ? 1 : 0;
            $data['purchase_return'] = $request->has('purchase_return') ? 1 : 0;
            $data['sale_quotation'] = $request->has('sale_quotation') ? 1 : 0;
            $data['sales_invoice'] = $request->has('sales_invoice') ? 1 : 0;
            $data['sales_invoice_return'] = $request->has('sales_invoice_return') ? 1 : 0;
            $data['sale_supply_order'] = $request->has('sale_supply_order') ? 1 : 0;

            SupplyTerm::create($data);

            return redirect(route('admin:supply-terms.index'))->with(['message' => __('done successfully'), 'alert-type'=>'success']);

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }
    }

    public function edit (SupplyTerm $supplyTerm) {

        $branches = Branch::select('id', 'name_' . $this->lang)->get();
        return view('admin.supply_terms.edit', compact('branches', 'supplyTerm'));
    }

    public function show (SupplyTerm $supplyTerm) {
        return view('admin.supply_terms.show', compact( 'supplyTerm'));
    }

    public function update (CreateRequest $request, SupplyTerm $supplyTerm) {

        try {

            $data = $request->validated();

            $data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;

            $data['status'] = $request->has('status') ? 1 : 0;

            $data['for_purchase_quotation'] = $request->has('for_purchase_quotation') ? 1 : 0;
            $data['supply_order'] = $request->has('supply_order') ? 1 : 0;
            $data['purchase_invoice'] = $request->has('purchase_invoice') ? 1 : 0;
            $data['purchase_return'] = $request->has('purchase_return') ? 1 : 0;
            $data['sale_quotation'] = $request->has('sale_quotation') ? 1 : 0;

            $data['sales_invoice'] = $request->has('sales_invoice') ? 1 : 0;
            $data['sales_invoice_return'] = $request->has('sales_invoice_return') ? 1 : 0;
            $data['sale_supply_order'] = $request->has('sale_supply_order') ? 1 : 0;

            $supplyTerm->update($data);

            return redirect(route('admin:supply-terms.index'))->with(['message' => __('done successfully'), 'alert-type'=>'success']);

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }
    }

    public function destroy (SupplyTerm $supplyTerm) {

        try {

            $supplyTerm->delete();
            return redirect(route('admin:supply-terms.index'))->with(['message' => __('done successfully'), 'alert-type'=>'success']);

        }catch (\Exception $e) {
            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }
    }

    public function deleteSelected (Request $request) {

        $this->validate($request, [
           'ids'=>'required',
           'ids.*'=>'required|integer|exists:supply_terms,id',
        ], ['ids.required' => __('sorry, please select items')]);

        try {

            foreach (array_values(array_unique($request['ids'])) as $id) {

                $term = SupplyTerm::find($id);
                $term->delete();
            }

            return redirect(route('admin:supply-terms.index'))->with(['message' => __('done successfully'), 'alert-type'=>'success']);

        }catch (\Exception $e) {

            return redirect()->back()->with(['message'=> __('sorry, please try later'), 'alert-type'=>'error']);
        }
    }

}
