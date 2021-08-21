<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetReplacement extends Model
{
    /**
     * @var string
     */
    protected $table = 'asset_replacements';

    /**
     * @var string[]
     */
    protected $fillable = [
        'number',
        'date',
        'time',
        'total_after_replacement',
        'total_before_replacement',
        'branch_id',
        'user_id',
        'note'
    ];

    protected $with = ['assetReplacementItems'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function assetReplacementItems(): HasMany
    {
        return $this->hasMany(AssetReplacementItem::class, 'asset_replacement_id');
    }
}
