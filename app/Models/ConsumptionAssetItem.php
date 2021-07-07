<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ConsumptionAssetItem extends Model
{
    use SoftDeletes, LogsActivity;

    protected $dates = ['created_by', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'consumption_asset_id',
        'asset_id',
        'asset_group_id',
        'consumption_amount',
    ];

    protected $table = 'consumption_asset_items';

    public function consumptionAsset()
    {
        return $this->belongsTo( ConsumptionAsset::class, 'consumption_asset_id' );
    }

    public function asset()
    {
        return $this->belongsTo( Asset::class, 'asset_id' );
    }

}
