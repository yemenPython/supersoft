<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td>
        <span style="width: 150px !important;display:block; cursor: pointer" data-img="{{$part->image}}"
              data-toggle="modal"
              data-target="#part_img" title="Part image" onclick="getPartImage('{{$index}}')"
              id="part_img_id_{{$index}}">

            {{$part->name}}
        </span>

        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" class="form-control"
               style="text-align: center;">
    </td>

    
    <td>
        <div class="input-group"  style="width: 180px !important;">

            <select class="form-control js-example-basic-single" name="items[{{$index}}][spare_part_id]"
                    id="spare_part_id_{{$index}}">

                @foreach($part->part_types_tree as $sparePartId => $sparePartValue)
                    <option
                        value="{{$sparePartId}}"{{isset($item) && $item->spare_part_id == $sparePartId ? 'selected':''}}>
                        {{$sparePartValue}}
                    </option>
                @endforeach
            </select>
        </div>
    </td>

    <td class="inline-flex-span">

        <span id="unit_quantity_{{$index}}">
            {{ isset($item) && $item->partPrice ? $item->partPrice->quantity : $part->first_price_quantity}}
        </span>

        <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span>
    </td>

    <td>
    <div class="input-group" style="width: 90px !important;">

            <select class="form-control js-example-basic-single" name="items[{{$index}}][part_price_id]"
                    id="prices_part_{{$index}}"
                    onchange="priceSegments('{{$index}}'); getPurchasePrice('{{$index}}'); calculateItem('{{$index}}')">

                @foreach($part->prices as $price)
                    <option value="{{$price->id}}"
                            data-quantity="{{$price->quantity}}"
                            data-purchase-price="{{$price->purchase_price}}"
                            data-big-percent-discount="{{$price->biggest_percent_discount}}"
                            data-big-amount-discount="{{$price->biggest_amount_discount}}"
                        {{isset($item) && $item->part_price_id == $price->id ? 'selected':''}}
                    >
                        {{optional($price->unit)->unit}}
                    </option>
                @endforeach
            </select>
        </div>
        {{input_error($errors, 'items['.$index.'][part_price_id]')}}
    </td>

    <td>
        <div class="input-group" id="price_segments_part_{{$index}}" style="width: 150px !important;">

            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{$index}}][part_price_segment_id]"
                    id="price_segments_part_{{$index}}"
                    onchange="getPurchasePriceFromSegments('{{$index}}'); calculateItem('{{$index}}') ">

                @if(isset($item) && $item->partPrice)

                    <option value="">{{__('Select Segment')}}</option>

                    @foreach($item->partPrice->partPriceSegments as $priceSegment)
                        <option value="{{$priceSegment->id}}"
                                data-purchase-price="{{$priceSegment->purchase_price}}"
                            {{isset($item) && $item->part_price_segment_id == $priceSegment->id  ? 'selected':''}}
                        >
                            {{$priceSegment->name}}
                        </option>
                    @endforeach

                @else

                    @if($part->prices->first())

                        <option value="">{{__('Select Segment')}}</option>

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

        <input style="width: 100px !important;" type="number" class="form-control border1" id="quantity_{{$index}}"
               value="{{isset($item) ? $item->quantity : 0}}" min="0"
               name="items[{{$index}}][quantity]"
               onchange="calculateItem('{{$index}}')" onkeyup="calculateItem('{{$index}}')">

    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border2" id="price_{{$index}}"
               value="{{isset($item) ? $item->price : $part->default_purchase_price}}"
               min="0" name="items[{{$index}}][price]"
               onchange="calculateItem('{{$index}}')" onkeyup="calculateItem('{{$index}}')">
    </td>


    <td>
        <div class="radio primary">
            <input type="radio" name="items[{{$index}}][discount_type]" id="discount_type_amount_{{$index}}"
                   value="amount" {{!isset($item) ? 'checked':''}} onclick="calculateItem('{{$index}}')"
                {{isset($item) && $item->discount_type == 'amount'? 'checked' : '' }}
            >
            <label for="discount_type_amount_{{$index}}">{{__('amount')}}</label>
        </div>

        <div class="radio primary">
            <input type="radio" name="items[{{$index}}][discount_type]"
                   id="discount_type_percent_{{$index}}" value="percent"
                   {{isset($item) && $item->discount_type == 'percent'? 'checked' : '' }}
                   onclick="calculateItem('{{$index}}')">
            <label for="discount_type_percent_{{$index}}">{{__('Percent')}}</label>
        </div>

    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border4" id="discount_{{$index}}"
               value="{{isset($item) ? $item->discount : 0 }}" min="0"
               name="items[{{$index}}][discount]"
               onkeyup="calculateItem('{{$index}}')" onchange="calculateItem('{{$index}}')">
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border2"
               id="total_before_discount_{{$index}}"
               value="{{isset($item) ? $item->sub_total : 0 }}" min="0"
               name="items[{{$index}}][total_before_discount]" disabled>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border2"
               id="total_after_discount_{{$index}}"
               value="{{isset($item) ? $item->total_after_discount : 0 }}" min="0"
               name="items[{{$index}}][total_after_discount]" disabled>
        {{input_error($errors, 'items['.$index.'][total_after_discount]')}}
    </td>

    <td>
        <div class="btn-group" style="display:flex !important;align-items:center">
    <span type="button" class="fa fa-eye eye-table-wg dropdown-toggle" data-toggle="dropdown"
          style="
    color: #a776e7;
    padding: 6px 10px;
    border-radius: 0;
    border: 1px solid #3f3f3f;
    cursor: pointer;
    font-size: 20px;
}"
          aria-haspopup="true" aria-expanded="false">
            </span>

            <ul class="dropdown-menu for-design-eye-style" style="margin-top: 19px;">
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
                                    {{!isset($item) ? 'checked':''}}
                                    {{isset($item) && in_array($tax->id, $item->taxes->pluck('id')->toArray()) ? 'checked':''}}
                                >
                                <span>
                                    {{$tax->name}} ( {{ $tax->value }} {{$tax->tax_type == 'amount' ? '$':'%'}} ) =
                                    <span id="calculated_tax_value_{{$tax_index}}_{{$index}}">
                                         {{isset($item) ? taxValueCalculated($item->total_after_discount, $item->sub_total, $tax ) : 0}}
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

            <input style="width: 120px !important;
    margin: 0 5px;" type="number" class="form-control border5" id="tax_{{$index}}"
                   value="{{isset($item) ? $item->tax : 0 }}" min="0" name="items[{{$index}}][tax]" disabled>
        </div>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border3" id="total_{{$index}}"
               value="{{isset($item) ? $item->total : 0}}" min="0"
               name="items[{{$index}}][total]" disabled>
    </td>

    <td>
        <div class="col-md-2" style="margin-top: 10px;">
            <div class="form-group has-feedback">
                <input type="checkbox" id="checkbox_item_{{$index}}" name="items[{{$index}}][checked]"
                       onclick=" calculateItem('{{$index}}'); calculateTotal();" class="item_of_all"
                    {{isset($item) && $item->active ? 'checked' : ''}}
                    {{ !isset($item) ? 'checked' : ''}}
                >
            </div>
        </div>
    </td>

    <td>

        <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>

        {{--        <div style="padding:5px !important;">--}}
        {{--            <a data-toggle="modal" data-target="#part_types_{{$index}}" title="Part Types" class="btn btn-info">--}}
        {{--                <i class="fa fa-check-circle"> </i> {{__('Types')}}--}}
        {{--            </a>--}}
        {{--        </div>--}}
    </td>

{{--    <td style="display: none;">--}}

{{--        @php--}}
{{--            $partTypes = isset($partTypes) ? $partTypes : partTypes($part);--}}
{{--        @endphp--}}

{{--        @foreach($partTypes as $key=>$value)--}}
{{--            <div class="checkbox">--}}
{{--                <input type="checkbox" id="item_type_real_checkbox_{{$index}}_{{$key}}" value="{{$key}}"--}}
{{--                       name="items[{{$index}}][item_types][{{$key}}][id]"--}}
{{--                    {{isset($item) && in_array($key, $item->spareParts->pluck('id')->toArray()) ? 'checked':''}}--}}
{{--                    {{isset($update_item) && in_array($key, $update_item->spareParts->pluck('id')->toArray()) ? 'checked':''}}--}}
{{--                >--}}

{{--                <input type="hidden" value="0" class="form-control"--}}
{{--                       name="items[{{$index}}][item_types][{{$key}}][price]"--}}
{{--                       id="item_type_real_price_{{$index}}_{{$key}}">--}}

{{--                <label for="item_type_real_checkbox_{{$index}}_{{$key}}"></label>--}}
{{--            </div>--}}
{{--        @endforeach--}}

{{--    </td>--}}

</tr>




