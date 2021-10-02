<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SaleQuotation extends Model
{
    protected $fillable = ['number', 'branch_id', 'purchase_request_id', 'date', 'time', 'user_id', 'customer_id', 'status', 'library_path',
        'supply_date_from', 'supply_date_to', 'sub_total', 'discount', 'discount_type', 'total_after_discount',
        'tax', 'total', 'type', 'additional_payments', 'customer_discount_active', 'customer_discount_type',
        'customer_discount', 'date_from', 'date_to', 'quotation_type', 'salesable_id', 'salesable_type', 'type_for'];

    protected $table = 'sale_quotations';

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
        'type' => 'type',
        'customer' => 'customer',
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

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
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
        return $this->hasMany(SaleQuotationItem::class, 'sale_quotation_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'taxes_fees_sale_quotations', 'sale_quotation_id', 'tax_id');
    }

    public function terms()
    {
        return $this->belongsToMany(SupplyTerm::class, 'sale_quotation_supply_terms',
            'sale_quotation_id', 'supply_term_id');
    }
//
//    public function execution()
//    {
//        return $this->hasOne(PurchaseQuotationExecution::class, 'purchase_quotation_id');
//    }
//
    public function files()
    {
        return $this->hasMany(SaleQuotationLibrary::class, 'sale_quotation_id');
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

    public function salesable()
    {
        return $this->morphTo();
    }

    public function salesInvoices()
    {
        return $this->belongsToMany(SalesInvoice::class, 'sales_invoices_sale_quotations',
            'sale_quotation_id', 'sales_invoice_id');
    }

    public function getCheckIfCompleteReceiptAttribute () {

        foreach ($this->items as $item) {

            if ($item->remaining_quantity_for_accept != 0) {
                return false;
            }
        }

        return true;
    }

    public function saleSupplyOrders()
    {
        return $this->belongsToMany(SaleSupplyOrder::class, 'sale_quotation_sale_supply_orders',
            'sale_quotation_id', 'supply_order_id');
    }
}
