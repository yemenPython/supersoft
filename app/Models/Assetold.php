<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Asset extends Model
{
    use ColumnTranslation, LogsActivity, SoftDeletes;
    /**
     * @var string
     */
    protected $table = 'assets_tb';

    /**
     * @var string[]
     */
    protected $dates = ['deleted_at'];

    /**
     * @var string[]
     */
    protected $fillable = [
        'id',
        'name_ar',
        'name_en',
        'asset_group_id',
        'annual_consumtion_rate',
        'branch_id',
        'asset_type_id',
        'asset_status',
        'details',
        'asset_details',
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
    ];

    /**
     * @var string[]
     */
    protected static $logAttributes = [
        'name_ar',
        'name_en',
        'asset_group_id',
        'annual_consumtion_rate',
        'branch_id',
        'asset_type_id',
        'asset_status',
        'details',
        'asset_details',
        'purchase_date',
        'date_of_work',
        'purchase_cost',
        'past_consumtion',
        'current_consumtion',
        'total_current_consumtion',
        'book_value',
    ];

    /**
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * @param string $eventName
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }
    /**
     * @return BelongsTo
     */
    function group(): BelongsTo
    {
        return $this->belongsTo(AssetGroup::class ,'asset_group_id');
    }

    /**
     * @return BelongsTo
     */
    function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class ,'branch_id');
    }

    /**
     * @return HasMany
     */
    public function asset_employees(): HasMany
    {
        return $this->hasMany(AssetEmployee::class ,'asset_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
