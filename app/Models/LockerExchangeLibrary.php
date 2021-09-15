<?php

namespace App\Models;

use App\Models\Banks\BankAccount;
use App\ModelsMoneyPermissions\LockerExchangePermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class LockerExchangeLibrary extends Model
{
    protected $fillable = [
        'name',
        'locker_exchange_id',
        'file_name',
        'extension',
        'title_ar',
        'title_en'
    ];

    protected $table = 'locker_exchange_libraries';

    public function lockerExchange()
    {
        return $this->belongsTo(LockerExchangePermission::class, 'locker_exchange_id');
    }

    public function getTitleAttribute()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
