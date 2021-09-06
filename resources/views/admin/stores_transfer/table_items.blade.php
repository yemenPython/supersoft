<div class="col-md-12">
    <div class="table-responsive scroll-table">
    <table class="table table-responsive table-bordered table-hover">
        <thead>
        <tr>
            <th > # </th>
            <th > {{ __('Name') }} </th>
            <th > {{ __('Part Type') }} </th>
            <th > {{ __('Unit Quantity') }} </th>
            <th > {{ __('Unit') }} </th>
            <th > {{ __('Price Segments') }} </th>
            <th > {{ __('Barcode') }} </th>
            <th > {{ __('Supplier Barcode') }} </th>
            <th > {{ __('Action') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">
            @if(isset($storeTransfer))

                @foreach ($storeTransfer->items as $index => $item)
                    @php
                        $max = 0;
                        $index +=1;
                        $part = $item->part;

                         $store = $part->stores()->where('store_id', $storeTransfer->store_from_id)->first();

                        if ($store) {
                            $max += $store->pivot->quantity;
                        }

                    @endphp
                    @include('admin.stores_transfer.part_raw')
                @endforeach
            @endif
        </tbody>
{{--        <tfoot>--}}
{{--        <tr>--}}
{{--            <th > # </th>--}}
{{--            <th > {{ __('Name') }} </th>--}}
{{--            <th > {{ __('Part Type') }} </th>--}}
{{--            <th > {{ __('Unit Quantity') }} </th>--}}
{{--            <th > {{ __('Unit') }} </th>--}}
{{--            <th > {{ __('Price Segments') }} </th>--}}
{{--            <th > {{ __('Barcode') }} </th>--}}
{{--            <th > {{ __('Supplier Barcode') }} </th>--}}
{{--            <th > {{ __('Action') }} </th>--}}
{{--        </tr>--}}
{{--        </tfoot>--}}

        <input type="hidden" name="index" id="items_count" value="{{isset($storeTransfer) ? $storeTransfer->items->count() : 0}}">
    </table>
    </div>
</div>
