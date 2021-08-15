<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class PurchaseInvoiceLibrary extends Model
{
    protected $fillable = ['name', 'purchase_invoice_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'purchase_invoice_libraries';

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
