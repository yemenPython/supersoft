<div class="col-md-12">
    <div class="table-responsive scroll-table">
        <table class="table table-responsive table-bordered table-hover">
            <thead>
            <tr>
                <th width="2%"> # </th>
                <th> {{ __('Name') }} </th>
                <th> {{ __('Part Types') }}</th>
                <th> {{ __('Unit Quantity') }} </th>
                <th> {{ __('Unit') }} </th>
                <th> {{ __('Price Segments') }} </th>
                <th width="8%"> {{ __('Quantity in store') }} </th>
                <th width="7%"> {{ __('Max Quantity return') }} </th>
                <th width="7%"> {{ __('Quantity') }} </th>
                <th width="8%"> {{ __('Price') }} </th>
                <th width="4%"> {{ __('Discount Type') }} </th>
                <th width="5%"> {{ __('Discount') }} </th>
                <th width="8%"> {{ __('Total Before Discount') }} </th>
                <th width="8%"> {{ __('Total After Discount') }} </th>
                <th width="8%"> {{ __('Taxes') }} </th>
                <th width="8%"> {{ __('Total') }} </th>
                <th width="8%">
                    {{ __('Check') }}
                </th>
            </tr>
            </thead>
            <tbody id="parts_data">

            @if(isset($purchaseReturn))

                @foreach ($purchaseReturn->items as $index => $item)
                    @php
                        $index +=1;
                        $part = $item->part;
                    @endphp
                    @include('admin.purchase_returns.info.items')
                @endforeach
            @endif

            </tbody>
            <tfoot>
            <tr>
                <th width="2%"> # </th>
                <th> {{ __('Name') }} </th>
                <th>{{ __('Part Types') }}</th>
                <th> {{ __('Unit Quantity') }} </th>
                <th> {{ __('Unit') }} </th>
                <th> {{ __('Price Segments') }} </th>
                <th width="8%"> {{ __('Quantity in store') }} </th>
                <th width="7%"> {{ __('Max Quantity return') }} </th>
                <th width="7%"> {{ __('Quantity') }} </th>
                <th width="8%"> {{ __('Price') }} </th>
                <th width="4%"> {{ __('Discount Type') }} </th>
                <th width="5%"> {{ __('Discount') }} </th>
                <th width="8%"> {{ __('Total Before Discount') }} </th>
                <th width="8%"> {{ __('Total After Discount') }} </th>
                <th width="8%"> {{ __('Taxes') }} </th>
                <th width="8%"> {{ __('Total') }} </th>
                <th width="8%"> {{ __('Check') }} </th>
            </tr>
            </tfoot>

        </table>
    </div>
</div>