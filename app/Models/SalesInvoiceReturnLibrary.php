<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SalesInvoiceReturnLibrary extends Model
{
    protected $fillable = ['name', 'sales_return_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'sales_invoice_return_libraries';

    public function salesInvoiceReturn()
    {
        return $this->belongsTo(SalesInvoiceReturn::class, 'sales_return_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
