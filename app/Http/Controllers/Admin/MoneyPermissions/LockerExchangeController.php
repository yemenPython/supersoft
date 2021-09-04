<?php

namespace App\Http\Controllers\Admin\MoneyPermissions;

use App\ModelsMoneyPermissions\LockerReceivePermission;
use Exception;
use App\Models\Locker;
use App\Models\Account;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\EmployeeData;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\ModelsMoneyPermissions\LockerExchangePermission;
use App\Http\Requests\MoneyPermissions\LockerExchangeRequest;
use App\Http\Requests\MoneyPermissions\LockerExchangeBankRequest;
use Yajra\DataTables\DataTables;

class LockerExchangeController extends Controller
{
    use ExchangeTrait;

    function index(Request $req)
    {
        if (!auth()->user()->can('view_locker_exchange_permissions')) {
            return redirect(route('admin:home'))->with(['authorization' => __('words.unauthorized')]);
        }

        $source = $req->has('source_id') && $req->source_id != '' ? $req->source_id : NULL;
        $receiver = $req->has('receiver_id') && $req->receiver_id != '' ? $req->receiver_id : NULL;
        $status = $req->has('permission_status') && $req->permission_status != '' ? $req->permission_status : NULL;
        $date_from = $req->has('date_from') && $req->date_from != '' ? $req->date_from : NULL;
        $date_to = $req->has('date_to') && $req->date_to != '' ? $req->date_to : date('Y-m-d');
        $key = $req->has('key') && $req->key != '' ? $req->key : NULL;
        $branch = $req->has('branch_id') && authIsSuperAdmin() && $req->branch_id != '' ? $req->branch_id : NULL;
        if (!authIsSuperAdmin()) $branch = auth()->user()->branch_id;

        $search_form = (new CommonLogic)->builde_search_form('locker', route('admin:locker-exchanges.index'));
        $exchanges = LockerExchangePermission::latest()->select(
            'permission_number', 'amount', 'operation_date', 'status', 'created_at', 'updated_at',
            'from_locker_id', 'to_locker_id', 'employee_id', 'id', 'destination_type'
        )
            ->with([
                'fromLocker' => function ($q) {
                    $q->select('id', 'name_ar', 'name_en');
                },
                'toLocker' => function ($q) {
                    $q->select('id', 'name_ar', 'name_en');
                },
                'toBank' => function ($q) {
                    $q->select('id', 'name_ar', 'name_en');
                },
                'employee' => function ($q) {
                    $q->select('id', 'name_ar', 'name_en');
                },
                'branch' => function ($q) {
                    $q->select('id', 'name_ar', 'name_en');
                }
            ])
            ->when($source, function ($q) use ($source) {
                $q->where('from_locker_id', $source);
            })
            ->when($receiver, function ($q) use ($receiver) {
                $q->where('to_locker_id', $receiver);
            })
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->when($branch, function ($q) use ($branch) {
                $q->where('branch_id', $branch);
            })
            ->when($date_from, function ($q) use ($date_from, $date_to) {
                $q->where('operation_date', '>=', $date_from)->where('operation_date', '<=', $date_to);
            })
            ->when($key, function ($q) use ($key) {
                $q->where('permission_number', 'like', "%$key%")->orWhere('operation_date', 'like', "%$key%");
            });
        if ($req->isDataTable) {
            return $this->dataTableColumns($exchanges);
        } else {
            return view('admin.money-permissions.locker-exchange.index', [
                'exchanges' => $exchanges,
                'search_form' => $search_form,
                'js_columns' => LockerExchangePermission::getJsDataTablesColumns(),
            ]);
        }
    }

    private function exchange_maintainable($id)
    {
        $exchange = LockerExchangePermission::find($id);
        if (!$exchange) throw new Exception(__('words.permission-not-exists'));
        if ($exchange->status != 'pending') {
            throw new Exception(__('words.permission-not-pending'));
        }
        return $exchange;
    }

