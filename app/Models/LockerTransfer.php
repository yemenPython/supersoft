<?php

namespace App\Models;

use App\ModelsMoneyPermissions\LockerTransferPivot;
use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LockerTransfer extends Model
{
    use SoftDeletes, LogsActivity;

    protected static $logAttributes = ['amount', 'date', 'created_by'];

    protected static $logOnlyDirty = true;

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'lockerFrom' => 'lockerFrom',
        'lockerTo' => 'lockerTo',
        'amount' => 'amount',
        'createdBy' => 'createdBy',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'options' => 'options'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected $fillable = ['branch_id', 'locker_from_id', 'locker_to_id', 'created_by', 'date', 'amount', 'description', 'library_path'];

    protected $table = 'locker_transfers';

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function lockerFrom()
    {
        return $this->belongsTo(Locker::class, 'locker_from_id')->withTrashed();
    }

    public function lockerTo()
    {
        return $this->belongsTo(Locker::class, 'locker_to_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->withTrashed();
    }

    function locker_transfer_pivot()
    {
        return $this->hasOne(LockerTransferPivot::class, 'locker_transfer_id');
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
        return $this->hasMany(LockerTransferLibrary::class, 'locker_transfer_id');
    }
}
