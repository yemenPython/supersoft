<table id="sale_supply_table" class="table table-bordered" style="width:100%">
    <thead>
    <tr>
        <th scope="col">{!! __('Store name.') !!}</th>
        <th scope="col">{!! __('Quantity') !!}</th>
    </tr>
    </thead>

    <tbody id="part_quantity">

        @foreach($part->stores as $store)
            <tr>
                <td >{{$store->name}}</td>
                <td >{{$store->pivot ? $store->pivot->quantity : 0 }}</td>
            </tr>
        @endforeach


    </tbody>

</table>
