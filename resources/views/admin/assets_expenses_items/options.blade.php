@foreach($items as $item)
    <option value="{{$item->id}}">{{$item->item}}</option>
@endforeach
