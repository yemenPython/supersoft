<?php

namespace App\Models;

use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SupplyTerm extends Model
{
    protected $fillable = ['term_en', 'term_ar', 'branch_id', 'status', 'for_purchase_quotation', 'type'];

    protected $table = 'supply_terms';

    public function getTermAttribute()
    {
        return App::getLocale() == 'ar' ? $this->term_ar : $this->term_en;
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new BranchScope());
    }

    public function purchaseQuotations()
    {
        return $this->belongsToMany(PurchaseQuotation::class, 'purchase_quotation_supply_terms', 'supply_term_id', 'purchase_quotation_id');
    }

    public function supplyOrders()
    {
        return $this->belongsToMany(SupplyOrder::class, 'supply_order_supply_terms', 'supply_term_id', 'supply_order_id');
    }
}
