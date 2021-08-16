<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class StoreTransferLibrary extends Model
{
    protected $fillable = ['name', 'store_transfer_id', 'file_name', 'extension', 'title_ar', 'title_en'];

    protected $table = 'store_transfer_libraries';

    public function StoreTransfer()
    {
        return $this->belongsTo(StoreTransfer::class, 'store_transfer_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
