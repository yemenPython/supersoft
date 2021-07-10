<option value="">{{__('Select')}}</option>
@foreach ($parts as $part)
    <option value="{{$part->id}}">
        {{$part->name}}
    </option>
@endforeach
