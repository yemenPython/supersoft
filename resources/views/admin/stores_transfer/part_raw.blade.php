<tr class="text-center-inputs tr_part_{{$index}}" id="tr_part_{{$index}}">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td>
        <span style="display:block; cursor: pointer" data-img="{{$part->image}}" data-toggle="modal"
              data-target="#part_img" title="Part image" onclick="getPartImage('{{$index}}')"
              id="part_img_id_{{$index}}" data-img="{{$part->img}}">

            {{$part->name}}
        </span>

        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" class="form-control"
               style="text-align: center;">
        <input type="hidden" value="{{isset($item) ? $max : 0}}" class="form-control" id="max_quantity_part_{{$index}}"
               style="text-align: center;">
    </td>

    <td>
        <div class="input-group" >

            <select class="form-control js-example-basic-single" name="items[{{$index}}][spare_part_id]" id="spare_part_id_{{$index}}">

                @foreach($part->part_types_tree as $sparePartId => $sparePartValue)
                    <option value="{{$sparePartId}}"
                        {{isset($item) && $item->spare_part_id == $sparePartId ? 'selected':''}}
                    >
                        {{$sparePartValue}}
                    </option>
                @endforeach
            </select>
        </div>
    </td>

    <td class="inline-flex-span" >

        <span id="unit_quantity_{{$index}}">
            {{isset($item) && $item->partPrice ? $item->partPrice->quantity : $part->first_price_quantity}}
        </span>
        <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span>
    </td>

    <td>
        <div class="input-group">

            <select  class="form-control js-example-basic-single"
                    name="items[{{$index}}][part_price_id]" id="prices_part_{{$index}}"
                    onchange="priceSegments('{{$index}}');">

                @foreach($part->prices as $price)
                    <option value="{{$price->id}}"
                            data-purchase-price="{{$price->purchase_price}}"
                            data-quantity="{{$price->quantity}}"
                            data-barcode="{{$price->barcode}}"
                            data-supplier-barcode="{{$price->supplier_barcode}}"

                        {{isset($item) && $item->part_price_id == $price->id ? 'selected':''}}>
                        {{optional($price->unit)->unit}}
                    </option>
                @endforeach
            </select>
        </div>
    </td>

    <td>
        <div class="input-group">

            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{$index}}][part_price_segment_id]"
                    id="price_segments_part_{{$index}}"
                    onchange=" getPurchasePrice('price_segments_part_','{{$index}}'); calculateItem('{{$index}}');">

                @if(isset($item) && $item->partPrice)

                    <option value="">{{__('Select')}}</option>

                    @foreach($item->partPrice->partPriceSegments as $priceSegment)
                        <option value="{{$priceSegment->id}}"
                                data-purchase-price="{{$priceSegment->purchase_price}}"
                            {{isset($item) && $item->part_price_segment_id == $priceSegment->id  ? 'selected':''}}>
                            {{$priceSegment->name}}
                        </option>
                    @endforeach

                @else

                    @if($part->prices->first())

                        <option value="">{{__('Select')}}</option>

                        @foreach($part->first_price_segments as $priceSegment)
                            <option value="{{$priceSegment->id}}"
                                    data-purchase-price="{{$priceSegment->purchase_price}}"
                                {{isset($item) && $item->part_price_segment_id == $priceSegment->id  ? 'selected':''}}>
                                {{$priceSegment->name}}
                            </option>
                        @endforeach
                    @endif

                @endif

            </select>
        </div>
    </td>

    <td>
        <span id="barcode_{{$index}}">
            {{ isset($item) && $item->partPrice ? $item->partPrice->barcode : $part->default_barcode }}
        </span>
    </td>

    <td>
        <span id="supplier_barcode_{{$index}}">
             {{ isset($item) && $item->partPrice ? $item->partPrice->supplier_barcode : $part->default_supplier_barcode }}
        </span>
    </td>

    <td>
        <button type="button" class="btn btn-default btn-sm accordion-toggle" data-toggle="collapse" data-target="#demo{{$index}}" >
            <i class="glyphicon glyphicon-eye-open"></i>
        </button>

        <div class="input-group" id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
        </div>
    </td>
</tr>

{{-- SECOND TR --}}

<tr class="tr_part_{{$index}}">

    <td colspan="12" class="hiddenRow">
        <div class="accordian-body collapse" id="demo{{$index}}">
            <table class=" table table-responsive table-bordered table-hover">
                <thead>
                <tr class="info">
                    <th > {{ __('Quantity') }} </th>
                    <th > {{ __('Price') }} </th>
                    <th > {{ __('Total') }} </th>
                </tr>
                </thead>

                <tbody>

                <tr >

                    <td>
                        <input type="number" class="form-control border1" id="quantity_{{$index}}"
                               onkeyup="checkPartQuantity('{{$index}}'); calculateItem('{{$index}}'); quantityValidation('{{$index}}','{{__('sorry, quantity not  valid')}}')"
                               onchange="checkPartQuantity('{{$index}}'); calculateItem('{{$index}}')"
                               value="{{isset($item) ? $item->quantity : 1}}" min="1" name="items[{{$index}}][quantity]">
                    </td>

                    <td>
                        <input  type="text" id="price_{{$index}}" class="form-control border2" disabled
                               value="{{isset($item) ? $item->price : $part->default_purchase_price}}">
                    </td>

                    <td>
                        <input type="text" id="total_{{$index}}" disabled class="form-control border3"
                               value="{{isset($item) ? ($item->price * $item->quantity) : 0}}" name="items[{{$index}}][total]">
                    </td>

                </tr>

                </tbody>
            </table>

        </div>
    </td>
</tr>


