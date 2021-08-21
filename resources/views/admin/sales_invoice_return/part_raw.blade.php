<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td>
        <span style="width: 150px !important;display:block; cursor: pointer" data-img="{{$part->image}}"
              data-toggle="modal" data-target="#part_img" title="Part image" onclick="getPartImage('{{$index}}')"
              id="part_img_id_{{$index}}">

            {{$part->name}}
        </span>

        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" class="form-control">

        <input type="hidden" value="{{isset($actionType) && $actionType == 'update' ? $item->itemable_id:$item->id}}"
               name="items[{{$index}}][item_id]" class="form-control">
    </td>

    <td>
        <div class="input-group" style="width: 180px !important;">
            <span class="price-span">    {{$item->sparePart ? $item->sparePart->type : __('Not determined')}}</span>
{{--            <input type="hidden" name="items[{{$index}}][spare_part_id]" value="{{$item->spare_part_id}}">--}}
        </div>
    </td>

    <td class="inline-flex-span">

        <span id="unit_quantity_{{$index}}">
            {{ $item->partPrice ? $item->partPrice->quantity : $part->first_price_quantity}}
        </span>

        <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span>
    </td>

    <td>
        <span class="part-unit-span">
            {{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : __('Not determined')}}
        </span>
    </td>

    <td>
        <span class="price-span" style="width: 120px !important;display:block">
            {{ $item->partPriceSegment ? $item->partPriceSegment->name : __('Not determined')}}
        </span>
    </td>

    <td>
        <?php
        $part_store = $part->stores()->where('store_id', $item->store_id)->first()
        ?>

        <span class="part-unit-span"> {{$part_store && $part_store->pivot ? $part_store->pivot->quantity : 0 }}</span>
    </td>

    <td>
        <span class="part-unit-span"> {{$item->quantity}}</span>
    </td>

    <td>
        <input style="width: 100px !important;" type="number" class="form-control border1" id="quantity_{{$index}}"
               value="{{$item->quantity}}" min="1" name="items[{{$index}}][quantity]"
               onchange="checkQuantity('{{$index}}'); calculateItem('{{$index}}');
               quantityValidation('{{$index}}','{{__('sorry, quantity not valid')}}')"

               onkeyup="checkQuantity('{{$index}}'); calculateItem('{{$index}}');
                   quantityValidation('{{$index}}','{{__('sorry, quantity not valid')}}')">

        <input type="hidden" value="{{$item->quantity}}" id="max_quantity_item_{{$index}}">
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control" id="price_{{$index}}" value="{{$item->price}}" disabled
               min="0" name="items[{{$index}}][price]"
               onchange="calculateItem('{{$index}}')"
               onkeyup="calculateItem('{{$index}}');priceValidation('{{$index}}','{{__('sorry, price not valid')}}')">
    </td>

    <td>
        <div class="radio primary">
            <input style="width: 150px !important;" type="radio" name="items[{{$index}}][discount_type]"
                   id="discount_type_amount_{{$index}}"
                   value="amount" onclick="calculateItem('{{$index}}')"
                {{ $item->discount_type == 'amount'? 'checked' : '' }}
            >
            <label for="discount_type_amount_{{$index}}">{{__('amount')}}</label>
        </div>

        <div class="radio primary">
            <input style="width: 150px !important;" type="radio" name="items[{{$index}}][discount_type]"
                   id="discount_type_percent_{{$index}}" value="percent"
                   {{ $item->discount_type == 'percent'? 'checked' : '' }}
                   onclick="calculateItem('{{$index}}')">
            <label for="discount_type_percent_{{$index}}">{{__('Percent')}}</label>
        </div>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border4" id="discount_{{$index}}"
               value="{{ $item->discount }}" min="0"
               name="items[{{$index}}][discount]"
               onkeyup="calculateItem('{{$index}}'); discountValidation('{{$index}}','{{__('sorry, discount not valid')}}')"
               onchange="calculateItem('{{$index}}')">
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border2"
               id="total_before_discount_{{$index}}" value="0" min="0"
               name="items[{{$index}}][total_before_discount]" disabled>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border2"
               id="total_after_discount_{{$index}}" value="0" min="0"
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
}" aria-haspopup="true" aria-expanded="false">
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
                                    {{ $item && in_array($tax->id, $item->taxes->pluck('id')->toArray()) ? 'checked':''}}
                                >
                                <span>
                                    {{$tax->name}} - {{$tax->tax_type == 'amount' ? '$':'%'}} - {{ $tax->value }} -
                                    <span id="calculated_tax_value_{{$tax_index}}_{{$index}}">
                                         {{taxValueCalculated($item->total, $item->sub_total, $tax)}}
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

            <input style="width: 120px !important; margin: 0 5px;" type="number" class="form-control" id="tax_{{$index}}" value="{{ $item->tax}}"
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
                       onclick=" calculateItem('{{$index}}'); calculateTotal();" class="item_of_all" checked
                >
            </div>
        </div>
    </td>

    <td>

        <div class="btn-group margin-top-10">

            <button type="button" class="btn btn-options dropdown-toggle"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="ico fa fa-bars"></i>
                {{__('Options')}} <span class="caret"></span>

            </button>

            <ul class="dropdown-menu dropdown-wg">


                <li class="btn-style-drop" style="margin-bottom:2px !important">

                    <button type="button" class="btn btn-danger" onclick="removeItem('{{$index}}')">
                        <i class="fa fa-trash"></i> {{__('Delete')}}
                    </button>
                </li>

                <li class="btn-style-drop">
                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="storeQuantity('{{$part->id}}')"
                            data-toggle="modal" data-target="#part_quantity">

                <li class="fa fa-cubes"></li>
                {{__('Stores Qty')}}
                </button>
                </li>


            </ul>
        </div>

    </td>

</tr>




