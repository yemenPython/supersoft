{{--NOT USED PLEASE CHECK--}}

<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td>
        <span style="width: 150px !important;display:block">{{$part->name}}</span>
        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" class="form-control"
               style="text-align: center;">
    </td>

    <td>
        <span>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : '---'}}</span>
    </td>

    <td>
        <span>{{$item->partPriceSegment ? $item->partPriceSegment->name:'---'}}</span>
    </td>

    <td>
        <input style="width: 100px !important;" type="number" class="form-control border1" id="quantity_{{$index}}"
               value="{{ isset($item) ? $item->quantity : 0}}" min="0"
               name="items[{{$index}}][quantity]"
               onchange="calculateItem('{{$index}}')" onkeyup="calculateItem('{{$index}}')">
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control" id="price_{{$index}}" disabled
               value="{{isset($item) ? $item->price : $part->default_purchase_price}}"
               min="0" name="items[{{$index}}][price]"
               onchange="calculateItem('{{$index}}')" onkeyup="calculateItem('{{$index}}')">
    </td>

    <td>
        <div class="radio primary">
            <input style="width: 150px !important;" type="radio" name="items[{{$index}}][discount_type]" id="discount_type_amount_{{$index}}"
                   value="amount" {{!isset($item) ? 'checked':''}} onclick="calculateItem('{{$index}}')"
                {{isset($item) && $item->discount_type == 'amount'? 'checked' : '' }}
            >
            <label for="discount_type_amount_{{$index}}">{{__('amount')}}</label>
        </div>

        <div class="radio primary">
            <input style="width: 150px !important;" type="radio" name="items[{{$index}}][discount_type]"
                   id="discount_type_percent_{{$index}}" value="percent"
                   {{isset($item) && $item->discount_type == 'percent'? 'checked' : '' }}
                   onclick="calculateItem('{{$index}}')">
            <label for="discount_type_percent_{{$index}}">{{__('Percent')}}</label>
        </div>
    </td>

    <td style="background:#FBE3E6 !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="discount_{{$index}}" value="0" min="0"
               name="items[{{$index}}][discount]"
               onkeyup="calculateItem('{{$index}}')" onchange="calculateItem('{{$index}}')">
    </td>

    <td style="background:#E3FBEA !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="total_before_discount_{{$index}}"
               value="{{isset($item) ? $item->subtotal : 0 }}" min="0"
               name="items[{{$index}}][total_before_discount]" disabled>
    </td>

    <td style="background:#E3E3FB !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="total_after_discount_{{$index}}"
               value="{{isset($item) ? $item->total_after_discount : 0 }}" min="0"
               name="items[{{$index}}][total_after_discount]" disabled>
    </td>

    <td style="background:#FBFBE3 !important">
        <input style="width: 150px !important;" type="number" class="form-control" id="total_{{$index}}"
               value="{{isset($item) ? $item->total_after_discount : 0}}" min="0"
               name="items[{{$index}}][total]" disabled>
    </td>


    <td>
        <div class="input-group">
            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{$index}}][store_id]" id="store_part_{{$index}}">

                @foreach($part->stores as $store)
                    <option value="{{$store->id}}"
                        {{isset($item) && $item->store_id == $store->id ? 'selected':'' }}>
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




