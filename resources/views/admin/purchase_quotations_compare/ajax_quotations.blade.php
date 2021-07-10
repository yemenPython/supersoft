<option value="">{{__('Select')}}</option>
@foreach ($quotations as $quotation)
    <option value="{{$quotation->id}}">
        {{$quotation->number}}
    </option>
@endforeach
