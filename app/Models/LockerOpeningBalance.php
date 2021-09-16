<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LockerOpeningBalance extends Model
{
    protected $table = 'locker_opening_balances';

    protected $fillable = [
        'branch_id',
        'user_id',
        'number',
        'date',
        'time',
        'current_total',
        'added_total',
        'total',
        'notes',
        'status',
    ];

    protected $with = ['items'];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'branch_id' => 'branch_id',
        'number' => 'number',
        'total' => 'total',
        'status' => 'status',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LockerOpeningBalanceItem::class, 'locker_opening_balance_id');
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

    public function files(): HasMany
    {
        return $this->hasMany(LockerOpeningBalanceLibrary::class, 'locker_opening_balance_id');
    }
}

