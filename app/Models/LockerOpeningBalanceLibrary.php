<?php

namespace App\Models;

use App\Models\Banks\BankAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

class LockerOpeningBalanceLibrary extends Model
{
    protected $fillable = [
        'name',
        'locker_opening_balance_id',
        'file_name',
        'extension',
        'title_ar',
        'title_en'
    ];

    protected $table = 'locker_opening_balance_libraries';

    public function lockerOpeningBalance(): BelongsTo
    {
        return $this->belongsTo(LockerOpeningBalance::class, 'locker_opening_balance_id');
    }

    public function getTitleAttribute()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
