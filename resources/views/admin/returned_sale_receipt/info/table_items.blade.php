<div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">
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
                    <th> {{ __('Price') }} </th>
                    <th width="7%"> {{ __('Total Quantity') }} </th>
                    <th width="7%"> {{ __('Last Accepted Quantity') }} </th>
                    <th width="7%"> {{ __('Remaining Quantity') }} </th>
                    <th width="7%"> {{ __('Refused Quantity') }} </th>
                    <th width="7%"> {{ __('Accepted Quantity') }} </th>
                    <th width="7%"> {{ __('Defect Percent') }} </th>
                    <th width="7%"> {{ __('Store') }} </th>
                </tr>
                </thead>
                <tbody id="parts_data">

                @if(isset($returnSaleReceipt))

                    @foreach ($returnSaleReceipt->items as $index => $update_item)
                        @php
                            $index +=1;
                            $part = $update_item->part;
                        @endphp
                        @include('admin.returned_sale_receipt.info.items')
                    @endforeach
                @endif


                </tbody>
                <tfoot>
                <tr>
                    <th width="2%"> # </th>
                    <th> {{ __('Name') }} </th>
                    <th> {{ __('Part Type') }} </th>
                    <th width="10%"> {{ __('Unit Quantity') }} </th>
                    <th width="8%"> {{ __('Unit') }} </th>
                    <th> {{ __('Price') }} </th>
                    <th width="7%"> {{ __('Total Quantity') }} </th>
                    <th width="7%"> {{ __('Last Accepted Quantity') }} </th>
                    <th width="7%"> {{ __('Remaining Quantity') }} </th>
                    <th width="7%"> {{ __('Refused Quantity') }} </th>
                    <th width="7%"> {{ __('Accepted Quantity') }} </th>
                    <th width="7%"> {{ __('Defect Percent') }} </th>
                    <th width="7%"> {{ __('Store') }} </th>
                </tr>
                </tfoot>

                <input type="hidden" name="index" id="items_count" value="{{isset($returnSaleReceipt) ? $returnSaleReceipt->items->count() : 0}}">
            </table>
        </div>
    </div>
</div>
