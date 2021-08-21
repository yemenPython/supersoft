@php
    $index = 0;
@endphp

@foreach($returnedItem->items as $item)

    @php
        $part = $item->part;
        $index +=1;
    @endphp

    @include('admin.sales_invoice_return.part_raw')
@endforeach
