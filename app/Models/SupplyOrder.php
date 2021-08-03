<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SupplyOrder extends Model
{
    protected $fillable = [
        'number',
        'branch_id',
        'purchase_request_id',
        'date', 'time',
        'user_id',
        'supplier_id',
        'status',
        'sub_total',
        'discount',
        'discount_type',
        'total_after_discount',
        'tax',
        'total',
        'type',
        'additional_payments',
        'description',
        'library_path',
        'supplier_discount',
        'supplier_discount_type',
        'supplier_discount_active',
        'date_from',
        'date_to'
    ];

    protected $table = 'supply_orders';

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
        'different_days' => 'different_days',
        'remaining_days' => 'remaining_days',
        'status' => 'status',
        'executionStatus' => 'executionStatus',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'purchase_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(SupplyOrderItem::class, 'supply_order_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'supply_order_taxes_fees', 'supply_order_id', 'tax_id');
    }

    public function purchaseQuotations()
    {
        return $this->belongsToMany(PurchaseQuotation::class, 'purchase_quotation_supply_orders',
            'supply_order_id', 'purchase_quotation_id');
    }

    public function terms()
    {
        return $this->belongsToMany(SupplyTerm::class, 'supply_order_supply_terms', 'supply_order_id', 'supply_term_id');
    }

    public function execution()
    {
        return $this->hasOne(SupplyOrderExecution::class, 'supply_order_id');
    }

    public function files()
    {
        return $this->hasMany(SupplyOrderLibrary::class, 'supply_order_id');
    }

    public function purchaseReceipts()
    {
        return $this->hasMany(PurchaseReceipt::class, 'supply_order_id');
    }

    public function getCheckIfCompleteReceiptAttribute () {

        foreach ($this->items as $item) {

            if ($item->remaining_quantity_for_accept != 0) {
                return false;
            }
        }

        return true;
    }

    public function getDifferentDaysAttribute()
    {
        $startDate = Carbon::create($this->date_from);
        $endDate = Carbon::create($this->date_to);
        return $endDate->diffInDays($startDate);
    }

    public function getRemainingDaysAttribute()
    {
        $dateNowFormat = Carbon::now()->format('Y-m-d');

        $dateNow = Carbon::create($dateNowFormat);
        $endDate = Carbon::create($this->date_to);

        $remaining = $dateNow->diffInDays($endDate, false);

        if (intval($remaining) < 0) {
            return 0;
        }

        return $dateNow->diffInDays($endDate, false);
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
