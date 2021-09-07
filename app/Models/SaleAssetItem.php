<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class SaleAssetItem extends Model
{
    use LogsActivity;

    protected $dates = ['created_by', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'sale_asset_id',
        'asset_id',
        'asset_group_id',
        'sale_amount',
        'sale_status',
    ];

    protected $table = 'sale_asset_items';

    public function saleAsset()
    {
        return $this->belongsTo( SaleAsset::class, 'sale_asset_id' );
    }

    public function asset()
    {
        return $this->belongsTo( Asset::class, 'asset_id' );
    }

}
