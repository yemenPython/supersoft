<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class EgyptianFederationLibrary extends Model
{
    protected $fillable = ['egyptian_federation_id','file_name', 'extension', 'name','title_en','title_ar'];

    protected $table = 'egyptian_federation_libraries';

    public function egyptian_federation() {

        return $this->belongsTo(EgyptianFederationOfConstructionAndBuildingContractors::class, 'egyptian_federation_id');
    }
    public function getTitleAttribute ()
    {
        return App::getLocale() == 'ar' ? $this->title_ar : $this->title_en;
    }
}
