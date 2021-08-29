<?php

namespace App\Models\Banks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class BankDataLibrary extends Model
{
    protected $fillable = [
        'name',
        'bank_data_id',
        'file_name',
        'extension',
        'title_ar',
        'title_en'
    ];

    protected $table = 'bank_data_libraries';

    public function bankData()
    {
        return $this->belongsTo(BankData::class, 'bank_data_id');
    }

    public function getTitleAttribute()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
