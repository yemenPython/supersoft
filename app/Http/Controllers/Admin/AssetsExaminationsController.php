<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Asset\AssetExaminationRequest;
use App\Models\AssetExamination;
use App\Models\Asset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Throwable;
use Yajra\DataTables\DataTables;

class AssetsExaminationsController extends Controller
{

    public function __construct()
    {
//        $this->middleware('permission:view_currencies');
//        $this->middleware('permission:create_currencies',['only'=>['create','store']]);
//        $this->middleware('permission:update_currencies',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_currencies',['only'=>['destroy','deleteSelected']]);
    }

    public function index(asset $asset,Request $request)
    {
        $assetsExaminations = AssetExamination::where("asset_id" , $asset->id)->orderBy('id' ,'desc');
        if ($request->has( 'name' ) && $request['name'] != '')
            $assetsExaminations->where( 'id',$request['name'] );

        if ($request->has( 'start_date' ) && $request['start_date'] != '')
            $assetsExaminations->where( 'start_date', $request->start_date );

        if ($request->has( 'end_date' ) && $request['end_date'] != '')
            $assetsExaminations->where( 'end_date', $request->end_date );

        if ($request->has( 'active' ) && $request['active'] != '')
            $assetsExaminations->where( 'status', '1' );

        if ($request->has( 'inactive' ) && $request['inactive'] != '')
            $assetsExaminations->where( 'status', '0' );

        if ($request->isDataTable) {
            try {
                return $this->dataTableColumns($assetsExaminations);
            } catch (Throwable $e) {
            }
        } else {
            return view('admin.assetsExaminations.index', [
                'asset' => $asset,
                'assetsExaminations' => $assetsExaminations,
                'js_columns' => AssetExamination::getJsDataTablesColumns(),
            ]);
        }
    }


    public function store(AssetExaminationRequest $request )
    {
        // if (!auth()->user()->can('create_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        if($request->asset_examination_id){

            $getExamination = AssetExamination::find($request->asset_examination_id);
            if($getExamination){
                AssetExamination::where("id" , $request->asset_examination_id)->update([
                    "examination_details" => $request->name,
                    "asset_id" => $request->asset_id,
                    "start_date" => $request->start_date,
                    "end_date" => $request->end_date,
                    'status'=>$request->status
                ]);
            }
            return redirect()->to('admin/assets-examinations/'.$request->asset_id)
            ->with(['message' => __('words.asset-examination-updated'), 'alert-type' => 'success']);

        }else{
            AssetExamination::create([
                "examination_details" => $request->name,
                "asset_id" => $request->asset_id,
                "start_date" => $request->start_date,
                "end_date" => $request->end_date,
                'status'=>$request->status
            ]);
            return redirect()->to('admin/assets-examinations/'.$request->asset_id)
            ->with(['message' => __('words.asset-examinations-created'), 'alert-type' => 'success']);
        }


    }



    public function destroy(AssetExamination $assetExamination)
    {
        // // if ($currency->countries()->exists()) {
        // //     return redirect()->back()->with(['message' => __('words.can-not-delete-this-data-cause-there-is-related-data'), 'alert-type' => 'error']);
        // // }
        // if (!auth()->user()->can('delete_currencies')) {
        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        $assetExamination->delete();
        return redirect()->to('admin/assets-examinations/'.$assetExamination->asset_id)
            ->with(['message' => __('words.asset-examinations-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected( Request $request)
    {
        // if (!auth()->user()->can('delete_currencies')) {

        //     return redirect()->back()->with(['authorization' => 'error']);
        // }

        if (isset($request->ids)) {
            $assetsExaminations = AssetExamination::whereIn('id', $request->ids)->delete();

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
        $viewPath = 'admin.assetsExaminations.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('status', function ($item) use ($viewPath) {
                $withStatus = true;
                return view($viewPath, compact('item', 'withStatus'))->render();
            })
            ->addColumn('examination_details', function ($item) use ($viewPath) {
                return  $item->examination_details;
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
