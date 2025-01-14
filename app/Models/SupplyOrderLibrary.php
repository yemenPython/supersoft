<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SupplyOrderLibrary extends Model
{
    protected $fillable = ['name', 'supply_order_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'supply_order_libraries';

    public function supplyOrder()
    {
        return $this->belongsTo(SupplyOrder::class, 'supply_order_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
