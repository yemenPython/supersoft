<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class PurchaseReceiptLibrary extends Model
{
    protected $fillable = ['name', 'purchase_receipt_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'purchase_receipt_libraries';

    public function purchaseReceipt()
    {
        return $this->belongsTo(PurchaseReceipt::class, 'purchase_receipt_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
