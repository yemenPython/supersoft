<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class LockerLibrary extends Model
{
    protected $fillable = ['name', 'locker_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'locker_libraries';

    public function locker()
    {
        return $this->belongsTo(Locker::class, 'locker_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
