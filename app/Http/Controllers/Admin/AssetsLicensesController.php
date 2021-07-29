<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetLicenseRequest;
use App\Models\AssetLicense;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\DataTables;

class AssetsLicensesController extends Controller
{
    public function index(asset $asset,Request $request)
    {

        $assetsLicenses = AssetLicense::where("asset_id" , $asset->id)->orderBy('id' ,'desc');
        if ($request->has( 'name' ) && $request['name'] != '')
            $assetsLicenses->where( 'id',$request['name']  );

        if ($request->has( 'active' ) && $request['active'] != '')
            $assetsLicenses->where( 'status', '1' );

        if ($request->has( 'inactive' ) && $request['inactive'] != '')
            $assetsLicenses->where( 'status', '0' );

        if ($request->isDataTable) {
            try {
                return $this->dataTableColumns($assetsLicenses);
            } catch (Throwable $e) {
            }
        } else {
            return view('admin.assetsLicenses.index', [
                'asset' => $asset,
                'assetsLicenses' => $assetsLicenses,
                'js_columns' => AssetLicense::getJsDataTablesColumns(),
            ]);
        }
    }


    public function store(AssetLicenseRequest $request )
    {
        if($request->asset_license_id){

            $getlicense = AssetLicense::find($request->asset_license_id);
            if($getlicense){
                AssetLicense::where("id" , $request->asset_license_id)->update([
                    "license_details" => $request->name,
                    "asset_id" => $request->asset_id,
                    "start_date" => $request->start_date,
                    "end_date" => $request->end_date,
                    'status'=>$request->status
                ]);
            }
            return redirect()->to('admin/assets-licenses/'.$request->asset_id)
            ->with(['message' => __('words.asset-licenses-updated'), 'alert-type' => 'success']);

        }else{
            AssetLicense::create([
                "license_details" => $request->name,
                "asset_id" => $request->asset_id,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                'status'=>$request->status
            ]);
            return redirect()->to('admin/assets-licenses/'.$request->asset_id)
            ->with(['message' => __('words.asset-licenses-created'), 'alert-type' => 'success']);
        }


    }



    public function destroy(AssetLicense $assetLicense)
    {
        $assetLicense->delete();
        return redirect()->to('admin/assets-licenses/'.$assetLicense->asset_id)
            ->with(['message' => __('words.asset-licenses-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected( Request $request)
    {
        if (isset($request->ids)) {
            $assetsLicenses = AssetLicense::whereIn('id', $request->ids)->delete();

            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.assetsLicenses.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('status', function ($item) use ($viewPath) {
                $withStatus = true;
                return view($viewPath, compact('item', 'withStatus'))->render();
            })
            ->addColumn('license_details', function ($item) use ($viewPath) {
                return  $item->license_details;
            })
            ->addColumn('start_date', function ($item) {
                return $item->start_date;
            })
            ->addColumn('end_date', function ($item) {
                return $item->end_date;
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
