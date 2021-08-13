@foreach($saleSupplyOrder as $saleSupply)

    <tr>
        <td>
            <input type="checkbox" name="sale_quotations[]" value="{{$saleSupply->id}}"
                   {{$salesInvoice && in_array($saleSupply->id, $salesInvoice->saleSupplyOrders->pluck('id')->toArray()) ? 'checked':''}}
                   class="sale_quotation_box_{{$saleSupply->id}}"
            >
        </td>

        <td>
            <span>{{$saleSupply->number}}</span>
        </td>

        <td>
            <span>{{optional($saleSupply->salesable)->name}}</span>
        </td>
    </tr>

@endforeach
