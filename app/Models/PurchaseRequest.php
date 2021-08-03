<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseRequest extends Model
{
    use SoftDeletes;

    protected $fillable = ['number', 'date', 'time', 'branch_id', 'user_id', 'status', 'type', 'request_for', 'requesting_party',
        'date_from', 'date_to', 'description', 'library_path'];

    protected $table = 'purchase_requests';

    protected $dates = ['deleted_at'];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'branch_id' => 'branch_id',
        'date' => 'date',
        'number' => 'number',
        'different_days' => 'different_days',
        'remaining_days' => 'remaining_days',
        'status' => 'status',
        'executionStatus' => 'executionStatus',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id')->withTrashed();
    }

    public function items()
    {
        return $this->hasMany(PurchaseRequestItem::class, 'purchase_request_id');
    }

    public function getSpecialNumberAttribute()
    {
        return $this->number . '_#';
    }

    public function execution()
    {
        return $this->hasOne(PurchaseRequestExecution::class, 'purchase_request_id');
    }

    function files()
    {
        return $this->hasMany(PurchaseRequestLibrary::class, 'purchase_request_id');
    }

    public function getDifferentDaysAttribute()
    {
        $startDate = Carbon::create($this->date_from);
        $endDate = Carbon::create($this->date_to);
        return $endDate->diffInDays($startDate);
    }

    public function getRemainingDaysAttribute()
    {
        $dateNowFormat = Carbon::now()->format('Y-m-d');

        $dateNow = Carbon::create($dateNowFormat);
        $endDate = Carbon::create($this->date_to);

        $remaining = $dateNow->diffInDays($endDate, false);

        if (intval($remaining) < 0) {
            return 0;
        }

        return $dateNow->diffInDays($endDate, false);
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
}
