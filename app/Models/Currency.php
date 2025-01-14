<?php

namespace App\Models;

use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Currency extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;

    /**
     * table name in db
     *
     * @var String
     */
    protected $table = "currencies";

    protected $dates = ['deleted_at'];

    protected static $logAttributes = ['name_ar', 'name_en', 'symbol_ar', 'symbol_en'];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_ar',
        'name_en',
        'symbol_ar',
        'symbol_en',
        'is_main_currency',
        'conversion_factor',
        'status',
        'seeder',
    ];

    protected $casts = [
        'is_main_currency' => 'boolean',
        'status' => 'boolean',
        'seeder' => 'boolean',
    ];

    public function countries()
    {
        return $this->hasMany(Country::class)->withTrashed();
    }
}
