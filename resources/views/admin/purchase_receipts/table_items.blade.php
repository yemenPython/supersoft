<div class="col-md-12">
<div class="table-responsive scroll-table">
    <table class="table table-responsive table-bordered table-hover">
        <thead>
        <tr>
            <th width="2%"> # </th>
            <th> {{ __('Name') }} </th>
            <th> {{ __('Part Type') }} </th>
            <th > {{ __('Unit Quantity') }} </th>
            <th > {{ __('Unit') }} </th>
            <th > {{ __('Store') }} </th>
            <th > {{ __('Barcode') }} </th>
            <th > {{ __('Supplier Barcode') }} </th>
            <th > {{ __('Options') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">

        @if(isset($purchaseReceipt))

            @foreach ($purchaseReceipt->items as $index => $update_item)
                @php
                    $index +=1;
                    $part = $update_item->part;
                @endphp
                @include('admin.purchase_receipts.part_raw')
            @endforeach
        @endif

        </tbody>

        <input type="hidden" name="index" id="items_count" value="{{isset($purchaseReceipt) ? $purchaseReceipt->items->count() : 0}}">
    </table>
</div>
</div>
