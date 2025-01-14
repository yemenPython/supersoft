<?php

namespace App\Models;

use App\Services\Relaying\RelayingPurchaseReceipts;
use Illuminate\Database\Eloquent\Model;

class PurchaseReceipt extends Model
{
    protected $fillable = ['number', 'branch_id', 'user_id', 'supply_order_id', 'date', 'time', 'supplier_id',
        'library_path', 'notes', 'total', 'total_accepted', 'total_rejected'];

    protected $table = 'purchase_receipts';

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'date' => 'date',
        'branch_id' => 'branch_id',
        'supplier_id' => 'supplier_id',
        'number' => 'number',
        'total' => 'total',
        'total_accepted' => 'total_accepted',
        'total_rejected' => 'total_rejected',
        'status' => 'status',
        'executionStatus' => 'executionStatus',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function supplyOrder()
    {
        return $this->belongsTo(SupplyOrder::class, 'supply_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function execution()
    {
        return $this->hasOne(PurchaseReceiptExecution::class, 'purchase_receipt_id');
    }

    public function files()
    {
        return $this->hasMany(PurchaseReceiptLibrary::class, 'purchase_receipt_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseReceiptItem::class, 'purchase_receipt_id');
    }

    public function concession()
    {
        return $this->morphOne(Concession::class, 'concessionable');
    }

    public function purchaseInvoices()
    {
        return $this->belongsToMany(PurchaseInvoice::class, 'purchase_invoice_purchase_receipts',
            'purchase_receipt_id', 'purchase_invoice_id');
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

    public function reasonsPreventRelaying ($to) {

        $obj = new RelayingPurchaseReceipts();

        return $obj->$to($this);
    }
}
