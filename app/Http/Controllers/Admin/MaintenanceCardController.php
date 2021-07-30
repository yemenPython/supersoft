<?php

namespace App\Http\Controllers\Admin;

use App\Models\Asset;
use App\Models\AssetGroup;
use App\Models\Branch;
use App\Models\MaintenanceCard;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;

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
}
