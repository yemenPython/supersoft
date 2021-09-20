<?php

namespace App\Models\Banks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpeningBalanceAccountItem extends Model
{
    /**
     * @var string
     */
    protected $table = 'opening_balance_account_items';

    /**
     * @var string[]
     */
    protected $fillable = [
        'bank_account_id',
        'opening_balance_account_id',
        'total',
    ];

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id');
    }
}
