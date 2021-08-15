<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class PartLibrary extends Model
{
    protected $fillable = ['part_id', 'file_name', 'extension', 'name', 'title_ar', 'title_en'];

    protected $table = 'part_libraries';

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
