<?php

namespace App\Models\Banks;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BranchProduct extends Model
{
    use ColumnTranslation;

    protected $table = 'branch_products';

    protected $fillable = [
        'name_ar',
        'name_en',
        'branch_id',
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function setNameEnAttribute($value)
    {
        $this->attributes['name_en'] = is_null($value) ? $this->attributes['name_ar'] : $value;
    }

    public function banksData(): BelongsToMany
    {
        return $this->belongsToMany(BankData::class,
            'branch_product_banks', 'branch_product_id', 'bank_data_id');
    }
}
