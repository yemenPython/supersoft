<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SaleSupplyLibrary extends Model
{
    protected $fillable = ['name', 'sale_supply_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'sale_supply_libraries';

    public function saleSupplyOrder()
    {
        return $this->belongsTo(SaleSupplyOrder::class, 'sale_supply_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
