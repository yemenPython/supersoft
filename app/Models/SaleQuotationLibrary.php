<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SaleQuotationLibrary extends Model
{
    protected $fillable = ['name', 'sale_quotation_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'sale_quotation_libraries';

    public function saleQuotation()
    {
        return $this->belongsTo(SaleQuotation::class, 'sale_quotation_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
