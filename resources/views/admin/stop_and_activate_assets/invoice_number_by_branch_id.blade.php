<option value="0"> {{ __('Number') }} </option>
@foreach($numbers as $number)
    <option value="{{$number}}"> {{$number}}</option>
@endforeach

