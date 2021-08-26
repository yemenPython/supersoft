<tr>
    <td>{{$key+1}}</td>
    <td>
        <span style="cursor: pointer" data-img="{{optional($item->part)->image}}" data-toggle="modal" data-target="#part_img"
              title="Part image" onclick="getPartImage('{{$key}}')" id="part_img_id_{{$key}}">
                  {{optional($item->part)->name}}
            </span>
    </td>

    <td>
        <span > {{ $item->sparePart ? $item->sparePart->type : '---' }} </span>
    </td>

    <td class="inline-flex-span">
    <span>{{optional($item->partPrice)->quantity}}</span>
        <span class="part-unit-span"> {{ $item->part && $item->part->sparePartsUnit ? $item->part->sparePartsUnit->unit : '---' }}  </span>
    </td>

    <td>
    <span>{{optional($item->partPrice)->unit->unit}}</span>
    <td>
    <span class="price-span">
            {{ $item->partPriceSegment ? $item->partPriceSegment->name : __('Not determined')}}
    </span>

    </td>

    <td class="text-danger">
    <span>{{$item->quantity}}</span>
    </td>

    <td style="width:150px">
    <span>
    {{$item->price}}</span>
    </td>

    <td>
    <span  style="background:#F7F8CC !important">
    {{ $item->price * $item->quantity}}</span>
    </td>

    <td>
    <span class="label wg-label" style="background: rgb(113, 101, 218) !important;">
        {{ $item->store ? $item->store->name : __('Not determined')}}
        </span>
    </td>

    <td>
        <button type="button" class="btn btn-primary" data-toggle="modal" onclick="showPartQuantity({{$item->part_id}})"
                data-target="#part_store_quantity">
                <i class="fa fa-cubes"></i>
        </button>
    </td>

</tr>


