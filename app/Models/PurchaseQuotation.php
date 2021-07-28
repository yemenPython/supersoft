<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PurchaseQuotation extends Model
{
    protected $fillable = ['number', 'branch_id', 'purchase_request_id', 'date', 'time', 'user_id', 'supplier_id', 'status', 'library_path',
        'supply_date_from', 'supply_date_to', 'sub_total', 'discount', 'discount_type', 'total_after_discount',
        'tax', 'total', 'type', 'additional_payments', 'supplier_discount_active', 'supplier_discount_type',
        'supplier_discount', 'date_from', 'date_to', 'quotation_type'];

    protected $table = 'purchase_quotations';

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'date' => 'date',
        'branch_id' => 'branch_id',
        'number' => 'number',
        'quotation_type' => 'quotation_type',
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
        return $this->hasMany(PurchaseQuotationItem::class, 'purchase_quotation_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'purchase_quotation_taxes_fees', 'purchase_quotation_id', 'tax_id');
    }

    public function terms()
    {
        return $this->belongsToMany(SupplyTerm::class, 'purchase_quotation_supply_terms', 'purchase_quotation_id', 'supply_term_id');
    }

    public function execution()
    {
        return $this->hasOne(PurchaseQuotationExecution::class, 'purchase_quotation_id');
    }

    public function files()
    {
        return $this->hasMany(PurchaseQuotationLibrary::class, 'purchase_quotation_id');
    }

    public function supplyOrders()
    {
        return $this->belongsToMany(SupplyOrder::class, 'purchase_quotation_supply_orders',
            'purchase_quotation_id', 'supply_order_id');
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
