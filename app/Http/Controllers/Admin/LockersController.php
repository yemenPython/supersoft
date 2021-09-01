<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Lockers\CreateLockersRequest;
use App\Http\Requests\Admin\Lockers\UpdateLockerRequest;
use App\Models\Branch;
use App\Models\Locker;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;

class LockersController extends Controller
{
    public function __construct()
    {
//        $this->middleware('permission:view_lockers');
//        $this->middleware('permission:create_lockers',['only'=>['create','store']]);
//        $this->middleware('permission:update_lockers',['only'=>['edit','update']]);
//        $this->middleware('permission:delete_lockers',['only'=>['destroy','deleteSelected']]);
    }

    public function index(Request $request)
    {
        if (!auth()->user()->can('view_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }
        $lockers = Locker::orderBy('id','DESC');
        if($request->filled('locker_id'))
            $lockers->where('id', $request->locker_id);

        if($request->filled('branchId'))
            $lockers->where('branch_id',$request['branch_id']);

        if($request->has('active') && $request['active'] != '')
            $lockers->where('status',1);

        if($request->has('inactive') && $request['inactive'] != '') {
            $lockers->where('status',0);
        }
        if ($request->isDataTable) {
            return $this->dataTableColumns($lockers);
        } else {
            return view('admin.lockers.index', [
                'data' => $lockers,
                'js_columns' => Locker::getJsDataTablesColumns(),
            ]);
        }
    }

    public function create()
    {
        if (!auth()->user()->can('create_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $users = User::orderBy('id','ASC')->branch()->get()->pluck('name','id');
        return view('admin.lockers.create',compact('branches','users'));
    }

    public function store(CreateLockersRequest $request)
    {
        if (!auth()->user()->can('create_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();
            $data['name_en'] = $request->filled('name_en') ? $request->name_en : $request->name_ar;
            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            $data['special'] = 0;

            if($request->has('special'))
                $data['special'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            $locker = Locker::create($data);

            if($request->has('special')){
                $locker->users()->attach($request['users']);
            }

        }catch (\Exception $e){
            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:lockers.index'))
            ->with(['message' => __('words.locker-created'),'alert-type'=>'success']);
    }

    public function show(Locker $locker)
    {
        if (!auth()->user()->can('view_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        return view('admin.lockers.show',compact('locker'));
    }

    public function edit(Locker $locker)
    {
        if (!auth()->user()->can('update_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if($locker->special && !in_array( auth()->id(), $locker->users->pluck('id')->toArray()) && !authIsSuperAdmin() ){

            return redirect()->back()
                ->with(['message' => __('words.cant-access-page'),'alert-type'=>'error']);
        }

        $branches = Branch::all()->pluck('name','id');
        $users = User::orderBy('id','ASC')->branch()->pluck('name','id');
        return view('admin.lockers.edit',compact('branches','locker','users'));
    }

    public function update(UpdateLockerRequest $request, Locker $locker)
    {
        if (!auth()->user()->can('update_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        try{
            $data = $request->validated();
            $data['name_en'] = $request->filled('name_en') ? $request->name_en : $request->name_ar;
            $data['status'] = 0;

            if($request->has('status'))
                $data['status'] = 1;

            $data['special'] = 0;

            if($request->has('special'))
                $data['special'] = 1;

            if(!authIsSuperAdmin())
                $data['branch_id'] = auth()->user()->branch_id;

            if($locker->revenueReceipts->count() || $locker->expensesReceipts->count()){
                $data['balance'] = $locker->balance;
            }

            $locker->update($data);

            if($request->has('special')){
                $locker->users()->sync($request['users']);
            }else{

                $locker->users()->detach();
            }

        }catch (\Exception $e){

            return redirect()->back()
                ->with(['message' => __('words.back-support'),'alert-type'=>'error']);
        }

        return redirect(route('admin:lockers.index'))
            ->with(['message' => __('words.locker-updated'),'alert-type'=>'success']);
    }

    public function destroy(Locker $locker)
    {
        if (!auth()->user()->can('delete_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if($locker->special && !in_array( auth()->id(), $locker->users->pluck('id')->toArray() ) && !authIsSuperAdmin()){

            return redirect()->back()
                ->with(['message' => __('words.cant-access-page'),'alert-type'=>'error']);
        }

        if($locker->revenueReceipts || $locker->expensesReceipts){

            return redirect()->back()
                ->with(['message' => __('words.this locker has transaction'),'alert-type'=>'error']);
        }

        $locker->delete();
        return redirect(route('admin:lockers.index'))
            ->with(['message' => __('words.locker-deleted'),'alert-type'=>'success']);
    }

    public function deleteSelected(Request $request)
    {
        if (!auth()->user()->can('delete_lockers')) {
            return redirect()->back()->with(['authorization' => 'error']);
        }

        if (isset($request->ids)) {

            Locker::where(function ($q){

                $q->orWhereDoesntHave('revenueReceipts');

                $q->orWhereDoesntHave('expensesReceipts');

            })->whereIn('id', $request->ids)->delete();

            return redirect(route('admin:lockers.index'))
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);

        }
        return redirect(route('admin:lockers.index'))
            ->with(['message' => __('words.select-one-least'), 'alert-type' => 'error']);
    }

    public function getUsers(Request $request){
        $this->validate($request,[
           'branch_id' => 'required|integer|exists:branches,id'
        ]);

        $users = User::where('branch_id', $request['branch_id'])->pluck('name','id');

        return view('admin.lockers.selected_users', compact('users'));
    }

    public function dataByBranch(Request $request){

        $lockers_search = Locker::where('branch_id', $request['id'])->get()->pluck('name','id');
        return view('admin.lockers.ajax_search',compact('lockers_search'));
    }

    private function dataTableColumns(Builder $items)
    {
        $view = 'admin.lockers.dataTable.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn( 'branch_id', function ($item) use ($view) {
                $withBranch = true;
                return view($view, compact('item', 'withBranch'))->render();
            })
            ->addColumn( 'name', function ($item) use ($view) {
               return $item->name;
            })
            ->addColumn('balance', function ($item) use ($view) {
                $withBalance = true;
                return view($view, compact('item', 'withBalance'))->render();
            })
            ->addColumn('status', function ($item) use ($view) {
                $withStatus = true;
                return view($view, compact('item', 'withStatus'))->render();
            })
            ->addColumn('created_at', function ($item) use ($view) {
                return $item->created_at->format('y-m-d h:i:s A');
            })
            ->addColumn('updated_at', function ($item) use ($view) {
                return $item->updated_at->format('y-m-d h:i:s A');
            })
            ->addColumn('action', function ($item) use ($view) {
                $withActions = true;
                return view($view, compact('item', 'withActions'))->render();
            })->addColumn('options', function ($item) use ($view) {
                $withOptions = true;
                return view($view, compact('item', 'withOptions'))->render();
            })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
    }
}
