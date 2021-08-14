<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class PurchaseRequestLibrary extends Model
{
    protected $fillable = ['name','purchase_request_id','file_name','extension', 'title_ar', 'title_en'];

    protected $table = 'purchase_request_libraries';

    public function purchaseRequest () {

        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
