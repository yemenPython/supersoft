<div class="col-md-12">
    <div class="table-responsive scroll-table">
        <table class="table table-responsive table-bordered table-hover">
            <thead>
            <tr>
                <th width="2%"> {{ __('#') }} </th>
                <th width="16%"> {{ __('Name') }} </th>
                <th width="16%"> {{ __('Part Type') }} </th>
                <th width="16%"> {{ __('Unit Quantity') }} </th>
                <th width="12%"> {{ __('Unit') }} </th>
                <th width="12%"> {{ __('Price Segments') }} </th>
                <th width="20%"> {{ __('Store') }} </th>
                <th width="25%"> {{ __('Barcode') }} </th>
                <th width="25%"> {{ __('Supplier Barcode') }} </th>
                <th width="25%"> {{ __('Options') }} </th>
            </tr>
            </thead>
            <tbody id="parts_data">
            @if(isset($concession))
                @foreach ($concession->concessionItems as $key=>$item)
                    @include('admin.concessions.edit_row')
                @endforeach
            @endif
            </tbody>

        </table>
    </div>
</div>
