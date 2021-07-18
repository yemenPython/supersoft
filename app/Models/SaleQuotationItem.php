<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleQuotationItem extends Model
{
    protected $fillable = ['sale_quotation_id', 'part_id', 'part_price_id', 'quantity', 'price', 'sub_total', 'discount',
        'discount_type', 'total_after_discount', 'tax', 'total', 'active', 'part_price_segment_id', 'spare_part_id'];

    protected $table = 'sale_quotation_items';

    public function saleQuotation()
    {
        return $this->belongsTo(SaleQuotation::class, 'sale_quotation_id');
    }

    public function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    public function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(TaxesFees::class, 'taxes_fees_sale_quotation_items', 'item_id', 'tax_id');
    }

    public function partPriceSegment()
    {
        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id');
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class, 'spare_part_id');
    }
}
