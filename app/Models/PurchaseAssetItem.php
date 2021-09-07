<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PurchaseAssetItem extends Model
{
    use LogsActivity;
    protected $dates = ['created_by','updated_at','deleted_at'];
    protected $fillable = [
        'purchase_asset_id',
        'asset_id',
        'asset_group_id',
        'purchase_cost',
        'past_consumtion',
        'annual_consumtion_rate',
        'asset_age',
    ];

    protected $table = 'purchase_asset_items';

    public function purchaseAsset()
    {
        return $this->belongsTo(PurchaseAsset::class, 'purchase_asset_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

}
