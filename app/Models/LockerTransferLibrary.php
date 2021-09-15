<?php

namespace App\Models;

use App\ModelsMoneyPermissions\LockerReceivePermission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\App;

class LockerTransferLibrary extends Model
{
    protected $fillable = [
        'name',
        'locker_transfer_id',
        'file_name',
        'extension',
        'title_ar',
        'title_en'
    ];

    protected $table = 'locker_transfer_libraries';

    public function lockerTransfer(): BelongsTo
    {
        return $this->belongsTo(LockerTransfer::class, 'locker_transfer_id');
    }

    public function getTitleAttribute()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
