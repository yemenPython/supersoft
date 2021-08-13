<?php

namespace App\Models;

use App\Model\PurchaseReturn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class PurchaseReturnLibrary extends Model
{
    protected $fillable = ['name', 'purchase_return_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'purchase_return_libraries';

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_return_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
