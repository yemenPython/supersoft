<tr>
    <td>{{$key+1}}</td>
    <td>
    <span>{{optional($item->part)->name}}</span> 
    <!-- <input type="text" disabled value="{{optional($item->part)->name}}" class="form-control"
               style="text-align: center;"> -->
               
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

    <td style="background:#FBE3E6 !important">
    <span>{{$item->quantity}}</span>
    </td>

    <td style="background:#E3FBEA !important">
    <span>{{$item->price}}</span>
    </td>

    <td style="background:#FBFAD4 !important">
    <span>{{ $item->price * $item->quantity}}</span>
    </td>

</tr>


