<?php

namespace App\Models\Banks;

use App\Models\Branch;
use App\Models\Currency;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    /**
     * @var string
     */
    protected $table = 'banks_accounts';

    /**
     * @var string[]
     */
    protected $fillable = [
        'branch_id',
        'bank_data_id',
        'branch_product_id',
        'currency_id',
        'main_type_bank_account_id',
        'sub_type_bank_account_id',
        'bank_account_child_id',
        'account_number',
        'account_name',
        'iban',
        'customer_number',
        'granted_limit',
        'deposit_number',
        'deposit_term',
        'periodicity_return_disbursement',
        'account_open_date',
        'expiry_date',
        'Last_renewal_date',
        'deposit_opening_date',
        'deposit_expiry_date',
        'interest_rate',
        'deposit_amount',
        'type',
        'yield_rate_type',
        'auto_renewal',
        'with_returning',
        'status',
        'check_books',
        'overdraft',
        'library_path',
        'balance',
    ];

    protected $casts = [
        'status' => 'boolean',
        'auto_renewal' => 'boolean',
        'with_returning' => 'boolean',
        'check_books' => 'boolean',
        'overdraft' => 'boolean',
    ];

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'branch_id' => 'branch_id',
        'type_bank_account' => 'type_bank_account',
        'bank_name' => 'bank_name',
        'balance' => 'balance',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        if (!authIsSuperAdmin()) {
            unset(self::$dataTableColumns['branch_id']);
        }
        return self::$dataTableColumns;
    }

    public function bankData(): BelongsTo
    {
        return $this->belongsTo(BankData::class, 'bank_data_id');
    }

    public function mainType(): BelongsTo
    {
        return $this->belongsTo(TypeBankAccount::class, 'main_type_bank_account_id');
    }

    public function subType(): BelongsTo
    {
        return $this->belongsTo(TypeBankAccount::class, 'sub_type_bank_account_id');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(BranchProduct::class, 'branch_product_id');
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(BankAccountLibrary::class, 'bank_account_id');
    }

}
