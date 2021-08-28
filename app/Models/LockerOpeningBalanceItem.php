<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class LockerOpeningBalanceItem extends Model
{
    protected $table = 'locker_opening_balance_items';

    protected $fillable = [
        'locker_id',
        'locker_opening_balance_id',
        'current_balance',
        'added_balance',
        'total',
        'currency_id',
    ];

    public function locker(): BelongsTo
    {
        return $this->belongsTo(Locker::class, 'locker_id');
    }

    public function currency(): HasOne
    {
        return $this->hasOne(Currency::class, 'currency_id');
    }
}




