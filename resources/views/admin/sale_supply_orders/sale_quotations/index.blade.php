<table id="sale_quotations_table" class="table table-bordered" style="width:100%">
    <thead>
    <tr>
        <th scope="col">{!! __('Check') !!}</th>
        <th scope="col">{!! __('Sale Quotation num.') !!}</th>
        <th scope="col">{!! __('Customer name') !!}</th>
    </tr>
    </thead>

    <form id="sale_quotation_form" method="post">
        @csrf


        <tbody id="sale_quotation_data">

        @foreach( $saleQuotations as $saleQuotation)

            <tr>
                <td>
                    <input type="checkbox" name="sale_quotations[]" value="{{$saleQuotation->id}}"
                           {{$saleSupplyOrder && in_array($saleQuotation->id, $saleSupplyOrder->saleQuotations->pluck('id')->toArray()) ? 'checked':''}}
                           class="sale_quotation_box_{{$saleQuotation->id}} quotations_boxes"
                    >
                </td>
                <td>
                    <span>{{$saleQuotation->number}}</span>
                </td>
                <td>
                    <span>{{optional($saleQuotation->salesable)->name}}</span>
                </td>
            </tr>

        @endforeach

        </tbody>

    </form>
</table>
