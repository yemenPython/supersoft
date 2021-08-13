<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SaleSupplyOrder extends Model
{
    protected $fillable = ['number', 'branch_id', 'date', 'time', 'user_id', 'status',
        'sub_total', 'discount', 'discount_type', 'total_after_discount', 'tax', 'total', 'type',
        'additional_payments', 'description', 'library_path', 'customer_discount', 'customer_discount_type',
        'customer_discount_active', 'supply_date_from', 'supply_date_to', 'salesable_id', 'salesable_type', 'type_for'];

    protected $table = 'sale_supply_orders';

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'date' => 'date',
        'branch_id' => 'branch_id',
        'number' => 'number',
        'type_for' => 'type_for',
        'salesable_id' => 'salesable_id',
        'total' => 'total',
        'different_days' => 'different_days',
        'remaining_days' => 'remaining_days',
        'status' => 'status',
        'statusExecution' => 'statusExecution',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
        'action' => 'action',
        'options' => 'options'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(SaleSupplyOrderItem::class, 'sale_supply_order_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'sale_supply_order_taxes_fees', 'supply_order_id', 'tax_id');
    }

    public function saleQuotations()
    {
        return $this->belongsToMany(SaleQuotation::class, 'sale_quotation_sale_supply_orders',
            'supply_order_id', 'sale_quotation_id');
    }

    public function terms()
    {
        return $this->belongsToMany(SupplyTerm::class, 'sale_supply_order_supply_terms', 'supply_order_id', 'supply_term_id');
    }
//
//    public function execution()
//    {
//        return $this->hasOne(SupplyOrderExecution::class, 'supply_order_id');
//    }

    public function files()
    {
        return $this->hasMany(SaleSupplyLibrary::class, 'sale_supply_id');
    }

    public function getDifferentDaysAttribute()
    {
        $startDate = Carbon::create($this->supply_date_from);
        $endDate = Carbon::create($this->supply_date_to);
        return $endDate->diffInDays($startDate);
    }

    public function getRemainingDaysAttribute()
    {
        $dateNowFormat = Carbon::now()->format('Y-m-d');

        $dateNow = Carbon::create($dateNowFormat);
        $endDate = Carbon::create($this->supply_date_to);

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

    public function salesable()
    {
        return $this->morphTo();
    }

    public function salesInvoices()
    {
        return $this->belongsToMany(SalesInvoice::class, 'sales_invoices_sale_supply_orders',
            'sale_supply_order_id', 'sales_invoice_id');
    }
}
