<?php

namespace App\Models;

use App\Models\Branch;
use App\Models\PurchaseInvoice;
use App\OpeningStockBalance\Models\OpeningBalanceItems;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;

class OpeningBalance extends Model
{
    protected $fillable = [
        'branch_id',
        'serial_number',
        'operation_date',
        'operation_time',
        'notes',
        'total_money',
        'purchase_invoice_id',
        'user_id',
        'library_path'
    ];

    protected $table = 'opening_balances';

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'branch_id' => 'branch_id',
        'operation_date' => 'operation_date',
        'serial_number' => 'serial_number',
        'total_money' => 'total_money',
        'status' => 'status',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    function items()
    {
        return $this->hasMany(OpeningBalanceItems::class, 'opening_balance_id');
    }

    function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }

    public function getNumberAttribute()
    {
        return $this->serial_number;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    function files()
    {
        return $this->hasMany(OpeningBalanceLibrary::class, 'opening_balance_id');
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

    public function getConcessionTypeAttribute() {

        $concessionTypeItem = ConcessionTypeItem::where('model', 'OpeningBalance')->first();

        if (!$concessionTypeItem) {
            return 0;
        }

        $firstType = $concessionTypeItem->concessionTypes->first();

        if (!$firstType) {

            return 0;
        }

        return $firstType->id;
    }
}
