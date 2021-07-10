<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td>
        <span style="width: 150px !important;display:block">{{$part->name}}</span>
        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" class="form-control" >
        <input type="hidden" value="{{$item->item_id}}" name="items[{{$index}}][item_id]" class="form-control" >
    </td>

    <td>
        <span>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : '---'}}</span>
    </td>

    <td>
        <span>{{ $item->partPriceSegment ? $item->partPriceSegment ->name:'---'}}</span>
    </td>

    <td>
        <?php
        $part_store = $part->stores()->where('store_id', $item->store_id)->first()
        ?>

        <span>{{$part_store && $part_store->pivot ? $part_store->pivot->quantity : 0 }}</span>
    </td>

    <td>
        <span>{{$item->max_quantity}}</span>
    </td>

    <td>
        <input style="width: 100px !important;" type="number" class="form-control" id="quantity_{{$index}}"
               value="{{ $item->quantity}}" min="0" name="items[{{$index}}][quantity]"
               onchange="checkQuantity('{{$index}}'); calculateItem('{{$index}}')"
               onkeyup="checkQuantity('{{$index}}'); calculateItem('{{$index}}')">

        <input type="hidden" value="{{$item->max_quantity}}" id="max_quantity_item_{{$index}}">
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control" id="price_{{$index}}"
               value="{{$item->price}}"
               min="0" name="items[{{$index}}][price]"
               onchange="calculateItem('{{$index}}')" onkeyup="calculateItem('{{$index}}')">
    </td>

    <td>
        <div class="radio primary">
            <input style="width: 150px !important;" type="radio" name="items[{{$index}}][discount_type]" id="discount_type_amount_{{$index}}"
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

    <td style="background:#FBE3E6 !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="discount_{{$index}}"
               value="{{ $item->discount }}" min="0"
               name="items[{{$index}}][discount]"
               onkeyup="calculateItem('{{$index}}')" onchange="calculateItem('{{$index}}')">
    </td>

    <td style="background:#E3FBEA !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="total_before_discount_{{$index}}" value="{{$item->sub_total}}" min="0"
               name="items[{{$index}}][total_before_discount]" disabled>
    </td>

    <td style="background:#E3E3FB !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="total_after_discount_{{$index}}" value="{{$item->total_after_discount}}" min="0"
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
                                    {{ $item && in_array($tax->id, $item->taxes->pluck('id')->toArray()) ? 'checked':''}}
                                >
                                <span>
                                    {{$tax->name}} - {{$tax->tax_type == 'amount' ? '$':'%'}} - {{ $tax->value }} -
                                    <span id="calculated_tax_value_{{$tax_index}}_{{$index}}">
                                         {{ taxValueCalculated($item->total, $item->sub_total, $tax )}}
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

            <input style="width: 150px !important;" type="number" class="form-control" id="tax_{{$index}}" value="{{ $item->tax}}"
                   min="0" name="items[{{$index}}][tax]" disabled>
        </div>
    </td>

    <td style="background:#FBFBE3 !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="total_{{$index}}" value="{{$item->total}}" min="0"
               name="items[{{$index}}][total]" disabled>
    </td>

    <td>
        <div class="col-md-2" style="margin-top: 10px;">
            <div class="form-group has-feedback">
                <input type="checkbox" id="checkbox_item_{{$index}}" name="items[{{$index}}][active]"
                       {{$item->active ? 'checked':''}}
                       onclick=" calculateItem('{{$index}}'); calculateTotal();" class="item_of_all" >
            </div>
        </div>
    </td>

    <td>
    <div style="padding:5px !important;">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>

            <button type="button" class="btn btn-primary waves-effect waves-light btn-xs" onclick="storeQuantity('{{$part->id}}')"
                    data-toggle="modal" data-target="#part_quantity" style="margin-right: 10px;">
                 
                    <li class="fa fa-cubes"></li> {{__('Stores Qty')}}
            </button>

        </div>
    </td>

</tr>




