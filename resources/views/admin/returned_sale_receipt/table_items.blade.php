<div class="col-md-12">
<div class="table-responsive scroll-table">
    <table class="table table-responsive table-bordered table-hover">
        <thead>
        <tr>
            <th width="2%"> # </th>
            <th> {{ __('Name') }} </th>
            <th> {{ __('Part Type') }} </th>
            <th width="10%"> {{ __('Unit Quantity') }} </th>
            <th width="8%"> {{ __('Unit') }} </th>

            <th width="7%"> {{ __('Store') }} </th>
            <th width="5%"> {{ __('Barcode') }} </th>
            <th width="5%"> {{ __('Supplier Barcode') }} </th>
            <th width="5%"> {{ __('Options') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">

        @if(isset($returnSaleReceipt))

            @foreach ($returnSaleReceipt->items as $index => $update_item)
                @php
                    $index +=1;
                    $part = $update_item->part;

                @endphp
                @include('admin.returned_sale_receipt.part_raw')
            @endforeach
        @endif

        </tbody>
{{--        <tfoot>--}}
{{--        <tr>--}}
{{--            <th width="2%"> # </th>--}}
{{--            <th> {{ __('Name') }} </th>--}}
{{--            <th> {{ __('Part Type') }} </th>--}}
{{--            <th width="10%"> {{ __('Unit Quantity') }} </th>--}}
{{--            <th width="8%"> {{ __('Unit') }} </th>--}}
{{--            <th> {{ __('Price') }} </th>--}}
{{--            <th width="7%"> {{ __('Total Quantity') }} </th>--}}
{{--            <th width="7%"> {{ __('Last Accepted Quantity') }} </th>--}}
{{--            <th width="7%"> {{ __('Remaining Quantity') }} </th>--}}
{{--            <th width="7%"> {{ __('Refused Quantity') }} </th>--}}
{{--            <th width="7%"> {{ __('Accepted Quantity') }} </th>--}}
{{--            <th width="7%"> {{ __('Defect Percent') }} </th>--}}
{{--            <th width="7%"> {{ __('Store') }} </th>--}}
{{--            <th width="5%"> {{ __('Barcode') }} </th>--}}
{{--            <th width="5%"> {{ __('Supplier Barcode') }} </th>--}}
{{--        </tr>--}}
{{--        </tfoot>--}}

        <input type="hidden" name="index" id="items_count" value="{{isset($returnSaleReceipt) ? $returnSaleReceipt->items->count() : 0}}">
    </table>
</div>
</div>
