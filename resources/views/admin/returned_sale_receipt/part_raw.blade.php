<tr id="tr_part_{{$index}}" class="text-center-inputs remove_on_change_branch">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
        <input type="hidden"
               name="items[{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][supply_order_item_id]"
               value="{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}">
    </td>

    <td>
        <span style="display:block; cursor: pointer;width: 150px !important;" data-img="{{$part->image}}" data-toggle="modal" data-target="#part_img" title="Part image"
              onclick="getPartImage('{{$index}}')" id="part_img_id_{{$index}}" >

            {{$part->name}}
        </span>
    </td>

    <td>
        <div class="input-group" style="width: 120px !important;">
        @if(isset($item))
            <span>{{$item->sparePart ? $item->sparePart->type : __('Not determined')}}</span>
        @elseif(isset($update_item))
            <span>{{$update_item->supplyOrderItem &&  $update_item->supplyOrderItem->sparePart ? $update_item->supplyOrderItem->sparePart->type :  __('Not determined')}}</span>
        @else
            <span> __('Not determined')}}</span>
        @endif
</div>
    </td>

    <td class="inline-flex-span">

        <span id="unit_quantity_{{$index}}">
            @if(isset($item))
                {{ $item->partPrice ? $item->partPrice->quantity : $part->first_price_quantity}}
            @elseif (isset($update_item))
                {{ $update_item->partPrice ? $update_item->partPrice->quantity : $part->first_price_quantity}}
            @else
                {{ $part->first_price_quantity}}
            @endif
        </span>

        <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span>
    </td>

    <td>
        <div class="input-group">
            @if(isset($update_item))
                <span>{{isset($update_item) && $update_item->partPrice && $update_item->partPrice->unit ? $update_item->partPrice->unit->unit : __('Not determined')}}</span>
            @else
                <span>{{isset($item) && $item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : __('Not determined')}}</span>
            @endif
        </div>
    </td>

    <td>
   
        <div class="input-group" style="width: 120px !important;">
            @if(isset($update_item))
            <span style="background:#F7F8CC !important">
            {{isset($update_item) ? $update_item->price : __('Not determined')}}</span>
                <input type="hidden" disabled id="price_{{$index}}" value="{{$update_item->price}}">
            @else
            <span style="background:#F7F8CC !important">
            {{isset($item) ? $item->price : __('Not determined')}}</span>
                <input type="hidden" disabled id="price_{{$index}}" value="{{$item->price}}">
            @endif
        </div>

    </td>

    <td>
        <input style="width: 130px !important;" type="number" class="form-control border1"
               id="total_quantity_{{$index}}"
               value="{{isset($update_item) ? $update_item->total_quantity : $item->quantity}}" disabled
               name="items[{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][total_quantity]">
    </td>

    <td>
        <input style="width: 130px !important;" type="number" class="form-control border5"
               id="old_accepted_quantity_{{$index}}"
               value="{{ isset($update_item) ? $update_item->old_accepted_quantity : $item->accepted_quantity}}"
               disabled>
    </td>

    <td>
        <input style="width: 130px !important;" type="number" class="form-control border4"
               id="remaining_quantity_{{$index}}"
               value="{{isset($update_item) ? $update_item->remaining_quantity : $item->remaining_quantity_for_accept}}"
               disabled>
    </td>

    <td>
        <input style="width: 130px !important;" type="number" class="form-control border2"
               id="refused_quantity_{{$index}}"
               value="{{isset($update_item) ? $update_item->remaining_quantity - $update_item->accepted_quantity  : 0}}"
               min="0"
               name="items[{{ isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][refused_quantity]"
               onchange="calculateRefusedQuantity('{{$index}}')" onkeyup="calculateRefusedQuantity('{{$index}}')">

    </td>

    <td>
        <input style="width: 130px !important;" type="number" class="form-control border6"
               id="accepted_quantity_{{$index}}"
               value="{{ isset($update_item) ? $update_item->accepted_quantity : $item->remaining_quantity_for_accept}}"
               min="0"
               name="items[{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][accepted_quantity]"
               onchange="calculateAcceptedQuantity('{{$index}}')" onkeyup="calculateAcceptedQuantity('{{$index}}')">

    </td>

    <td>
        <span
            id="defect_percent_{{$index}}">{{isset($update_item) ? ' % ' . $update_item->calculate_defected_percent : '0 %'}}</span>
    </td>

    <td>
        <div class="input-group">
            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{isset($update_item) ? $update_item->supply_order_item_id : $item->id}}][store_id]"
                    id="store_part_{{$index}}">

                @foreach($part->stores as $store)
                    <option value="{{$store->id}}"
                        {{isset($update_item) && $update_item->store_id == $store->id ? 'selected':'' }}>
                        {{$store->name}}
                    </option>
                @endforeach

            </select>
        </div>
    </td>
</tr>




