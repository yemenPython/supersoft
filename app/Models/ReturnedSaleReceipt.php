<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnedSaleReceipt extends Model
{
    protected $fillable = ['number', 'branch_id', 'user_id', 'salesable_id', 'salesable_type', 'type', 'date', 'time',
        'clientable_id', 'clientable_type', 'library_path', 'notes', 'total', 'total_accepted', 'total_rejected'];

    protected $table = 'returned_sale_receipts';

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'date' => 'date',
        'branch_id' => 'branch_id',
        'client_id' => 'client_id',
        'number' => 'number',
        'total' => 'total',
        'total_accepted' => 'total_accepted',
        'total_rejected' => 'total_rejected',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

//    public function supplyOrder()
//    {
//        return $this->belongsTo(SupplyOrder::class, 'supply_order_id');
//    }

    public function salesable()
    {
        return $this->morphTo();
    }

    public function clientable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

//    public function execution()
//    {
//        return $this->hasOne(PurchaseReceiptExecution::class, 'purchase_receipt_id');
//    }
//
    public function files()
    {
        return $this->hasMany(ReturnedReceiptLibrary::class, 'returned_receipt_id');
    }

    public function items()
    {
        return $this->hasMany(ReturnedSaleReceiptItem::class, 'sale_receipt_id');
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
}
