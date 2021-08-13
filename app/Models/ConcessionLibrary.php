<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class ConcessionLibrary extends Model
{
    protected $fillable = ['name','concession_id','file_name','extension', 'title_ar', 'title_en'];

    protected $table = 'concession_libraries';

    public function concession () {

        return $this->belongsTo(Concession::class, 'concession_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
