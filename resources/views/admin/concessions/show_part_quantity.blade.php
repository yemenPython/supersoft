@foreach($part->stores as $store)
    <tr>
        <td scope="col">{{$store->name}}</td>
        <td scope="col">{{$store->pivot ? $store->pivot->quantity : -0 }}</td>
    </tr>
@endforeach
