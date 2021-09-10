<div class="col-md-12">
    <div class="table-responsive scroll-table">
    <table class="table table-responsive table-bordered table-hover">
        <thead>
        <tr>
            <th > # </th>
            <th > {{ __('Name') }} </th>
            <th> {{ __('Part Types') }} </th>
            <th > {{ __('Unit Quantity') }} </th>
            <th> {{ __('Unit') }} </th>
            <th > {{ __('Price Segments') }} </th>

            <th > {{ __('Barcode') }} </th>
            <th > {{ __('Supplier Barcode') }} </th>

            <th >
                {{ __('Select') }}
                <input type="checkbox" class="select_all_items form-control" style="margin-right: 16px;"
                       onclick="checkAllItems(); executeAllItems()" {{!isset($purchaseQuotation) ? 'checked':''}}>
            </th>

            <th > {{ __('Action') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">

        @if(isset($purchaseQuotation))

            @foreach ($purchaseQuotation->items as $index => $update_item)
                @php
                    $index +=1;
                    $part = $update_item->part;
                @endphp
                @include('admin.purchase_quotations.part_raw')
            @endforeach
        @endif


        </tbody>
        <tfoot>
        <tr>
            <th > # </th>
            <th > {{ __('Name') }} </th>
            <th> {{ __('Part Types') }} </th>
            <th> {{ __('Unit Quantity') }} </th>
            <th > {{ __('Unit') }} </th>
            <th > {{ __('Price Segments') }} </th>

            <th > {{ __('Barcode') }} </th>
            <th > {{ __('Supplier Barcode') }} </th>


            <th > {{ __('Select') }} </th>
            <th > {{ __('Action') }} </th>
        </tr>
        </tfoot>

        <input type="hidden" name="index" id="items_count" value="{{isset($purchaseQuotation) ? $purchaseQuotation->items->count() : 0}}">
    </table>
</div>
</div>
