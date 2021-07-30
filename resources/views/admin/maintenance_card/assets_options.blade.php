<option value="">{{__('Select Asset')}}</option>

@foreach($assets as $asset)
    <option value="{{$asset->id}}">
        {{$asset->name}}
    </option>
@endforeach