    function update(LockerExchangeRequest $req, $id)
    {
        if (!auth()->user()->can('edit_locker_exchange_permissions')) {
            return redirect(route('admin:locker-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        return $this->run_update($req, $id);
    }

    function updateToBank(LockerExchangeBankRequest $req, $id)
    {
        if (!auth()->user()->can('edit_locker_exchange_permissions')) {
            return redirect(route('admin:locker-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        return $this->run_update($req, $id);
    }

    function create()
    {
        if (!auth()->user()->can('create_locker_exchange_permissions')) {
            return redirect(route('admin:locker-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        $type = isset($_GET['type']) && $_GET['type'] == 'bank';
        $form_action = $type ? route('admin:locker-exchanges.store-to-bank') : route('admin:locker-exchanges.store');
        $validation_class = $type ?
            'App\Http\Requests\MoneyPermissions\LockerExchangeBankRequest' : 'App\Http\Requests\MoneyPermissions\LockerExchangeRequest';
        if (authIsSuperAdmin()) {
            $money_source = Locker::select('id', 'name_en', 'name_ar', 'balance')->get();
            $money_destination = $type ? Account::select('id', 'name_en', 'name_ar', 'balance')->get() : clone $money_source;
        } else {
            $money_source = Locker::select('id', 'name_en', 'name_ar', 'balance')
                ->whereDoesntHave('assigned_users')->get();
            $money_source2 = Locker::select('id', 'name_en', 'name_ar', 'balance')
                ->whereHas('assigned_users', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })->get();
            $money_source = $money_source->merge($money_source2);

            if ($type) {
                $money_destination = Account::select('id', 'name_en', 'name_ar', 'balance')
                    ->whereDoesntHave('assigned_users')->get();
                $money_destination2 = Account::select('id', 'name_en', 'name_ar', 'balance')
                    ->whereHas('assigned_users', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    })->get();
                $money_destination = $money_destination->merge($money_destination2);
            } else {
                $money_destination = clone $money_source;
            }
        }
        $last_locker_exchange = LockerExchangePermission::orderBy('id', 'desc')->select('permission_number')->first();
        $permission_number = $last_locker_exchange ? ((int)$last_locker_exchange->permission_number + 1) : 200000001;
        $employees = EmployeeData::select('id', 'name_ar', 'name_en')->get();
        return view('admin.money-permissions.locker-exchange.create', [
            'money_source' => $money_source,
            'permission_number' => $permission_number,
            'employees' => $employees,
            'money_destination' => $money_destination,
            'input_title' => $type ? __('Account To') : __('Locker To'),
            'select_one_title' => $type ? __('Select Account') : __('Select Locker'),
            'validation_class' => $validation_class,
            'form_action' => $form_action
        ]);
    }

    function store(LockerExchangeRequest $req)
    {
        if (!auth()->user()->can('create_locker_exchange_permissions')) {
            return redirect(route('admin:locker-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        $data = $req->all();
        if (!authIsSuperAdmin()) $data['branch_id'] = auth()->user()->branch_id;
        LockerExchangePermission::create($data);
        return redirect(route('admin:locker-exchanges.index'))->with([
            'message' => __('words.permission-is-created'),
            'alert-type' => 'success'
        ]);
    }

    function storeToBank(LockerExchangeBankRequest $req)
    {
        if (!auth()->user()->can('create_locker_exchange_permissions')) {
            return redirect(route('admin:locker-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        $data = $req->all();
        if (!authIsSuperAdmin()) $data['branch_id'] = auth()->user()->branch_id;
        LockerExchangePermission::create($data);
        return redirect(route('admin:locker-exchanges.index'))->with([
            'message' => __('words.permission-is-created'),
            'alert-type' => 'success'
        ]);
    }

    function show(Request $req, $id)
    {
        if (!auth()->user()->can('view_locker_exchange_permissions')) {
            return response(['message' => __('words.unauthorized')], 400);
        }
        try {
            $exchange = LockerExchangePermission::with([
                'fromLocker' => function ($q) {
                    $q->select('id', 'name_ar', 'name_en');
                },
                'toLocker' => function ($q) {
                    $q->select('id', 'name_ar', 'name_en');
                },
                'toBank' => function ($q) {
                    $q->select('id', 'name_ar', 'name_en');
                },
                'employee' => function ($q) {
                    $q->select('id', 'name_ar', 'name_en');
                },
                'branch'
            ])->findOrFail($id);
        } catch (Exception $e) {
            return response(['message' => __('words.permission-not-exists')], 400);
        }
        $code = view('admin.money-permissions.locker-exchange.show', compact('exchange'))->render();
        return response(['code' => $code]);
    }

    function approve($id)
    {
        if (!auth()->user()->can('approve_locker_exchange_permissions')) {
            return redirect(route('admin:locker-exchanges.index'))->with(['authorization' => __('words.unauthorized')]);
        }
        try {
            DB::beginTransaction();
            $exchange = $this->exchange_maintainable($id);
            $locker = $exchange->fromLocker;
//            $money_permission_account = (new CommonLogic)->account_relation_exists();
//            $locker_account = (new CommonLogic)->get_locker_account_tree($locker);
            $exchange->update(['status' => 'approved']);
//            $locker_process = new LockerProcess($exchange->amount ,$locker);
//            $locker_process->decrement();
//            $restriction_process = new LockerRestrictionProcess($money_permission_account ,$locker_account);
//            $restriction_process->set_exchange_permission($exchange);
//            $restriction_process();
        } catch (Exception $e) {
            DB::rollBack();
            return redirect(route('admin:locker-exchanges.index'))->with([
                'message' => $e->getMessage(),
                'alert-type' => 'error'
            ]);
        }
        DB::commit();
        return redirect(route('admin:locker-exchanges.index'))->with([
            'message' => __('words.permission-is-approved'),
            'alert-type' => 'success'
        ]);
    }

    function load_branch_lockers()
    {
        $branch_id = isset($_GET['branch_id']) && $_GET['branch_id'] != '' ? $_GET['branch_id'] : NULL;
        $type = isset($_GET['type']) && $_GET['type'] == 'bank';
        if (!$branch_id) return response(['message' => __('words.No Data Available')], 400);
        $lockers = (new CommonLogic)->fetch_source_money(new Locker, $branch_id);
        try {
            $lockers[0];
        } catch (Exception $e) {
            return response(['message' => __('words.No Data Available')], 400);
        }
        $from_code = view('admin.money-permissions.source-money-select', [
            'money_source' => $lockers,
            'select_one' => __('Select Locker'),
            'include_balance' => true
        ])->render();
        $to_code = view('admin.money-permissions.source-money-select', [
            'money_source' => $type ? (new CommonLogic)->fetch_source_money(new Account, $branch_id) : $lockers,
            'select_one' => __('Select Locker'),
            'include_balance' => false
        ])->render();
        return response(['from_code' => $from_code, 'to_code' => $to_code]);
    }

    function get_money_sources($exchange)
    {
        if (authIsSuperAdmin()) {
            $money_source = Locker::where('branch_id', $exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')->get();
        } else {
            $money_source = Locker::where('branch_id', $exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                ->whereDoesntHave('assigned_users')->get();
            $money_source2 = Locker::where('branch_id', $exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                ->whereHas('assigned_users', function ($q) {
                    $q->where('user_id', auth()->user()->id);
                })->get();
            $money_source = $money_source->merge($money_source2);
        }
        return $money_source;
    }

    function get_money_destinations($exchange)
    {
        if (authIsSuperAdmin()) {
            $money_destination = $exchange->destination_type != 'bank' ?
                Locker::where('branch_id', $exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')->get()
                : Account::where('branch_id', $exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')->get();
        } else {
            $money_destination = $exchange->destination_type != 'bank' ?
                Locker::where('branch_id', $exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                    ->whereDoesntHave('assigned_users')->get()
                : Account::where('branch_id', $exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                    ->whereDoesntHave('assigned_users')->get();
            $money_destination2 = $exchange->destination_type != 'bank' ?
                Locker::where('branch_id', $exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                    ->whereHas('assigned_users', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    })->get()
                : Account::where('branch_id', $exchange->branch_id)->select('id', 'name_en', 'name_ar', 'balance')
                    ->whereHas('assigned_users', function ($q) {
                        $q->where('user_id', auth()->user()->id);
                    })->get();
            $money_destination = $money_destination->merge($money_destination2);
        }
        return $money_destination;
    }

    function get_invoker_name()
    {
        return StaticNames::LOCKER;
    }

    /**
     * @param Builder $items
     * @return mixed
     * @throws Throwable|Throwable
     */
    private function dataTableColumns(Builder $items)
    {
        $viewPath = 'admin.money-permissions.locker-exchange.options-datatable.options';
        return DataTables::of($items)->addIndexColumn()
            ->addColumn('permission_number', function ($item) use ($viewPath) {
                return $item->permission_number;
            })
            ->addColumn('fromLocker', function ($item) use ($viewPath) {
                return optional($item->fromLocker)->name;
            })
            ->addColumn('toBank', function ($item) use ($viewPath) {
                $withTo = true;
                return view($viewPath, compact('item', 'withTo'))->render();
            })
            ->addColumn('destination_type', function ($item) {
                return __('words.' . $item->destination_type);
            })
            ->addColumn('employee', function ($item) use ($viewPath) {
                return optional($item->employee)->name;
            })
            ->addColumn('amount', function ($item) use ($viewPath) {
                return $item->amount;
            })
            ->addColumn('operation_date', function ($item) use ($viewPath) {
                return $item->operation_date;
            })
            ->addColumn('status', function ($item) use ($viewPath) {
                return $item->render_status($item->status, __('words.' . $item->status));
            })
            ->addColumn('created_at', function ($item) {
                return $item->created_at->format('y-m-d h:i:s A');
            })
            ->addColumn('updated_at', function ($item) {
                return $item->updated_at->format('y-m-d h:i:s A');
            })->addColumn('actions', function ($item) use ($viewPath) {
                $withActions = true;
                return view($viewPath, compact('item', 'withActions'))->render();
            })->addColumn('options', function ($item) use ($viewPath) {
                $withOptions = true;
                return view($viewPath, compact('item', 'withOptions'))->render();
            })->rawColumns(['actions'])->rawColumns(['options'])->escapeColumns([])->make(true);
    }

    function getLockerExchangeTOReceive(int $id)
    {
        $exchangePermission = LockerExchangePermission::findOrFail($id);
        $last_locker_receive = LockerReceivePermission::orderBy('id', 'desc')->select('permission_number')->first();
        $permission_number = $last_locker_receive ? ((int)$last_locker_receive->permission_number + 1) : 200000001;
        $employees = EmployeeData::select('id', 'name_ar', 'name_en')->get();
        $view = view('admin.money-permissions.locker-exchange.form-create-locker-reciver', [
            'exchange' => $exchangePermission,
            'permission_number' => $permission_number,
            'employees' => $employees,
        ])->render();
        return response()->json([
            'data' => $view,
        ]);
    }
}
