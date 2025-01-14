<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td>
        <span style="width: 150px !important;display:block">{{$part->name}}</span>
        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" class="form-control" style="text-align: center;">
    </td>

    <td>
        <div class="input-group">

            <select style="width: 150px !important;" class="form-control js-example-basic-single" name="items[{{$index}}][part_price_id]"
                    id="prices_part_{{$index}}"
                    onchange="priceSegments('{{$index}}'); getPurchasePrice('{{$index}}'); calculateItem('{{$index}}')">

                @foreach($part->prices as $price)
                    <option {{isset($update_item) && $update_item->part_price_id == $price->id ? 'selected':''}}
                            data-purchase-price="{{$price->purchase_price}}"
                            data-big-percent-discount="{{$price->biggest_percent_discount}}"
                            data-big-amount-discount="{{$price->biggest_amount_discount}}"
                            value="{{$price->id}}">
                        {{optional($price->unit)->unit}}
                    </option>
                @endforeach
            </select>
        </div>
        {{input_error($errors, 'items['.$index.'][part_price_id]')}}
    </td>


    <td>
        <div class="input-group" id="price_segments_part_{{$index}}">

            <select style="width: 150px !important;" class="form-control js-example-basic-single" name="items[{{$index}}][part_price_segment_id]"
                    id="price_segments_part_{{$index}}"
                    onchange="getPurchasePriceFromSegments('{{$index}}'); calculateItem('{{$index}}') ">

                @if(isset($update_item) && $update_item->partPrice)

                    <option value="">{{__('Select Segment')}}</option>

                    @foreach($update_item->partPrice->partPriceSegments as $priceSegment)
                        <option value="{{$priceSegment->id}}"
                                data-purchase-price="{{$priceSegment->purchase_price}}"
                            {{isset($update_item) && $update_item->part_price_segment_id == $priceSegment->id  ? 'selected':''}}>
                            {{$priceSegment->name}}
                        </option>
                    @endforeach

                @else

                    @if($part->prices->first())

                        <option value="">{{__('Select Segment')}}</option>

                        @foreach($part->first_price_segments as $priceSegment)
                            <option value="{{$priceSegment->id}}" data-purchase-price="{{$priceSegment->purchase_price}}"
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

        @if(isset($item))

            <input style="width: 100px !important;" type="number" class="form-control" id="quantity_{{$index}}"
                   value="{{ $item->quantity}}" min="0"
                   name="items[{{$index}}][quantity]"
                   onchange="calculateItem('{{$index}}')" onkeyup="calculateItem('{{$index}}')">

        @else
            <input style="width: 100px !important;" type="number" class="form-control" id="quantity_{{$index}}"
                   value="{{isset($update_item) ? $update_item->quantity : 0}}" min="0"
                   name="items[{{$index}}][quantity]"
                   onchange="calculateItem('{{$index}}')" onkeyup="calculateItem('{{$index}}')">

        @endif
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control" id="price_{{$index}}"
               value="{{isset($update_item) ? $update_item->price : $part->default_purchase_price}}"
               min="0" name="items[{{$index}}][price]"
               onchange="calculateItem('{{$index}}')" onkeyup="calculateItem('{{$index}}')">
        {{input_error($errors, 'items['.$index.'][price]')}}
    </td>

    <td>
        <div class="radio primary">
            <input style="width: 150px !important;" type="radio" name="items[{{$index}}][discount_type]" id="discount_type_amount_{{$index}}"
                   value="amount" {{!isset($update_item) ? 'checked':''}} onclick="calculateItem('{{$index}}')"
                {{isset($update_item) && $update_item->discount_type == 'amount'? 'checked' : '' }}
            >
            <label for="discount_type_amount_{{$index}}">{{__('amount')}}</label>
        </div>
        <div class="radio primary">
            <input style="width: 150px !important;" type="radio" name="items[{{$index}}][discount_type]"
                   id="discount_type_percent_{{$index}}" value="percent"
                   {{isset($update_item) && $update_item->discount_type == 'percent'? 'checked' : '' }}
                   onclick="calculateItem('{{$index}}')">
            <label for="discount_type_percent_{{$index}}">{{__('Percent')}}</label>
        </div>

    </td>

    <td style="background:#FBE3E6 !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="discount_{{$index}}"
               value="{{isset($update_item) ? $update_item->discount : 0 }}" min="0"
               name="items[{{$index}}][discount]"
               onkeyup="calculateItem('{{$index}}')" onchange="calculateItem('{{$index}}')">
    </td>

    <td style="background:#E3FBEA !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="total_before_discount_{{$index}}"
               value="{{isset($update_item) ? $update_item->subtotal : 0 }}" min="0"
               name="items[{{$index}}][total_before_discount]" disabled>
    </td>

    <td style="background:#E3E3FB !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="total_after_discount_{{$index}}"
               value="{{isset($update_item) ? $update_item->total_after_discount - $update_item->tax : 0 }}" min="0"
               name="items[{{$index}}][total_after_discount]" disabled>
    </td>

    <td style="background:#E3F6FB !important">
        <div class="btn-group ">
            <span type="button" class="fa fa-usd  dropdown-toggle" data-toggle="dropdown"
                  style="background-color: rgb(244, 67, 54); color: white; padding: 3px; border-radius: 5px; cursor: pointer"
                  aria-haspopup="true" aria-expanded="false">
            </span>

            <ul class="dropdown-menu" style="margin-top: 19px;">
                @if($part->taxes->count())
                    @foreach($part->taxes as $tax_index => $tax)

                        @php
                            $tax_index +=1;

                        @endphp

                        <li>
                            <a>
                                <input type="checkbox" id="checkbox_tax_{{$tax_index}}_{{$index}}"
                                       name="items[{{$index}}][taxes][]" value="{{$tax->id}}"
                                       data-tax-value="{{$tax->value}}"
                                       data-tax-type="{{$tax->tax_type}}"
                                       data-tax-execution-time="{{$tax->execution_time}}"
                                       onclick="calculateItem('{{$index}}')"
                                    {{!isset($update_item) ? 'checked':''}}
                                    {{isset($update_item) && in_array($tax->id, $update_item->taxes->pluck('id')->toArray()) ? 'checked':''}}
                                >
                                <span>
                                    {{$tax->name}} - {{$tax->tax_type == 'amount' ? '$':'%'}} - {{ $tax->value }} -
                                    <span id="calculated_tax_value_{{$tax_index}}_{{$index}}">
                                         {{isset($update_item) ? taxValueCalculated($update_item->total_after_discount - $update_item->tax, $update_item->subtotal, $tax ) : 0}}
                                    </span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                @else
                    <li>
                        <a>
                            <span>{{ __('No Taxes Founded') }}</span>
                        </a>
                    </li>

                @endif
            </ul>

            <input type="hidden" id="tax_count_{{$index}}" value="{{$part->taxes->count()}}">

            <input style="width: 150px !important;" type="number" class="form-control" id="tax_{{$index}}"
                   value="{{isset($update_item) ? $update_item->tax : 0 }}"
                   min="0" name="items[{{$index}}][tax]" disabled>
        </div>
    </td>

    <td style="background:#FBFBE3 !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="total_{{$index}}"
               value="{{isset($update_item) ? $update_item->total_after_discount : 0}}" min="0"
               name="items[{{$index}}][total]" disabled>
    </td>

    <td>
        <div class="input-group">
            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{$index}}][store_id]" id="store_part_{{$index}}">

                @foreach($part->stores as $store)
                    <option value="{{$store->id}}"
                        {{isset($update_item) && $update_item->store_id == $store->id ? 'selected':'' }}>
                        {{$store->name}}
                    </option>
                @endforeach

            </select>
        </div>
    </td>

    <td>
        <div>
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
        </div>
    </td>

</tr>




