<option value="0"> {{ __('Invoice Number') }} </option>
@foreach($numbers as $number)
    <option value="{{$number}}"> {{$number}}</option>
@endforeach

