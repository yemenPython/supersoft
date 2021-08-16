<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class TaxCardLibrary extends Model
{
    protected $fillable = ['tax_card_id','file_name', 'extension', 'name','title_en','title_ar'];

    protected $table = 'tax_card_libraries';

    public function tax_card() {

        return $this->belongsTo(TaxCard::class, 'tax_card_id');
    }
    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
