<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetRequest;
use App\Models\AssetGroup;
use App\Models\AssetEmployee;
use App\Models\AssetInsurance;
use App\Models\AssetExamination;
use App\Models\AssetLicense;
use App\Models\AssetType;
use App\Models\Asset;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetsController extends Controller
{
    
    public function __construct()
    {
//        $this->middleware('permission:view_currencies');
//        $this->middleware('permission:create_currencies',['only'=>['create','store']]);
//        $this->middleware('permission:update_currencies',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_currencies',['only'=>['destroy','deleteSelected']]);
    }

    public function index()
    {
        // if (!auth()->user()->can('view_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        $assets = Asset::orderBy('id' ,'desc')->get();
        return view('admin.assets.index', compact('assets'));
    }

    public function show(Request $request)
    {

        $asset = Asset::where("id",$request['asset_id'])->first();
        $assetEmployees = AssetEmployee::where("asset_id" , $asset->id)->get();
        $assetInsurances = AssetInsurance::where("asset_id" , $asset->id)->get();
        $assetExaminations = AssetExamination::where("asset_id" , $asset->id)->get();
        $assetLicenses = AssetLicense::where("asset_id" , $asset->id)->get();
        $assetType = AssetType::where("id" , $asset->asset_type_id)->first();
        $view = view('admin.assets.show', compact("asset","assetType","assetEmployees","assetInsurances","assetExaminations","assetLicenses"))->render();
        
        return response()->json(['view' => $view]);
    }

    public function create()
    {
        // if (!auth()->user()->can('create_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }
        $branches = Branch::all();
        $assetsGroups = AssetGroup::all();
        $assetsTypes = AssetType::all();

        return view('admin.assets.create' , compact("assetsGroups","branches","assetsTypes"));
    }

    public function store(AssetRequest $request)
    {
        // if (!auth()->user()->can('create_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }
        
        
        $asset_group = AssetGroup::find($request->asset_group_id);
        if( ($request->purchase_cost/$asset_group->annual_consumtion_rate) > 0){
            $asset_age = ($request->purchase_cost/$asset_group->annual_consumtion_rate)/1000;
        }else{
            $asset_age = 0;
        }
        Asset::create([
            'asset_age' => $asset_age,
            'branch_id' => $request->branch_id,
           'annual_consumtion_rate' => $asset_group->annual_consumtion_rate,
           'name_ar' => $request->name_ar,
           'name_en' => $request->name_en,
           'asset_group_id' => $request->asset_group_id,
           'asset_type_id' => $request->asset_type_id,
           'asset_status' => $request->asset_status,
           'asset_details' => $request->asset_details,
           'purchase_date' => $request->purchase_date,
           'date_of_work' => $request->date_of_work,
           'purchase_cost' => $request->purchase_cost,
       ]);
        return redirect()->to('admin/assets')
            ->with(['message' => __('words.asset-created'), 'alert-type' => 'success']);
    }

    public function edit(asset $asset)
    {
        // if (!auth()->user()->can('update_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }
        $branches = Branch::all();
        $assetsGroups = AssetGroup::all();
        $assetsTypes = AssetType::all();
        return view('admin.assets.edit', compact('asset',"assetsGroups","branches","assetsTypes"));
    }

    public function update(AssetRequest $request, asset $asset)
    {
        // if (!auth()->user()->can('update_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        $asset_group = AssetGroup::find($request->asset_group_id);
        if( ($request->purchase_cost/$asset_group->annual_consumtion_rate) > 0){
            $asset_age = ($request->purchase_cost/$asset_group->annual_consumtion_rate)/1000;
        }else{
            $asset_age = 0;
        }
        $asset->update([
            'asset_age' => $asset_age,
            'branch_id' => $request->branch_id,
           'annual_consumtion_rate' => $request->annual_consumtion_rate,
           'name_ar' => $request->name_ar,
           'name_en' => $request->name_en,
           'asset_group_id' => $request->asset_group_id,
           'asset_type_id' => $request->asset_type_id,
           'asset_status' => $request->asset_status,
           'asset_details' => $request->asset_details,
           'purchase_date' => $request->purchase_date,
           'date_of_work' => $request->date_of_work,
           'purchase_cost' => $request->purchase_cost,
       ]);
        return redirect()->to('admin/assets')
            ->with(['message' => __('words.asset-updated'), 'alert-type' => 'success']);
        // return redirect()->to('admin/assets-groups')
        //     ->with(['message' => __('words.asset-group-updated'), 'alert-type' => 'success']);
    }

    public function destroy(Asset $asset)
    {
        // // if ($currency->countries()->exists()) {
        // //     return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        // // }
        // if (!auth()->user()->can('delete_currencies')) {
        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        // delete Asset Insurances
        AssetInsurance::where("asset_id" , $asset->id)->delete();
        // delete Asset Examinations
        AssetExamination::where("asset_id" , $asset->id)->delete();
        // delete Asset Licenses
        AssetLicense::where("asset_id" , $asset->id)->delete();

        // delete Asset Employees
        AssetEmployee::where("asset_id" , $asset->id)->delete();
        // delete Asset
        $asset->delete();

        return redirect()->to('admin/assets')
            ->with(['message' => __('words.asset-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        // if (!auth()->user()->can('delete_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        if (isset($request->ids)) {
            // delete Asset Insurances
            AssetInsurance::whereIn("asset_id" , $request->ids)->delete();
            // delete Asset Examinations
            AssetExamination::whereIn("asset_id" , $request->ids)->delete();
            // delete Asset Licenses
            AssetLicense::whereIn("asset_id" , $request->ids)->delete();

            // delete Asset Employees
            AssetEmployee::whereIn("asset_id" , $request->ids)->delete();
            // delete Asset
            Asset::whereIn("id" , $request->ids)->delete();
            return redirect()->to('admin/assets')
            ->with(['message' => __('words.selected-rows-delete'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/assets')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }

}
