<div class="col-md-12">
    <div class="table-responsive scroll-table">
    <table class="table table-responsive table-bordered table-hover">
        <thead>
        <tr>
            <th width="2%"> # </th>
            <th width="9%"> {{ __('Name') }} </th>
            <th width="15%"> {{ __('Part Types') }} </th>
            <th width="15%"> {{ __('Unit Quantity') }} </th>
            <th width="8%"> {{ __('Unit') }} </th>
            <th width="8%"> {{ __('Price Segments') }} </th>

            <th width="5%"> {{ __('Barcode') }} </th>
            <th width="5%"> {{ __('Supplier Barcode') }} </th>
            <th width="1%">
                {{ __('Select') }}
                <input type="checkbox" class="select_all_items form-control" style="margin-right: 16px;"
                       onclick="checkAllItems(); executeAllItems()" {{!isset($saleQuotation) ? 'checked':''}}>
            </th>
            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">

        @if(isset($saleQuotation))

            @foreach ($saleQuotation->items as $index => $item)
                @php
                    $index +=1;
                    $part = $item->part;
                @endphp
                @include('admin.sale_quotations.part_raw')
            @endforeach
        @endif


        </tbody>
{{--        <tfoot>--}}
{{--        <tr>--}}
{{--            <th width="2%"> # </th>--}}
{{--            <th width="9%"> {{ __('Name') }} </th>--}}
{{--            <th width="15%"> {{ __('Part Types') }} </th>--}}
{{--            <th width="10%"> {{ __('Unit Quantity') }} </th>--}}
{{--            <th width="8%"> {{ __('Unit') }} </th>--}}
{{--            <th width="8%"> {{ __('Price Segments') }} </th>--}}
{{--            <th width="7%"> {{ __('Quantity') }} </th>--}}
{{--            <th width="8%"> {{ __('Price') }} </th>--}}

{{--            <th width="4%"> {{ __('Discount Type') }} </th>--}}
{{--            <th width="5%"> {{ __('Discount') }} </th>--}}
{{--            <th width="8%"> {{ __('Total') }} </th>--}}
{{--            <th width="8%"> {{ __('Total After Discount') }} </th>--}}
{{--            <th width="8%"> {{ __('Taxes') }} </th>--}}
{{--            <th width="8%"> {{ __('Total') }} </th>--}}
{{--            <th width="5%"> {{ __('Barcode') }} </th>--}}
{{--            <th width="5%"> {{ __('Supplier Barcode') }} </th>--}}
{{--            <th width="1%"> {{ __('Select') }} </th>--}}
{{--            <th width="5%"> {{ __('Action') }} </th>--}}
{{--        </tr>--}}
{{--        </tfoot>--}}

        <input type="hidden" name="index" id="items_count" value="{{isset($saleQuotation) ? $saleQuotation->items->count() : 0}}">
    </table>
</div>
</div>
