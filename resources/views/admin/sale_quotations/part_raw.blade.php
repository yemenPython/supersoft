<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs tr_part_{{$index}}">

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
                            data-purchase-price="{{$price->selling_price}}"
                            data-big-percent-discount="{{$price->biggest_percent_discount}}"
                            data-big-amount-discount="{{$price->biggest_amount_discount}}"
                            data-barcode="{{$price->barcode}}"
                            data-supplier-barcode="{{$price->supplier_barcode}}"
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
                                data-purchase-price="{{$priceSegment->sales_price}}"
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
                                    data-purchase-price="{{$priceSegment->sales_price}}"
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

        <button type="button" class="btn btn-default btn-sm accordion-toggle" data-toggle="collapse" data-target="#demo{{$index}}" >
            <i class="glyphicon glyphicon-eye-open"></i>
        </button>

        <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>

    </td>


</tr>


{{-- SECOND TR --}}

<tr class="tr_part_{{$index}}">

    <td colspan="12" class="hiddenRow">
        <div class="accordian-body collapse" id="demo{{$index}}">
            <table class=" table table-responsive table-bordered table-hover">
                <thead>
                <tr class="info">
                    <th width="7%"> {{ __('Quantity') }} </th>
                    <th width="8%"> {{ __('Price') }} </th>
                    <th width="4%"> {{ __('Discount Type') }} </th>
                    <th width="5%"> {{ __('Discount') }} </th>
                    <th width="8%"> {{ __('Total Before Discount') }} </th>
                    <th width="8%"> {{ __('Total After Discount') }} </th>
                    <th width="8%"> {{ __('Taxes') }} </th>
                    <th width="8%"> {{ __('Total') }} </th>
                </tr>
                </thead>

                <tbody>

                <tr>

                    <td>

                        <input style="width: 100px !important;" type="number" class="form-control border1" id="quantity_{{$index}}"
                               value="{{isset($item) ? $item->quantity : 1}}" min="1"
                               name="items[{{$index}}][quantity]"
                               onchange="calculateItem('{{$index}}')"
                               onkeyup="calculateItem('{{$index}}'); quantityValidation('{{$index}}', '{{__('sorry, quantity not valid')}}')">

                    </td>

                    <td>
                        <input style="width: 150px !important;" type="number" class="form-control border2" id="price_{{$index}}"
                               value="{{isset($item) ? $item->price : $part->default_sale_price}}"
                               min="0" name="items[{{$index}}][price]"
                               onchange="calculateItem('{{$index}}')"
                               onkeyup="calculateItem('{{$index}}');
                                   priceValidation('{{$index}}', '{{__('sorry, price not valid')}}')">
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
                               onkeyup="calculateItem('{{$index}}'); discountValidation('{{$index}}', '{{__('sorry, discount not valid')}}')"
                               onchange="calculateItem('{{$index}}')">
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

                </tr>

                </tbody>
            </table>

        </div>
    </td>
</tr>




