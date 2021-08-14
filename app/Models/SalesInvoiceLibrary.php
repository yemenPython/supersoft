<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SalesInvoiceLibrary extends Model
{
    protected $fillable = ['name', 'sales_invoice_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'sales_invoice_libraries';

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
