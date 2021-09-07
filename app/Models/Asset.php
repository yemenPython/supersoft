<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Spatie\Activitylog\Traits\LogsActivity;

class Asset extends Model
{
    use ColumnTranslation, LogsActivity;
    /**
     * @var string
     */
    protected $table = 'assets_tb';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'name_ar',
        'name_en',
        'asset_group_id',
        'asset_type_id',
        'asset_status',
        'annual_consumtion_rate',
        'branch_id',
        'asset_details',
        'asset_age',
        'purchase_date',
        'date_of_work',
        'purchase_cost',
        'past_consumtion',
        'current_consumtion',
        'total_current_consumtion',
        'book_value',
        'created_at',
        'updated_at',
        'deleted_at',
        'user_id',
        'total_replacements',
        'status'
    ];

    protected static $logAttributes = [
        'name_ar',
        'name_en',
        'asset_group_id',
        'asset_type_id',
        'asset_status',
        'annual_consumtion_rate',
        'branch_id',
        'asset_details',
        'asset_age',
        'purchase_date',
        'date_of_work',
        'purchase_cost',
        'past_consumtion',
        'current_consumtion',
        'total_current_consumtion',
        'book_value',
        'total_replacements',
        'status'
     ];

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
    function group() {
        return $this->belongsTo(AssetGroup::class ,'asset_group_id');
    }

    function type() {
        return $this->belongsTo(AssetType::class ,'asset_type_id');
    }

    function branch() {
        return $this->belongsTo(Branch::class ,'branch_id');
    }
    public function asset_employees() {
        return $this->hasMany(AssetEmployee::class ,'asset_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function replacements()
    {
        return $this->hasMany(AssetReplacementItem::class,'asset_id');
    }

    public function assetReplacementItem(): HasOne
    {
        return $this->hasOne(AssetReplacementItem::class, 'asset_id');
    }

    public function assetMaintenances(): HasMany
    {
        return $this->hasMany(AssetMaintenance::class, 'asset_id');
    }
    public function expenses(){
        return $this->hasMany(AssetExpenseItem::class);
    }
}
