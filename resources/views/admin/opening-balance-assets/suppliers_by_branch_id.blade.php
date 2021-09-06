<option value="">{{__('Select')}}</option>

@foreach($suppliers as $supplier)
    <option value="{{$supplier->id}}">
        {{$supplier->name}}
    </option>
@endforeach

