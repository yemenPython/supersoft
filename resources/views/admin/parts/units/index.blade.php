<table id="cities" class="table table-bordered wg-table-print table-hover" style="width:100%">
    <thead>
    <tr>
        <th scope="col">{!! __('#') !!}</th>
        <th scope="col">{!! __('Name') !!}</th>
        <th scope="col">{!! __('Quantity') !!}</th>
        <th scope="col">{!! __('Selling Price') !!}</th>
        <th scope="col">{!! __('Purchase Price') !!}</th>
        <th scope="col">{!! __('Options') !!}</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th scope="col">{!! __('#') !!}</th>
        <th scope="col">{!! __('Name') !!}</th>
        <th scope="col">{!! __('Quantity') !!}</th>
        <th scope="col">{!! __('Selling Price') !!}</th>
        <th scope="col">{!! __('Purchase Price') !!}</th>
        <th scope="col">{!! __('Options') !!}</th>
    </tr>
    </tfoot>
    <tbody>

    @if(isset($prices))

        @foreach($prices as $index => $price)
            <tr id="unit_tr_{{$index}}">
                <td>{!! $index +1 !!}</td>
                <td>{!! $price->unit ? $price->unit->unit : '---' !!}</td>
                <td class="text-danger">{!! $price->quantity !!}</td>
                <td class="text-danger">{!! $price->selling_price !!}</td>
                <td class="text-danger">{!! $price->purchase_price !!}</td>
                <td>

                    <div class="btn-group margin-top-10">
                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <i class="ico fa fa-bars"></i>
                            {{__('Options')}} <span class="caret"></span>
                        </button>

                        <ul class="dropdown-menu dropdown-wg">

                            <li>
                                <button type="button" class="btn btn-wg-edit hvr-radial-out"
                                        onclick="editUnit('{{$price->id}}')">
                                    <i class="fa fa-pencil"></i> {{__('Edit')}}
                                </button>
                            </li>

                            @if($index != 0)
                                <li class="btn-style-drop">
                                    <button type="button" class="btn btn-wg-delete hvr-radial-out"
                                            onclick="deleteUnit('{{$price->id}}', '{{$index}}')">
                                        <i class="fa fa-trash"></i> {{__('Delete')}}
                                    </button>
                                </li>
                            @endif

                        </ul>
                    </div>
                </td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

