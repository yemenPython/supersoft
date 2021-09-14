<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Scopes\StopAnactivateAssetBranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class StopAndActivateAsset extends Model
{
    use LogsActivity;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'branch_id',
        'date',
        'user_id',
        'asset_id',
        'status'
    ];

    protected $table = 'stop_activate_assets';

    protected static $logAttributes = [
        'branch_id',
        'date',
        'user_id',
        'asset_id',
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
        static::addGlobalScope( new StopAnactivateAssetBranchScope() );
    }


    public function branch()
    {
        return $this->belongsTo( Branch::class, 'branch_id' )->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function asset()
    {
        return $this->belongsTo( Asset::class,'asset_id');
    }

}
