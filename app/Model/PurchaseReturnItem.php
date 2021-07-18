<?php

namespace App\Model;

use App\Models\Part;
use App\Models\PartPrice;
use App\Models\PartPriceSegment;
use App\Models\SparePart;
use App\Models\Store;
use App\Models\TaxesFees;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnItem extends Model
{
    protected $table = 'purchase_return_items';

    protected $fillable = [
        'purchase_returns_id',
        'part_id',
        'store_id',
        'available_qty',
        'quantity',
        'price',
        'discount_type',
        'discount',
        'total_after_discount',
        'sub_total',
        'tax',
        'total',
        'active',
        'max_quantity',
        'item_id',
        'part_price_id',
        'part_price_segment_id',
        'spare_part_id'
    ];

    public function part(){
        return $this->belongsTo(Part::class, 'part_id')->withTrashed();
    }

    public function store(){
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    public function purchaseReturn () {
        return $this->belongsTo(PurchaseReturn::class, 'purchase_returns_id');
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id');
    }

    public function partPriceSegment()
    {
        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'purchase_return_item_taxes_fees', 'item_id', 'tax_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class, 'spare_part_id');
    }
}
