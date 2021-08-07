<tr class="text-center-inputs" id="tr_part_{{$index}}">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td>
            <span style="width: 150px !important;display:block; cursor: pointer" data-img="{{$part->image}}"
                  data-toggle="modal"
                  data-target="#part_img" title="Part image" onclick="getPartImage('{{$index}}')"
                  id="part_img_id_{{$index}}" data-img="{{$part->img}}">

            {{$part->name}}
        </span>

        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" class="form-control"
               style="text-align: center;">
        <input type="hidden" value="{{isset($item) ? $max : $part->first_store_quantity}}" class="form-control"
               id="max_quantity_part_{{$index}}" style="text-align: center;">
    </td>

    <td>
        <div class="input-group" style="width: 180px !important;">

            <select class="form-control js-example-basic-single" name="items[{{$index}}][spare_part_id]"
                    id="spare_part_id_{{$index}}">

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

    <td class="inline-flex-span">
        <span id="unit_quantity_{{$index}}">
        {{isset($item) && $item->partPrice ? $item->partPrice->quantity : $part->first_price_quantity}}
        </span>
        <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span>

    </td>

    <td>
        <div class="input-group">

            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{$index}}][part_price_id]"
                    id="prices_part_{{$index}}" onchange="priceSegments('{{$index}}');">

                @foreach($part->prices as $price)
                    <option value="{{$price->id}}"
                            data-damaged-price="{{$price->purchase_price}}"
                            data-quantity="{{$price->quantity}}"
                        {{isset($item) && $item->part_price_id == $price->id ? 'selected':''}}>
                        {{optional($price->unit)->unit}}
                    </option>
                @endforeach
            </select>
        </div>
    </td>

    <td>
        <div class="input-group" id="price_segments_part_{{$index}}">

            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{$index}}][part_price_segment_id]"
                    id="price_segments_part_{{$index}}"
                    onchange="getPurchasePrice('{{$index}}'); calculateItem('{{$index}}') ">

                @if(isset($item) && $item->partPrice)

                    <option value="">{{__('Select Segment')}}</option>

                    @foreach($item->partPrice->partPriceSegments as $priceSegment)
                        <option value="{{$priceSegment->id}}" data-purchase_price="{{$priceSegment->purchase_price}}"
                            {{isset($item) && $item->part_price_segment_id == $priceSegment->id  ? 'selected':''}}>
                            {{$priceSegment->name}}
                        </option>
                    @endforeach

                @else

                    @if($part->prices->first())

                        <option value="">{{__('Select Segment')}}</option>

                        @foreach($part->first_price_segments as $priceSegment)
                            <option value="{{$priceSegment->id}}"
                                    data-purchase_price="{{$priceSegment->purchase_price}}"
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
        <div class="input-group">
            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{$index}}][store_id]" id="store_part_{{$index}}"
                    onchange="partQuantityInStore('{{$index}}'); checkPartQuantity('{{$index}}');">

                @foreach($part->stores as $store)

                    <option value="{{$store->id}}" data-quantity="{{ $store->pivot ? $store->pivot->quantity : 0 }}"
                        {{isset($item) && $item->store_id == $store->id ? 'selected':'' }}>
                        {{$store->name}}
                    </option>
                @endforeach
            </select>
        </div>
    </td>

    <td>
        <input style="width: 120px !important;" type="number" class="form-control border1" id="quantity_{{$index}}"
               onkeyup="calculateItem('{{$index}}'); checkPartQuantity('{{$index}}'); quantityValidation('{{$index}}','{{__('sorry, quantity not valid')}}')"
               onchange="calculateItem('{{$index}}'); checkPartQuantity('{{$index}}');"
               value="{{isset($item) ? $item->quantity : 1}}" min="1" name="items[{{$index}}][quantity]">
    </td>

    <td>
        <input style="width: 150px !important;" type="text" id="price_{{$index}}" class="form-control border2"
               onkeyup="calculateItem('{{$index}}'); priceValidation('{{$index}}','{{__('sorry, price not valid')}}')"
               value="{{isset($item) ? $item->price : $part->default_purchase_price}}" name="items[{{$index}}][price]">
    </td>

    <td>
        <input style="width: 150px !important;" type="text" id="total_{{$index}}" disabled class="form-control border3"
               value="{{isset($item) ? ($item->price * $item->quantity) : 0}}"
               name="items[{{$index}}][total]">
    </td>

    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
        </div>
    </td>
</tr>


