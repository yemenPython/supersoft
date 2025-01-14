<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetGroupRequest;
use App\Models\AssetGroup;
use App\Models\Asset;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssetsGroupController extends Controller
{
    public function index()
    {

        if (!auth()->user()->can('view_currencies')) {

            return redirect()->back()->with(['authorization' => 'error']);
        }

        $assetsGroups = AssetGroup::orderBy('id' ,'desc')->get();
        return view('admin.assetsGroups.index', compact('assetsGroups'));
    }

    public function create()
    {
        $branches = Branch::select(['id','name_ar','name_en'])->get();
        return view('admin.assetsGroups.create',compact(['branches']));
    }

    public function store(AssetGroupRequest $request)
    {
       AssetGroup::create($request->all());
        return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.asset-type-created'), 'alert-type' => 'success']);
    }

    public function edit(assetGroup $assetGroup)
    {
        $branches = Branch::select(['id','name_ar','name_en'])->get();
        return view('admin.assetsGroups.edit', compact('assetGroup','branches'));
    }

    public function update(AssetGroupRequest $request, AssetGroup $assetGroup)
    {
        $data = $request->all();
        if ($data['consumption_type'] =='manual'){
            $data['age_years'] = null;
            $data['age_months'] = null;
            $data['consumption_period'] = null;
        }
        $assetGroup->update($data);
        return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.asset-group-updated'), 'alert-type' => 'success']);
    }

    public function destroy(AssetGroup $assetGroup)
    {
        if($assetGroup->assets->count()){
            return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.group-asset-cannot-deleted-has-asset '), 'alert-type' => 'error']);

        }
        $assetGroup->delete();
        return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.asset-group-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {
            $assetsGroups = AssetGroup::whereIn('id', $request->ids)->get();
            foreach ($assetsGroups as $assetGroup) {
                if(!$assetGroup->assets->count()){
                    $assetGroup->delete();
                }
            }

            return redirect()->to('admin/assets-groups')
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
        return redirect()->to('admin/assets-groups')
            ->with(['message' => __('words.no-data-delete'), 'alert-type' => 'error']);
    }
}
