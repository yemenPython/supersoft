<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnedSaleReceiptItem extends Model
{
    protected $fillable = ['sale_receipt_id', 'part_id', 'part_price_id', 'total_quantity', 'refused_quantity',
        'accepted_quantity', 'defect_percent', 'itemable_id', 'itemable_type', 'price', 'store_id', 'spare_part_id'];

    protected $table = 'returned_sale_receipt_items';

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id');
    }

    public function itemable()
    {
        return $this->morphTo();
    }

    public function getOldAcceptedQuantityAttribute()
    {
        $oldAcceptedQuantity = $this->itemable ? $this->itemable->accepted_quantity : 0;

        if (!$oldAcceptedQuantity) {
            return 0;
        }

        return $oldAcceptedQuantity - $this->accepted_quantity;
    }

    public function getRemainingQuantityAttribute()
    {
        $remainingQuantity = $this->itemable ? $this->itemable->remaining_quantity_for_accept : 0;

        return $remainingQuantity + $this->accepted_quantity;
    }

    public function getCalculateDefectedPercentAttribute()
    {
        $refusedQuantity = $this->remaining_quantity - $this->accepted_quantity;

        if ($refusedQuantity < 0) {
            $refusedQuantity = 0;
        }

        return round($refusedQuantity * 100 / $this->total_quantity, 2);
    }

    public function getQuantityAttribute()
    {
        return $this->accepted_quantity;
    }

    public function getPartPriceSegmentIdAttribute()
    {
        $item = $this->itemable;
        return $item ? $item->part_price_segment_id : null;
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class, 'spare_part_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function getDiscountTypeAttribute()
    {
        $item = $this->itemable;
        return $item ? $item->discount_type : null;
    }

    public function getDiscountAttribute()
    {
        $item = $this->itemable;
        return $item ? $item->discount : null;
    }

    public function getTaxesAttribute()
    {
        $item = $this->itemable;
        return $item ? $item->taxes : null;
    }

}
