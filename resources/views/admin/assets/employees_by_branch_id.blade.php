<option value="0">{{__('Select Employee Name')}}</option>
@foreach($assetEmployees as $assetEmployee)
    <option value="{{$assetEmployee->id}}">{{$assetEmployee->name}}</option>
@endforeach

