<div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">
    <div class="col-md-12">
        <div class="table-responsive scroll-table">
            <table class="table table-responsive table-bordered table-hover">
                <thead>
                <tr>
                    <th width="2%"> #</th>
                    <th width="9%"> {{ __('Name') }} </th>
                    <th> {{ __('Part Types') }} </th>
                    <th width="15%"> {{ __('Unit Quantity') }} </th>
                    <th> {{ __('Unit') }} </th>
                    <th width="8%"> {{ __('Price Segments') }} </th>
                    <th width="7%"> {{ __('Quantity') }} </th>
                    <th width="8%"> {{ __('Price') }} </th>
                    <th width="4%"> {{ __('Discount Type') }} </th>
                    <th width="5%"> {{ __('Discount') }} </th>
                    <th width="8%"> {{ __('Total Before Discount') }} </th>
                    <th width="8%"> {{ __('Total After Discount') }} </th>
                    <th width="8%"> {{ __('Taxes') }} </th>
                    <th width="8%"> {{ __('Total') }} </th>
                    <th width="5%"> {{ __('Barcode') }} </th>
                    <th width="5%"> {{ __('Supplier Barcode') }} </th>
                    <th width="1%">{{ __('Select') }}</th>
                </tr>
                </thead>
                <tbody id="parts_data">

                @if(isset($purchaseQuotation))

                    @foreach ($purchaseQuotation->items as $index => $update_item)
                        @php
                            $index +=1;
                            $part = $update_item->part;
                        @endphp
                        @include('admin.purchase_quotations.info.items')
                    @endforeach
                @endif


                </tbody>
                <tfoot>
                <tr>
                    <th width="2%"> #</th>
                    <th width="9%"> {{ __('Name') }} </th>
                    <th> {{ __('Part Types') }} </th>
                    <th> {{ __('Unit Quantity') }} </th>
                    <th width="12%"> {{ __('Unit') }} </th>
                    <th width="8%"> {{ __('Price Segments') }} </th>
                    <th width="7%"> {{ __('Quantity') }} </th>
                    <th width="8%"> {{ __('Price') }} </th>

                    <th width="4%"> {{ __('Discount Type') }} </th>
                    <th width="5%"> {{ __('Discount') }} </th>
                    <th width="8%"> {{ __('Total') }} </th>
                    <th width="8%"> {{ __('Total After Discount') }} </th>
                    <th width="8%"> {{ __('Taxes') }} </th>
                    <th width="8%"> {{ __('Total') }} </th>
                    <th width="5%"> {{ __('Barcode') }} </th>
                    <th width="5%"> {{ __('Supplier Barcode') }} </th>
                    <th width="1%"> {{ __('Select') }} </th>
{{--                    <th width="5%"> {{ __('Action') }} </th>--}}
                </tr>
                </tfoot>

            </table>
        </div>
    </div>
</div>
