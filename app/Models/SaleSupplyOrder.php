<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class SaleSupplyOrder extends Model
{
    protected $fillable = ['number', 'branch_id', 'date', 'time', 'user_id', 'customer_id', 'status',
        'sub_total', 'discount', 'discount_type', 'total_after_discount', 'tax', 'total', 'type',
        'additional_payments', 'description', 'library_path', 'customer_discount', 'customer_discount_type',
        'customer_discount_active', 'supply_date_from', 'supply_date_to'];

    protected $table = 'sale_supply_orders';

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
        return $this->hasMany(SupplyOrderLibrary::class, 'supply_order_id');
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

        return $dateNow->diffInDays($endDate, false);
    }
}
