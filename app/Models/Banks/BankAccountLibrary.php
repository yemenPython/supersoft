<?php

namespace App\Models\Banks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class BankAccountLibrary extends Model
{
    protected $fillable = [
        'name',
        'bank_account_id',
        'file_name',
        'extension',
        'title_ar',
        'title_en'
    ];

    protected $table = 'bank_account_libraries';

    public function BankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }

    public function getTitleAttribute()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
