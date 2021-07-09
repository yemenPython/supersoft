
@php
$index = 0;
@endphp

@foreach($purchaseRequest->items as $item)

    @php
        $part = $item->part;
    @endphp

    @foreach($item->spareParts as $itemType)

        @php
            $index +=1;
        @endphp

        @include('admin.purchase_quotations.part_raw')

    @endforeach

@endforeach
