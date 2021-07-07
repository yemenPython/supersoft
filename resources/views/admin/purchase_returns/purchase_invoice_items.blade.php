@php
    $index = 0;
@endphp

@foreach($purchaseInvoice->items as $item)

    @php
        $part = $item->part;
        $index +=1;
    @endphp

    @include('admin.purchase_returns.items.purchase_invoice')
@endforeach

