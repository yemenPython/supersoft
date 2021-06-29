<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssetExpense extends Model
{
    /**
     * @var string
     */
    protected $table = 'asset_expenses';

    /**
     * @var string[]
     */
    protected $fillable = [
        'branch_id',
        'number',
        'date',
        'notes',
        'status',
        'total',
        'user_id',
        'time',
    ];

    public function assetExpensesItems(): HasMany
    {
        return $this->hasMany(AssetExpenseItem::class, 'asset_expense_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'branch_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'user_id');
    }
}
