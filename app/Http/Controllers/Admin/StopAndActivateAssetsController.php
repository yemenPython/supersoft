<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Asset\ConsumptionAssetRequest;
use App\Http\Requests\Admin\Asset\PurchaseAssetRequest;
use App\Http\Requests\Admin\Asset\StopAndActivateAssetRequest;
use App\Models\Asset;
use App\Models\AssetEmployee;
use App\Models\AssetGroup;
use App\Models\AssetReplacementItem;
use App\Models\AssetType;
use App\Models\ConsumptionAsset;
use App\Models\ConsumptionAssetItem;
use App\Models\EmployeeData;
use App\Models\PurchaseAsset;
use App\Models\PurchaseAssetItem;
use App\Models\SaleAssetItem;
use App\Models\StopAndActivateAsset;
use Carbon\Carbon;
use Exception;
use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class StopAndActivateAssetsController extends Controller
{

    public $lang;


    public function __construct()
    {

        $this->lang = App::getLocale();
    }

    public function index(Request $request)
    {
        if ($request->isDataTable) {
            $StopAndActivateAsset = StopAndActivateAsset::select([
                'id',
                'branch_id',
                'date',
                'user_id',
                'asset_id',
                'status',
                'created_at',
                'updated_at'
            ])->with(['asset'=>function($query){
                $query->select(['id','name_ar','name_en']);
            }]);

            if ($request->has('branch_id') && !empty($request['branch_id']))
                $StopAndActivateAsset->where('branch_id', $request['branch_id']);
            if ($request->has('asset_id') && !empty($request->asset_id))
                $StopAndActivateAsset->where('asset_id', $request['asset_id']);
            if ($request->has('status') && !empty($request->status))
                $StopAndActivateAsset->where('status', $request['status']);

            whereBetween($StopAndActivateAsset, 'date', $request->date_from, $request->date_to);
            return DataTables::of($StopAndActivateAsset)
                ->addIndexColumn()
                ->addColumn('branch_id', function ($asset) {
                    return '<span class="text-danger">' . optional($asset->branch)->name . '</span>';

                })
                ->addColumn('asset_id', function ($asset) {
                    return '<span class="text-danger">' . optional($asset->asset)->name . '</span>';

                })
                ->addColumn('date', function ($consumptionAsset) {
                    return '<span class="text-danger">' . $consumptionAsset->date;
                })
                ->addColumn('status', function ($consumptionAsset) {
                    return $consumptionAsset->status;

                })
                ->addColumn('created_at', function ($consumptionAsset) {
                    return $consumptionAsset->created_at;
                })
                ->addColumn('updated_at', function ($consumptionAsset) {
                    return $consumptionAsset->updated_at;
                })
                ->addColumn('action', function ($consumptionAsset) {
                    return '
                      <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i> ' . __("Options") . '<span class="caret"></span></button>
                                          <ul class="dropdown-menu dropdown-wg">
                                            <li> <a class="btn btn-wg-edit hvr-radial-out" href="' . route("admin:stop_and_activate_assets.edit", $consumptionAsset->id) . '">
    <i class="fa fa-edit"></i>  ' . __('Edit') . '
        </a></li>
        <li class="btn-style-drop">
        <button type="button" class="btn btn-wg-delete hvr-radial-out"  onclick="confirmDelete(' . $consumptionAsset->id . ')">
            <i class="fa fa-trash"></i>  ' . __('Delete') . '
        </button>

        <form style="display: none" method="POST" id="confirmDelete' . $consumptionAsset->id . '" action="' . route('admin:stop_and_activate_assets.destroy', $consumptionAsset->id) . '">
            <input type="hidden" name="_method" value="DELETE">
           ' . @csrf_field() . '
        </form>
        </li>
        <li>
        <a style="cursor:pointer" class="btn btn-print-wg text-white  "
           data-toggle="modal" onclick="getPrintData(' . $consumptionAsset->id . ')"
           data-target="#boostrapModal" title="' . __('print') . '">
            <i class="fa fa-print"></i> ' . __('Print') . '</a>
        </li>
          </ul> </div>
                 ';
                })->addColumn('options', function ($consumptionAsset) {
                    return '
                    <form action=' . route("admin:stop_and_activate_assets.deleteSelected") . ' method="post" id="deleteSelected">
                    ' . @csrf_field() . '
        <div class="checkbox danger">
        <input type="checkbox" name="ids[]" value="' . $consumptionAsset->id . '" id="checkbox-' . $consumptionAsset->id . '">
        <label for="checkbox-' . $consumptionAsset->id . '"></label>
          </div>
            </form>
                    ';
                })
                ->rawColumns(['action'])
                ->rawColumns(['actions'])
                ->escapeColumns([])
                ->make(true);
        } else {

            $js_columns = [
                'DT_RowIndex' => 'DT_RowIndex',
                'asset_id' => 'stop_activate_assets.asset_id',
                'date' => 'stop_activate_assets.date',
                'status' => 'stop_activate_assets.status',
                'created_at' => 'stop_activate_assets.created_at',
                'updated_at' => 'stop_activate_assets.updated_at',
                'action' => 'action',
                'options' => 'options'
            ];

            if (authIsSuperAdmin()) {

                $js_columns = [
                    'DT_RowIndex' => 'DT_RowIndex',
                    'branch_id' => 'stop_activate_assets.branch_id',
                    'asset_id' => 'stop_activate_assets.asset_id',
                    'date' => 'stop_activate_assets.date',
                    'status' => 'stop_activate_assets.status',
                    'created_at' => 'stop_activate_assets.created_at',
                    'updated_at' => 'stop_activate_assets.updated_at',
                    'action' => 'action',
                    'options' => 'options'
                ];
            }

            $assets = Asset::all();
            $branches = Branch::all()->pluck('name', 'id');
            $assetsGroups = AssetGroup::select(['id', 'name_ar', 'name_en'])->get();
            $numbers = ConsumptionAsset::select('number')->distinct()->orderBy('number', 'asc')->get();
            return view('admin.stop_and_activate_assets.index', compact('js_columns', 'assets', 'branches', 'assetsGroups', 'numbers'));
        }

    }

    public function create(Request $request)
    {
        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;
        $assetsGroups = AssetGroup::where('branch_id', $branch_id)->get();
        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();
        $status = $request->status;
        $assets = Asset::where('branch_id', $branch_id);
        if ($status =='activate'){
            $assets->where('status','=','stop');
        }
        $assets = $assets->get();
        return view('admin.stop_and_activate_assets.create', compact('data', 'assetsGroups', 'assets','status'));
    }

    public function store(StopAndActivateAssetRequest $request)
    {
        DB::beginTransaction();
        try {
            $data = [
                'date' => $request->date,
                'user_id' => auth()->id(),
                'asset_id'=>$request->asset_id,
                'status'=>$request->status
            ];
            $data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;
            $record = StopAndActivateAsset::create($data);
            $record->status =='stop'? $record->asset()->update(['status'=>$request->status,'asset_status'=>4]):$record->asset()->update(['status'=>$request->status]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to(route('admin:stop_and_activate_assets.create',['branch_id'=>$request->branch_id,'status'=>$request->status]))
                ->with(['message' => __($e->getMessage()), 'alert-type' => 'error']);
        }

        return redirect()->to(route('admin:stop_and_activate_assets.index'))
            ->with(['message' => __('words.stop_and_activate_assets-created'), 'alert-type' => 'success']);

    }

    public function show(StopAndActivateAsset $stopAndActivateAsset)
    {
        return back();
    }

    public function edit(StopAndActivateAsset $stopAndActivateAsset)
    {
        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();
        $branch_id = $stopAndActivateAsset->branch_id;
        $assetsGroups = AssetGroup::where('branch_id', $branch_id)->get();
        $stop_and_activate_assets = $stopAndActivateAsset;
        $status = $stopAndActivateAsset->status;
        $assets = Asset::where('branch_id', $branch_id);
        if ($status =='activate'){
            $assets->where('status','=','stop');
        }
        $assets = $assets->get();
        return view('admin.stop_and_activate_assets.edit', compact('data', 'stop_and_activate_assets', 'assets', 'assetsGroups','status'));
    }

    public function update(StopAndActivateAssetRequest $request, StopAndActivateAsset $stopAndActivateAsset)
    {

        DB::beginTransaction();
        try {
            $data = [
                'date' => $request->date,
                'user_id' => auth()->id(),
                'asset_id'=>$request->asset_id,
                'status'=>$request->status
            ];
            $data['branch_id'] = authIsSuperAdmin() ? $request['branch_id'] : auth()->user()->branch_id;
            $stopAndActivateAsset->update($data);
            $stopAndActivateAsset->status =='stop'? $stopAndActivateAsset->asset()->update(['status'=>$request->status,'asset_status'=>4]):$stopAndActivateAsset->asset()->update(['status'=>$request->status]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->to(route('admin:stop_and_activate_assets.edit', $stopAndActivateAsset->id))
                ->with(['message' => __($e->getMessage()), 'alert-type' => 'error']);
        }

        return redirect()->to(route('admin:stop_and_activate_assets.index'))
            ->with(['message' => __('words.stop_and_activate_assets-created'), 'alert-type' => 'success']);

    }

    public function destroy(StopAndActivateAsset $stopAndActivateAsset)
    {
        $status = $stopAndActivateAsset->status =='stop'?'activate':'stop';
        $stopAndActivateAsset->status =='stop'? $stopAndActivateAsset->asset()->update(['status'=>$status,'asset_status'=>1]):$stopAndActivateAsset->asset()->update(['status'=>$status,'asset_status'=>4]);
        if ($stopAndActivateAsset->status =='stop' && StopAndActivateAsset::where('asset_id',$stopAndActivateAsset->asset_id)->latest()->first()->status =='activate'){
            return redirect()->to( route( 'admin:stop_and_activate_assets.index' ) )
                ->with( ['message' => __( 'words.Can not delete this stop asset' ), 'alert-type' => 'error'] );
        }
       $consumption =  ConsumptionAsset::where('date_to','>=',$stopAndActivateAsset->date)->whereHas('items',function ($query)use($stopAndActivateAsset){
            $query->where('asset_id',$stopAndActivateAsset->asset_id);
        })->where('created_at','>',$stopAndActivateAsset->created_at)->first();
        if (!empty($consumption) || AssetReplacementItem::where( 'asset_id', $stopAndActivateAsset->asset_id )->where('created_at','>',$stopAndActivateAsset->created_at)->exists()){
            return redirect()->to( route( 'admin:stop_and_activate_assets.index' ) )
                ->with( ['message' => __( 'words.Can not delete this stop asset' ), 'alert-type' => 'error'] );
        }
        $stopAndActivateAsset->delete();
        return redirect()->back()
            ->with(['message' => __('words.stop_and_activate_assets-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (isset($request->ids)) {

            foreach (array_unique($request->ids) as $invoiceId) {

                $stopAndActivateAsset = StopAndActivateAsset::find($invoiceId);
                $status = $stopAndActivateAsset->status =='stop'?'activate':'stop';
                $stopAndActivateAsset->status =='stop'? $stopAndActivateAsset->asset()->update(['status'=>$status,'asset_status'=>1]):$stopAndActivateAsset->asset()->update(['status'=>$status,'asset_status'=>4]);
                if ($stopAndActivateAsset->status =='stop' && StopAndActivateAsset::where('asset_id',$stopAndActivateAsset->asset_id)->latest()->first()->status =='activate'){
                    return redirect()->to( route( 'admin:stop_and_activate_assets.index' ) )
                        ->with( ['message' => __( 'words.Can not delete this stop asset' ), 'alert-type' => 'error'] );
                }
                $consumption =  ConsumptionAsset::where('date_to','>=',$stopAndActivateAsset->date)->whereHas('items',function ($query)use($stopAndActivateAsset){
                    $query->where('asset_id',$stopAndActivateAsset->asset_id);
                })->first();

                if (!empty($consumption) || AssetReplacementItem::where( 'asset_id', $stopAndActivateAsset->asset_id )->exists()){
                    return redirect()->to( route( 'admin:stop_and_activate_assets.index' ) )
                        ->with( ['message' => __( 'words.Can not delete this stop asset' ), 'alert-type' => 'error'] );
                }
                $stopAndActivateAsset->delete();
            }

            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }

        return redirect()->back()
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getAssetsByAssetId(Request $request): JsonResponse
    {
        if (is_null($request->asset_id)) {
            return response()->json(__('please select valid Asset'), 400);
        }
        $asset = Asset::find($request->asset_id);
        if ($asset->status == $request->status) {
            return response()->json(__('Asset already '.$request->status), 400);
        }
        return response()->json([],200);
    }
    public function getAssetsByAssetGroup(Request $request): JsonResponse
    {
        if (empty($request->branch_id) ){
            return response()->json(__('please select valid Branch'), 400);
        }
        $assets = Asset::query();
        if (!$request->asset_group_id) {
            $assets = $assets->where('branch_id' , $request->branch_id);
        }
        if ($request->asset_group_id && $request->branch_id) {
            $assets = $assets->where([
                'asset_group_id' => $request->asset_group_id,
                'branch_id' => $request->branch_id,
            ]);
        }
        if (!empty($request->status) && $request->status =='activate'){
            $assets = $assets->where('status','=','stop');
        }
        $assets = $assets->get();
        $htmlAssets = '<option value="">'.__('Select Assets').'</option>';
        foreach ($assets as $asset) {
            $htmlAssets .= '<option value="' . $asset->id . '">' . $asset->name . '</option>';
        }
        return response()->json( [
            'assets' => $htmlAssets,
        ] );
    }

}
