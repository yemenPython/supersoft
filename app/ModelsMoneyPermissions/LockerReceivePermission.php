<?php

namespace App\ModelsMoneyPermissions;

use App\Models\Branch;
use App\Models\EmployeeData;
use Illuminate\Database\Eloquent\Model;
use App\AccountingModule\Models\CostCenter;

class LockerReceivePermission extends Model
{
    use StatusRenderTrait;

    protected $fillable = [
        'locker_exchange_permission_id', 'permission_number', 'amount', 'branch_id', 'source_type',
        'employee_id', 'operation_date', 'status', 'note', 'cost_center_id'
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'permission_number' => 'permission_number',
        'locker' => 'locker',
        'source_type' => 'source_type',
        'employee' => 'employee',
        'amount' => 'amount',
        'operation_date' => 'operation_date',
        'status' => 'status',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'actions' => 'actions',
        'options' => 'options',
    ];

    function exchange_permission()
    {
        return $this->belongsTo(LockerExchangePermission::class, 'locker_exchange_permission_id');
    }

    function bank_exchange_permission()
    {
        return $this->belongsTo(BankExchangePermission::class, 'locker_exchange_permission_id');
    }

    function employee()
    {
        return $this->belongsTo(EmployeeData::class, 'employee_id');
    }

    function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    function cost_center()
    {
        return $this->belongsTo(CostCenter::class, 'cost_center_id');
    }

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }
}
