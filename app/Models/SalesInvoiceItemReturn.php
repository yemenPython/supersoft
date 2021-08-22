<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceItemReturn extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['sales_invoice_return_id','part_id','quantity','price','store_id','part_price_id','part_price_segment_id',
        'discount_type','discount','sub_total','total_after_discount','total','itemable_id', 'itemable_type',
        'spare_part_id', 'active', 'max_quantity', 'tax'];

    protected $table = 'sales_invoice_item_returns';


    public function part(){
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'sales_invoice_items_return_taxes_fees' ,'item_id', 'tax_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id')->withTrashed();
    }

    public function partPriceSegment()
    {
        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class, 'spare_part_id');
    }

    public function itemable()
    {
        return $this->morphTo();
    }
}
