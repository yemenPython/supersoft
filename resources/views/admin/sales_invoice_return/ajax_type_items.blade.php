<option value="">{{__('Select')}}</option>

@foreach($items as $item)
    <option value="{{$item->id}}">{{ $item->number }}</option>
@endforeach
