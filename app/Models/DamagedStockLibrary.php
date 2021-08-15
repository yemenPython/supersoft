<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class DamagedStockLibrary extends Model
{
    protected $fillable = ['name', 'damaged_stock_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'damaged_stock_libraries';

    public function damagedStock()
    {
        return $this->belongsTo(DamagedStock::class, 'damaged_stock_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
