@foreach($items as $index=>$item)
    <tr class="text-center-inputs">
        <td>
            {{$index+1}}
        </td>

        <td>
        <span style="width:150px;display:block">{{optional($item->part)->name}}</span> 
        <!-- <input type="text" disabled value="{{optional($item->part)->name}}" class="form-control" style="text-align: center;"> -->
        
        </td>

        <td class="inline-flex-span" >
        <span>
        {{optional($item->partPrice)->quantity}}
        </span>
            <span class="part-unit-span"> {{ $item->part && $item->part->sparePartsUnit ? $item->part->sparePartsUnit->unit : '---' }}  </span>
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

      
        <td class="text-danger">
        <span>
        {{$item->quantity}}
        </span>
        </td>

        <td>
        <span style="background:#F7F8CC !important">
        {{ $item->price}}
        </span>
        </td>

        <td>
        <span style="background:rgb(253, 215, 215) !important">
        {{ $item->price * $item->quantity}}
        </span>
        </td>

    </tr>

@endforeach
