<option value="">{{__('Select')}}</option>
@foreach($maintenanceDetections as $maintenance)
    <option value="{{ $maintenance->id }}">{{ $maintenance->name }}</option>
@endforeach
