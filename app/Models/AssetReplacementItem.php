<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetReplacementItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'asset_replacement_items';

    /**
     * @var string[]
     */
    protected $fillable = [
        'asset_id',
        'asset_replacement_id',
        'value_replacement',
        'value_after_replacement',
        'age',
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}




