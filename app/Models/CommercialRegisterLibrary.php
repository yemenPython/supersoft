<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class CommercialRegisterLibrary extends Model
{
    protected $fillable = ['commercial_register_id','file_name', 'extension', 'name','title_en','title_ar'];

    protected $table = 'commercial_register_libraries';

    public function commercial_register() {

        return $this->belongsTo(CommercialRegister::class, 'commercial_register_id');
    }
    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
