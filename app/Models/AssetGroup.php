<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class AssetGroup extends Model
{
    use ColumnTranslation, LogsActivity;
    /**
     * @var string
     */
    protected $table = 'assets_groups';

    protected $dates = ['created_at','updated_at','deleted_at'];

    protected $fillable = [
        'name_ar',
        'name_en',
        'total_consumtion',
        'annual_consumtion_rate',
        'branch_id',
        'consumption_type',
        'age_years',
        'age_months',
        'consumption_period',
        'consumption_for'
    ];

    protected static $logAttributes = ['name_ar', 'name_en' ,'total_consumtion', 'annual_consumtion_rate' ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }
    public function assets(){
        return $this->hasMany(Asset::class , 'asset_group_id');
    }

    function branch() {
        return $this->belongsTo(Branch::class ,'branch_id');
    }
}
