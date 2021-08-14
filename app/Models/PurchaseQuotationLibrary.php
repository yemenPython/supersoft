<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class PurchaseQuotationLibrary extends Model
{
    protected $fillable = ['name', 'purchase_quotation_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'purchase_quotation_libraries';

    public function purchaseQuotation()
    {
        return $this->belongsTo(PurchaseQuotation::class, 'purchase_quotation_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
