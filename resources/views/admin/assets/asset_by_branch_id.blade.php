<option value="0">{{__('Select Name')}}</option>
@foreach($assets as $asset)
    <option value="{{$asset->id}}">{{$asset->name}}</option>
@endforeach

