@foreach( $saleQuotations as $saleQuotation)

    <tr>
        <td>
            <input type="checkbox" name="sale_quotations[]" value="{{$saleQuotation->id}}"
                   {{$salesInvoice && in_array($saleQuotation->id, $salesInvoice->saleQuotations->pluck('id')->toArray()) ? 'checked':''}}
                   class="sale_quotation_box_{{$saleQuotation->id}}">
        </td>
        <td>
            <span>{{$saleQuotation->number}}</span>
        </td>
        <td>
            <span>{{optional($saleQuotation->salesable)->name}}</span>
        </td>
    </tr>

@endforeach
