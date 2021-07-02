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

class PurchaseAsset extends Model
{
    use SoftDeletes, LogsActivity;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'invoice_number',
        'supplier_id',
        'branch_id',
        'date',
        'time',
        'note',
        'remaining_amount',
        'paid_amount'
    ];

    protected $table = 'purchase_assets';

    protected static $logAttributes = [
        'invoice_number',
        'supplier_id',
        'branch_id',
        'date',
        'time'
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

    public function supplier()
    {
        return $this->belongsTo( Supplier::class, 'supplier_id' )->withTrashed();
    }

    public function items()
    {
        return $this->hasMany( PurchaseAssetItem::class, 'purchase_asset_id' );
    }

}
