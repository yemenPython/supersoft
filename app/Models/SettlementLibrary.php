<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SettlementLibrary extends Model
{
    protected $fillable = ['name', 'settlement_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'settlement_libraries';

    public function settlement()
    {
        return $this->belongsTo(Settlement::class, 'settlement_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
