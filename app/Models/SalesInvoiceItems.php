<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceItems extends Model
{
    protected $fillable = ['sales_invoice_id','part_id','quantity', 'price','discount_type','discount','sub_total',
        'total_after_discount','total', 'store_id', 'spare_part_id', 'part_price_segment_id', 'part_price_id', 'tax' ];

    protected $table = 'sales_invoice_items';

    public function purchaseInvoice(){
        return $this->belongsTo(PurchaseInvoice::class, 'purchase_invoice_id')->withTrashed();
    }

    public function part(){
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'sales_invoice_items_taxes_fees' ,'item_id', 'tax_id');
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

    public function saleReturnedItem()
    {
        return $this->morphMany(ReturnedSaleReceiptItem::class, 'itemable');
    }

    public function getAcceptedQuantityAttribute()
    {
        return $this->saleReturnedItem->sum('accepted_quantity');
    }

    public function getRemainingQuantityForAcceptAttribute()
    {
        $acceptedQuantity = $this->accepted_quantity;
        return $this->quantity - $acceptedQuantity;
    }
}
