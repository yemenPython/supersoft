<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreTransferItem extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'store_transfer_id', 'part_id', 'new_part_id', 'quantity', 'part_price_id', 'part_price_segment_id',
        'price', 'total', 'spare_part_id'
    ];

    function store_transfer()
    {
        return $this->belongsTo(StoreTransfer::class, 'store_transfer_id');
    }

    function part()
    {
        return $this->belongsTo(Part::class, 'part_id');
    }

    function new_part()
    {
        return $this->belongsTo(Part::class, 'new_part_id');
    }

    function partPrice()
    {
        return $this->belongsTo(PartPrice::class, 'part_price_id');
    }

    function partPriceSegment()
    {
        return $this->belongsTo(PartPriceSegment::class, 'part_price_segment_id');
    }

    public function getStoreIdAttribute()
    {
        return $this->store_transfer->store_from_id;
    }

    function sparePart()
    {
        return $this->belongsTo(SparePart::class, 'spare_part_id');
    }

    public function getStoreFromAttribute() {

        return $this->store_transfer  && $this->store_transfer->store_from ? $this->store_transfer->store_from->name : __('Not determined');
    }

    public function getStoreToAttribute() {

        return $this->store_transfer  && $this->store_transfer->store_to ? $this->store_transfer->store_to->name : __('Not determined');
    }

}
