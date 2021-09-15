<?php
namespace App\ModelsMoneyPermissions;

use App\Models\Branch;
use App\Models\Locker;
use App\Models\Account;
use App\Models\EmployeeData;
use App\Models\LockerExchangeLibrary;
use Illuminate\Database\Eloquent\Model;
use App\AccountingModule\Models\CostCenter;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LockerExchangePermission extends Model
{
    use StatusRenderTrait;

    protected $fillable = [
        'permission_number' ,'locker_receive_permission_id' ,'from_locker_id' ,'to_locker_id' ,'destination_type' ,
        'amount' ,'employee_id' ,'operation_date' ,'status' ,'branch_id' ,'note' ,'cost_center_id', 'library_path'
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'permission_number' => 'permission_number',
        'fromLocker' => 'fromLocker',
        'toBank' => 'toBank',
        'destination_type' => 'destination_type',
        'employee' => 'employee',
        'amount' => 'amount',
        'operation_date' => 'operation_date',
        'status' => 'status',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'actions' => 'actions',
        'options' => 'options',
    ];

    function fromLocker() {
        return $this->belongsTo(Locker::class ,'from_locker_id');
    }

    function toLocker() {
        return $this->belongsTo(Locker::class ,'to_locker_id');
    }

    function toBank() {
        return $this->belongsTo(Account::class ,'to_locker_id');
    }

    function employee() {
        return $this->belongsTo(EmployeeData::class ,'employee_id');
    }

    function branch() {
        return $this->belongsTo(Branch::class ,'branch_id');
    }

    function cost_center() {
        return $this->belongsTo(CostCenter::class ,'cost_center_id');
    }

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }

    public function files(): HasMany
    {
        return $this->hasMany(LockerExchangeLibrary::class, 'locker_exchange_id');
    }
}
