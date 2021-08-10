
@php
    $index = 0;
@endphp

@foreach($saleSupplyOrders as $saleSupplyOrder)

    @foreach($saleSupplyOrder->items as $item)
        @php
            $part = $item->part;
            $index +=1;
        @endphp

        @include('admin.sales_invoice.part_raw')
    @endforeach
@endforeach
