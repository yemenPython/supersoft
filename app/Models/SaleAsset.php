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

class SaleAsset extends Model
{
    use LogsActivity;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'number',
        'branch_id',
        'date',
        'time',
        'note',
        'type',
        'total_purchase_cost',
        'total_past_consumtion',
        'total_replacement',
        'total_current_consumtion',
        'final_total_consumtion',
        'total_sale_amount'
    ];

    protected $table = 'sale_assets';

    protected static $logAttributes = [
        'number',
        'branch_id',
        'date',
        'time',
        'type',
        'total_purchase_cost',
        'total_past_consumtion',
        'total_replacement',
        'total_current_consumtion',
        'final_total_consumtion',
        'total_sale_amount',
        'sale_status'
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


    public function items()
    {
        return $this->hasMany( SaleAssetItem::class, 'sale_asset_id' );
    }

}
