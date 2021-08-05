<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseQuotationItem extends Model
{
    protected $fillable = ['purchase_quotation_id', 'part_id', 'part_price_id', 'quantity', 'price', 'sub_total', 'discount',
        'discount_type', 'total_after_discount', 'tax', 'total', 'active', 'part_price_segment_id', 'spare_part_id'];


    protected $table = 'purchase_quotation_items';

    /**
     * @var string[]
     */
    protected static $dataTableColumns = [
        'DT_RowIndex' => 'DT_RowIndex',
        'quotation_number' => 'quotation_number',
        'purchase_request_number' => 'purchase_request_number',
        'supplier_id' => 'supplier_id',
        'part_id' => 'part_id',
        'spare_part_id' => 'spare_part_id',
        'part_price_id' => 'part_price_id',
        'part_price_segment_id' => 'part_price_segment_id',
        'quantity' => 'quantity',
        'price' => 'price',
        'discount_type' => 'discount_type',
        'discount' => 'discount',
        'sub_total' => 'sub_total',
        'total_after_discount' => 'total_after_discount',
        'tax' => 'tax',
        'total' => 'total',
        'action' => 'action',
        'options' => 'options'
    ];

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

    public function purchaseQuotation()
    {
        return $this->belongsTo(PurchaseQuotation::class, 'purchase_quotation_id');
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
        return $this->belongsToMany(TaxesFees::class, 'purchase_quotation_item_taxes_fees', 'item_id', 'tax_id');
    }

    public function partPriceSegment()
    {
        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id');
    }

    public function spareParts()
    {
        return $this->belongsToMany(SparePart::class, 'purchase_quotation_items_spare_parts', 'item_id', 'spare_part_id')
            ->withPivot('price');
    }

    public function sparePart()
    {
        return $this->belongsTo(SparePart::class, 'spare_part_id');
    }
}
