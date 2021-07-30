<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\MaintenanceCard\CreateRequest;
use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\Branch;
use App\Models\MaintenanceCard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;

class MaintenanceCardController extends Controller
{
    public $lang;

    public function __construct()
    {
        $this->lang = App::getLocale();


//        $this->middleware('permission:view_sales_invoices');
//        $this->middleware('permission:create_sales_invoices',['only'=>['create','store']]);
//        $this->middleware('permission:update_sales_invoices',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_sales_invoices',['only'=>['destroy','deleteSelected']]);
    }

    public function index () {

        $data['cards'] = MaintenanceCard::get();

        return view('admin.maintenance_card.index', compact('data'));
    }

    public function create (Request $request) {

        $branch_id = $request->has('branch_id') ? $request['branch_id'] : auth()->user()->branch_id;

        $data['asset_groups'] = AssetGroup::where('branch_id', $branch_id)->select('id','name_' . $this->lang)->get();

        $data['assets'] = Asset::where('branch_id', $branch_id)->select('id','name_' . $this->lang)->get();

        $data['branches'] = Branch::where('status', 1)->select('id', 'name_' . $this->lang)->get();

        return view('admin.maintenance_card.create', compact('data'));
    }

    public function store (CreateRequest $request) {

        try {

            dd($request->all());

            $data = $request->validated();

            $data['receive_status'] = $request->has('receive_status') ? 1 : 0;
            $data['delivery_status'] = $request->has('delivery_status') ? 1 : 0;

        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => 'sorry, please try later', 'alert-type' => 'error']);
        }

        return redirect(route('admin:concessions.index'))->with(['message' => 'Concession created successfully', 'alert-type' => 'success']);

    }

    public function getAssets (Request $request) {

        $validator = Validator::make($request->all(), [
            'group_id'=>'required|integer|exists:assets_groups,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->first(), 400);
        }

        try {

            $assets = Asset::where('asset_group_id', $request['group_id'])->select('id','name_' . $this->lang)->get();

            $view = view('admin.maintenance_card.assets_options', compact('assets'))->render();

        } catch (\Exception $e) {
            return response()->json(['sorry, please try later'], 400);
        }

        return response()->json(['view'=> $view], 200);

    }


}
