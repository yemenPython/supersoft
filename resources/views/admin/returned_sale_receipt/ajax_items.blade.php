@php
    $index = 0;
@endphp

@foreach($saleModel->items as $item)
    @php
        $part = $item->part;
        $index +=1;
    @endphp

    @include('admin.returned_sale_receipt.part_raw')
@endforeach
