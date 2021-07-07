<?php

namespace App\Models;

use App\Scopes\BranchScope;
use App\Traits\ColumnTranslation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetsItemExpense extends Model
{
    use ColumnTranslation;
    /**
     * @var string
     */
    protected $table = 'assets_item_expenses';

    /**
     * @var string[]
     */
    protected $fillable = [
        'item_ar',
        'item_en',
        'assets_type_expenses_id',
        'branch_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new BranchScope());
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function assetsTypeExpense(): BelongsTo
    {
        return $this->belongsTo(AssetsTypeExpense::class, 'assets_type_expenses_id');
    }

    public function assetExpenseItems(): HasMany
    {
        return $this->hasMany(AssetExpenseItem::class, 'asset_expense_item_id');
    }
}
