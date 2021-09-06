<thead>
    <tr>
        @foreach(\App\Http\Controllers\DataExportCore\Invoices\PurchaseAssets::get_my_view_columns() as $key => $value)
            <th class="text-center column-{{ $key }}"
                onclick="appling_sort(event ,'{{ $key }}')"
                data-sort-method="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : 'desc' }}"
                scope="col">
                {{ $value }}
            </th>
        @endforeach

        <th scope="col">{!! __('Options') !!}</th>
        <th scope="col">
            <div class="checkbox danger">
                <input type="checkbox"  id="select-all">
                <label for="select-all"></label>
            </div>
            {!! __('Select') !!}
        </th>
    </tr>
</thead>
