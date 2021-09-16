<?php

namespace App\Models;

use App\ModelsMoneyPermissions\LockerReceivePermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

class LockerRecieverLibrary extends Model
{
    protected $fillable = [
        'name',
        'locker_receive_id',
        'file_name',
        'extension',
        'title_ar',
        'title_en'
    ];

    protected $table = 'locker_reciever_libraries';

    public function lockerReceive(): BelongsTo
    {
        return $this->belongsTo(LockerReceivePermission::class, 'locker_receive_id');
    }

    public function getTitleAttribute()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}

