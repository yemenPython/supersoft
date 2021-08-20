<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ReturnedReceiptLibrary extends Model
{
    protected $fillable = ['name', 'returned_receipt_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'returned_receipt_libraries';

    public function returnedReceipt()
    {
        return $this->belongsTo(ReturnedSaleReceipt::class, 'returned_receipt_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
