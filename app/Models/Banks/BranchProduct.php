<?php

namespace App\Models\Banks;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;

class BranchProduct extends Model
{
    use ColumnTranslation;

    protected $table = 'branch_products';

    protected $fillable = [
        'name_ar',
        'name_en'
    ];

    public function setNameEnAttribute($value)
    {
        $this->attributes['name_en'] = is_null($value) ? $this->attributes['name_ar'] : $value;
    }
}
