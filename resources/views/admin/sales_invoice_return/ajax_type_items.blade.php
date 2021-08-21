<option value="">{{__('Select')}}</option>

@foreach($items as $item)
    <option value="{{$item->id}}"
        {{isset($salesInvoice) && $salesInvoice->invoice_type == 'normal'? 'selected':'' }}>
        {{ $item->number }}
    </option>
@endforeach
