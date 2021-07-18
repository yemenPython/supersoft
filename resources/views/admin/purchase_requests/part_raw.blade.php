<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td class="text-center">

        <span style=" cursor: pointer" data-img="{{$part->image}}" data-toggle="modal"
              data-target="#part_img" title="Part image" onclick="getPartImage('{{$index}}')"
              id="part_img_id_{{$index}}">

            {{$part->name}}
        </span>

        <input type="hidden" value="{{$part->id}}" name="items[{{$index}}][part_id]" id="part_id_index_{{$index}}"
               class="form-control"
               style="text-align: center;">

        @if(isset($item) && isset($request_type) && $request_type == 'approval')
            <input type="hidden" value="{{$item->id}}" name="items[{{$index}}][item_id]" class="form-control"
                   style="text-align: center;">
        @endif
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
                    id="prices_part_{{$index}}" onchange="defaultUnitQuantity('{{$index}}')"
                {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
            >

                @foreach($part->prices as $price)
                    <option data-quantity="{{$price->quantity}}"
                            value="{{$price->id}}"{{isset($item) && $item->part_price_id == $price->id ? 'selected':''}}>
                        {{optional($price->unit)->unit}}
                    </option>
                @endforeach
            </select>
        </div>
        {{input_error($errors, 'items['.$index.'][part_price_id]')}}
    </td>

    <td>
        <input style="width: 130px !important;margin:0 auto;display:block" type="number" class="form-control border1"
               id="quantity_{{$index}}"
               value="{{isset($item) ? $item->quantity : 0}}" min="0"
               name="items[{{$index}}][quantity]" {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}>

        {{input_error($errors, 'items['.$index.'][quantity]')}}
    </td>

    @if(isset($request_type) && $request_type == 'approval')
        <td>
            <input style="width: 150px !important;" type="number" class="form-control border2"
                   value="{{isset($item) ? $item->part->quantity : 0}}" disabled>
        </td>

        <td>
            <input style="width: 150px !important;" type="number" class="form-control border3" id="quantity_{{$index}}"
                   value="{{isset($item) ? $item->approval_quantity : 0}}"
                   min="0" name="items[{{$index}}][approval_quantity]">
        </td>
    @endif

    <td>
<ul class="list-inline" style="display:flex;justify-content:center;align-items:center;margin:0;padding:0">
  <li>
  <a data-toggle="modal" data-target="#part_types_{{$index}}" title="Part Types" class="btn btn-info">
            <i class="fa fa-check-circle"> </i> {{__('Types')}}
        </a>
  </li>
  <li>
  {{--        @if(!isset($request_type) || ( isset($request_type) && $request_type != 'approval'))--}}
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
{{--        @endif--}}
  </li>
</ul>
   



        <div style="padding:0px !important;">
            @if(isset($request_type) && $request_type == 'approval')
                <a data-toggle="modal" data-target="#part_quantity_{{$index}}"
                   title="Part quantity" class="btn btn-primary">
                    <li class="fa fa-cubes"></li> {{__('Stores Qty')}}
                </a>
            @endif
        </div>


    </td>

    <td style="display: none;">

        @php
            $partTypes = isset($partTypes) ? $partTypes : partTypes($part);
        @endphp

        @foreach($partTypes as $key=>$value)
            <div class="checkbox">
                <input type="checkbox" id="item_type_real_checkbox_{{$index}}_{{$key}}" value="{{$key}}"
                       name="items[{{$index}}][item_types][]"
                    {{isset($item) && in_array($key, $item->spareParts->pluck('id')->toArray()) ? 'checked':''}}
                    {{ !isset($item) && $part->First_sub_part_type_id == $key ? 'checked':''}}
                >

                <label for="item_type_real_checkbox_{{$index}}_{{$key}}"></label>
            </div>
        @endforeach

    </td>

</tr>




