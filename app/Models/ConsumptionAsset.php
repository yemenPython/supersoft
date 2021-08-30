<?php

namespace App\Models;

use App\Model\PurchaseReturn;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Traits\LogsActivity;

class ConsumptionAsset extends Model
{
    use SoftDeletes, LogsActivity;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'number',
        'branch_id',
        'date',
        'time',
        'note',
        'date_from',
        'date_to',
        'total_purchase_cost',
        'total_past_consumtion',
        'total_replacement',
        'user_id',
        'type'
    ];

    protected $table = 'consumption_assets';

    protected static $logAttributes = [
        'number',
        'branch_id',
        'date',
        'time',
        'date_from',
        'date_to',
        'total_purchase_cost',
        'total_past_consumtion',
        'total_replacement'
    ];

    protected static $logOnlyDirty = true;

    public function getDescriptionForEvent(string $eventName): string
    {
        return "This model has been {$eventName}";
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope( new BranchScope() );
    }


    public function branch()
    {
        return $this->belongsTo( Branch::class, 'branch_id' )->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function items()
    {
        return $this->hasMany( ConsumptionAssetItem::class, 'consumption_asset_id' );
    }

}
