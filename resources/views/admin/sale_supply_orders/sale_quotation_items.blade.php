
@php
$index = 0;
@endphp

@foreach($saleQuotations as $saleQuotation)

    @foreach($saleQuotation->items as $item)
        @php
            $part = $item->part;
            $index +=1;
        @endphp

        @include('admin.sale_supply_orders.part_raw')
    @endforeach
@endforeach
