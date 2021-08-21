@foreach($part->stores as $store)

    <tr>
        <td>
            <span>{{$store->name}}</span>
        </td>
        <td>
            <span>{{optional($store->pivot)->quantity}}</span>
        </td>
    </tr>

@endforeach
