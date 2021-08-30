<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class ConsumptionAssetItemExpense extends Model
{
    use SoftDeletes, LogsActivity;

    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'consumption_asset_item_id',
        'asset_id',
        'expense_id',
        'consumption_amount',
    ];

    protected $table = 'consumption_asset_item_expenses';

    public function consumptionAssetItem()
    {
        return $this->belongsTo( ConsumptionAssetItem::class, 'consumption_asset_item_id' );
    }

    public function asset()
    {
        return $this->belongsTo( Asset::class, 'asset_id' );
    }
    public function expense()
    {
        return $this->belongsTo(AssetExpenseItem::class,'expense_id');
    }

}
