<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class RegisterAddedValueLibrary extends Model
{
    protected $fillable = ['register_added_value_id','file_name', 'extension', 'name','title_en','title_ar'];

    protected $table = 'register_added_value_libraries';

    public function register_added_value() {

        return $this->belongsTo(RegisterAddedValue::class, 'register_added_value_id');
    }
    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
