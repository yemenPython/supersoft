<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class OpeningBalanceLibrary extends Model
{
    protected $fillable = ['name', 'opening_balance_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'opening_balance_libraries';

    public function openingBalance()
    {
        return $this->belongsTo(OpeningBalance::class, 'opening_balance_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
