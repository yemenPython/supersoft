<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td>

        <span style="width: 150px !important;display:block; cursor: pointer" data-img="{{$part->image}}" data-toggle="modal" data-target="#part_img" title="Part image" onclick="getPartImage('{{$index}}')"
              id="part_img_id_{{$index}}" >

            {{$part->name}}
        </span>

        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" class="form-control">
        <input type="hidden" value="{{$item->id}}" name="items[{{$index}}][item_id]" class="form-control" >
    </td>

    <td>
    <div class="input-group" style="width: 180px !important;">
    <span class="price-span">
        {{$item->sparePart ? $item->sparePart->type : __('Not determined')}}
</span>
        <input type="hidden" name="items[{{$index}}][spare_part_id]" value="{{$item->spare_part_id}}">
</div>
    </td>

    <td class="inline-flex-span">

        <span id="unit_quantity_{{$index}}">
            {{ $item->partPrice ? $item->partPrice->quantity : $part->first_price_quantity}}
        </span>

        <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span>
    </td>

    <td>
        <span class="part-unit-span">{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : __('Not determined')}}</span>
    </td>

    <td>
        <span class="price-span" style="width: 120px !important;display:block">{{$item->supplyOrderItem &&  $item->supplyOrderItem->partPriceSegment ? $item->supplyOrderItem->partPriceSegment ->name : __('Not determined')}}</span>
    </td>

    <td>
        <?php
        $part_store = $part->stores()->where('store_id', $item->store_id)->first()
        ?>

<span class="part-unit-span">{{$part_store && $part_store->pivot ? $part_store->pivot->quantity : 0 }}</span>
    </td>

    <td>
    <span class="part-unit-span">{{$item->accepted_quantity}}</span>
    </td>

    <td>
        <input style="width: 100px !important;" type="number" class="form-control border1" id="quantity_{{$index}}"
               value="{{ isset($item) ? $item->accepted_quantity : 0}}" min="0" name="items[{{$index}}][quantity]"
               onchange="checkQuantity('{{$index}}'); calculateItem('{{$index}}')"
               onkeyup="checkQuantity('{{$index}}'); calculateItem('{{$index}}')">

        <input type="hidden" value="{{$item->accepted_quantity}}" id="max_quantity_item_{{$index}}">
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border2" id="price_{{$index}}"
               value="{{$item->price}}" min="0" name="items[{{$index}}][price]"
               onchange="calculateItem('{{$index}}')" onkeyup="calculateItem('{{$index}}')">
    </td>

    <td>
        <div class="radio primary">
            <input style="width: 150px !important;" type="radio" name="items[{{$index}}][discount_type]" id="discount_type_amount_{{$index}}"
                   value="amount" onclick="calculateItem('{{$index}}')"
                {{isset($item) && $item->supplyOrderItem && $item->supplyOrderItem->discount_type == 'amount'? 'checked' : '' }}
            >
            <label for="discount_type_amount_{{$index}}">{{__('amount')}}</label>
        </div>

        <div class="radio primary">
            <input style="width: 150px !important;" type="radio" name="items[{{$index}}][discount_type]"
                   id="discount_type_percent_{{$index}}" value="percent"
                   {{isset($item) && $item->supplyOrderItem && $item->supplyOrderItem->discount_type == 'percent'? 'checked' : '' }}
                   onclick="calculateItem('{{$index}}')">
            <label for="discount_type_percent_{{$index}}">{{__('Percent')}}</label>
        </div>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border4" id="discount_{{$index}}"
               value="{{isset($item) && $item->supplyOrderItem ? $item->supplyOrderItem->discount : 0}}" min="0"
               name="items[{{$index}}][discount]"
               onkeyup="calculateItem('{{$index}}')" onchange="calculateItem('{{$index}}')">
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border2" id="total_before_discount_{{$index}}" value="0" min="0"
               name="items[{{$index}}][total_before_discount]" disabled>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border2" id="total_after_discount_{{$index}}" value="0" min="0"
               name="items[{{$index}}][total_after_discount]" disabled>
    </td>

    <td>
    <div class="btn-group" style="display:flex !important;align-items:center">
    <span type="button" class="fa fa-eye dropdown-toggle eye-table-wg" data-toggle="dropdown"
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
                                    {{ $item->supplyOrderItem && in_array($tax->id, $item->supplyOrderItem->taxes->pluck('id')->toArray()) ? 'checked':''}}
                                >
                                <span>
                                    {{$tax->name}} - {{$tax->tax_type == 'amount' ? '$':'%'}} - {{ $tax->value }} -
                                    <span id="calculated_tax_value_{{$tax_index}}_{{$index}}">
                                         {{ $item->supplyOrderItem ? taxValueCalculated($item->supplyOrderItem->total, $item->supplyOrderItem->sub_total, $tax ) : 0}}
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

            <input style="width: 120px !important; margin: 0 5px;" type="number" class="form-control border5" id="tax_{{$index}}"
                   value="{{isset($update_item) ? $update_item->tax : 0 }}"
                   min="0" name="items[{{$index}}][tax]" disabled>
        </div>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border3" id="total_{{$index}}" value="0" min="0"
               name="items[{{$index}}][total]" disabled>
    </td>

    <td>
        <div class="col-md-2" style="margin-top: 10px;">
            <div class="form-group has-feedback">
                <input type="checkbox" id="checkbox_item_{{$index}}" name="items[{{$index}}][active]"
                       onclick=" calculateItem('{{$index}}'); calculateTotal();" class="item_of_all" checked>
            </div>
        </div>
    </td>

    <td>
    <div style="padding:5px !important;">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>

            <button type="button" class="btn btn-primary waves-effect waves-light btn-xs" onclick="storeQuantity('{{$part->id}}')"
                    data-toggle="modal" data-target="#part_quantity" style="margin-right: 10px;margin-top:5px">

                    <li class="fa fa-cubes"></li> {{__('Stores Qty')}}
            </button>
        </div>
    </td>

</tr>




