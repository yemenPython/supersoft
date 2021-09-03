<?php

namespace App\Models;

use App\Model\LockerUsers;
use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Locker extends Model
{
    use SoftDeletes, LogsActivity, ColumnTranslation;

    protected $fillable = ['name_en','name_ar','branch_id','status','balance','description','special'];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $table = 'lockers';

    protected $dates = ['deleted_at'];

    protected static $logAttributes = ['name_ar','name_en','status','balance','special'];

    protected static $logOnlyDirty = true;

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'branch_id' => 'branch_id',
        'name' => 'name',
        'balance' => 'balance',
        'status' => 'status',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function branch(){
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function getActiveAttribute(){
        return $this->status == 1? __('Active'): __('inActive');
    }

    public function users(){
        return $this->belongsToMany(User::class, 'lockers_users');
    }

    public function revenueReceipts(){
        return $this->hasMany(RevenueReceipt::class, 'locker_id');
    }

    public function expensesReceipts(){
        return $this->hasMany(ExpensesReceipt::class, 'locker_id');
    }

    public function accessible_users() {
        return $this->hasMany(\App\Model\LockerUsers::class ,'locker_id');
    }

    function get_trans_name() {
        return app()->getLocale() == 'ar' ? $this->name_ar : $this->name_en;
    }

    function assigned_users() {
        return $this->hasMany(LockerUsers::class ,'locker_id');
    }

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        if (!authIsSuperAdmin()) {
            unset(self::$dataTableColumns['branch_id']);
        }
        return self::$dataTableColumns;
    }

    function files()
    {
        return $this->hasMany(LockerLibrary::class, 'locker_id');
    }

    public function lockerOpeningBalanceItems(): HasMany
    {
        return $this->hasMany(LockerOpeningBalanceItem::class, 'locker_id');
    }
}
