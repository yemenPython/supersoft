<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'supplier_id',
        'bank_name',
        'account_name',
        'branch',
        'account_number',
        'iban',
        'swift_code',
        'customer_id',
        'status',
        'start_date',
        'end_date',
    ];

    protected $table = 'bank_accounts';

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withTrashed();
    }

    public function customer()
    {
        return $this->belongsTo(Supplier::class, 'customer_id')->withTrashed();
    }

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'status' => 'status',
        'bank_name' => 'bank_name',
        'account_name' => 'account_name',
        'branch' => 'branch',
        'account_number' => 'account_number',
        'iban' => 'iban',
        'swift_code' => 'swift_code',
        'start_date' => 'start_date',
        'end_date' => 'end_date',
        'action' => 'action',
        'options' => 'options',
    ];

    /**
     * @return string[]
     */
    public static function getJsDataTablesColumns(): array
    {
        return self::$dataTableColumns;
    }
}
