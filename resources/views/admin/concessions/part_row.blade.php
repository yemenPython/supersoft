@foreach($items as $index=>$item)
    <tr class="text-center-inputs">
        <td>
            {{$index+1}}
        </td>

        <td>
            <span style="width: 150px !important;display:block; cursor: pointer" data-img="{{optional($item->part)->image}}" data-toggle="modal" data-target="#part_img"
                  title="Part image" onclick="getPartImage('{{$index}}')" id="part_img_id_{{$index}}">

                  {{optional($item->part)->name}}
            </span>
        </td>

        <td class="inline-flex-span">
        <span>
        {{optional($item->partPrice)->quantity}}
        </span>
            <span
                class="part-unit-span"> {{ $item->part && $item->part->sparePartsUnit ? $item->part->sparePartsUnit->unit : '---' }}  </span>
        </td>

        <td>
        <span>
        {{ $item->partPrice && $item->partPrice->unit ? $item->partprice->unit->unit : ''}}
        </span>
        </td>

        <td>

        <span class="price-span">
            {{ $item->partPriceSegment ? $item->partPriceSegment->name : __('Not determined')}}
        </span>

        </td>


        <td style="background:#FBE3E6 !important">
        <span>
        {{$item->quantity}}
        </span>
        </td>

        <td style="background:#E3FBEA !important">
        <span>
        {{ $item->price}}
        </span>
        </td>

        <td style="background:#FBFAD4 !important">
        <span>
        {{ $item->price * $item->quantity}}
        </span>
        </td>

    </tr>

@endforeach
