<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EgyptianFederationLibrary extends Model
{
    protected $fillable = ['egyptian_federation_id','file_name', 'extension', 'name'];

    protected $table = 'egyptian_federation_libraries';

    public function egyptian_federation() {

        return $this->belongsTo(EgyptianFederationOfConstructionAndBuildingContractors::class, 'egyptian_federation_id');
    }
}
