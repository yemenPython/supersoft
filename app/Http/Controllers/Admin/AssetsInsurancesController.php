<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetInsuranceRequest;
use App\Models\AssetEmployee;
use App\Models\AssetGroup;
use App\Models\AssetType;
use App\Models\AssetInsurance;
use App\Models\Asset;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;
use Yajra\DataTables\DataTables;

class AssetsInsurancesController extends Controller
{
    public function index(asset $asset,Request $request)
    {
        $assetsInsurances = AssetInsurance::where("asset_id" , $asset->id)->latest();
        if ($request->has( 'name' ) && $request['name'] != '')
            $assetsInsurances->where( 'id',$request['name']  );

        if ($request->has( 'active' ) && $request['active'] != '')
            $assetsInsurances->where( 'status', '1' );

        if ($request->has( 'inactive' ) && $request['inactive'] != '')
            $assetsInsurances->where( 'status', '0' );

        if ($request->isDataTable) {
            try {
                return $this->dataTableColumns($assetsInsurances);
            } catch (Throwable $e) {
            }
        } else {
            return view('admin.assetsInsurances.index', [
                'asset' => $asset,
                'assetsInsurances' => $assetsInsurances,
                'js_columns' => AssetInsurance::getJsDataTablesColumns(),
            ]);
        }
    }

    public function store(AssetInsuranceRequest $request )
    {

        if($request->asset_insurance_id){

            $getInsurance = AssetInsurance::find($request->asset_insurance_id);
            if($getInsurance){
                AssetInsurance::where("id" , $request->asset_insurance_id)->update([
                    "insurance_details" => $request->name,
                    "asset_id" => $request->asset_id,
                    "start_date" => $request->start_date,
                    "end_date" => $request->end_date,
                    'status'=>$request->status
                ]);
            }
            return redirect()->to('admin/assets-insurances/'.$request->asset_id)
            ->with(['message' => __('words.asset-insurances-updated'), 'alert-type' => 'success']);

        }else{
            AssetInsurance::create([
                "insurance_details" => $request->name,
                "asset_id" => $request->asset_id,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                'status'=>$request->status
            ]);
            return redirect()->to('admin/assets-insurances/'.$request->asset_id)
            ->with(['message' => __('words.asset-insurances-created'), 'alert-type' => 'success']);
        }
    }


    public function destroy(AssetInsurance $assetInsurance)
    {
        $assetInsurance->delete();
        return redirect()->to('admin/assets-insurances/'.$assetInsurance->asset_id)
            ->with(['message' => __('words.asset-insurance-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected( Request $request)
    {
        if (isset($request->ids)) {
            $assetsInsurances = AssetInsurance::whereIn('id', $request->ids)->delete();
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
        $viewPath = 'admin.assetsInsurances.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('status', function ($item) use ($viewPath) {
                $withStatus = true;
                return view($viewPath, compact('item', 'withStatus'))->render();
            })
            ->addColumn('insurance_details', function ($item) use ($viewPath) {
                return  $item->insurance_details;
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
