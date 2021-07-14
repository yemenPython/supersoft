<div class="col-md-12">
    <div class="table-responsive scroll-table">
    <table class="table table-responsive table-bordered table-hover">
        <thead>
        <tr>
            <th width="2%"> # </th>
        
            <th width="9%"> {{ __('Asset name') }} </th>
            <th scope="col"> {{ __('Asset group') }} </th>
            <th scope="col"> {{ __('work date') }} </th>
            <th scope="col"> {{ __('purchase cost') }} </th>
            <th scope="col"> {{ __('past consumtion') }} </th>
            <th scope="col"> {{ __('replacement cost') }} </th>
            <th scope="col"> {{ __('total consumtion') }} </th>
            <th scope="col"> {{ __('final total consumption') }} </th>
            <th scope="col"> {{ __('Sale amount') }} </th>
            <th width="5%"> {{ __('Action') }} </th>
        </tr>

        </thead>
        <tbody id="items_data">
        @if(isset($saleAsset))

            @foreach ($saleAsset->items as $index => $update_item)
                @php

                    $index +=1;
                    $asset = $update_item->asset;

                @endphp
                @include('admin.sale-assets.row')
            @endforeach
        @endif

        </tbody>
        <tfoot>
        <tr>
            <th width="2%"> # </th>
       
            <th width="9%"> {{ __('Asset name') }} </th>
            <th scope="col"> {{ __('Asset group') }} </th>
            <th scope="col"> {{ __('work date') }} </th>
            <th scope="col"> {{ __('purchase cost') }} </th>
            <th scope="col"> {{ __('past consumtion') }} </th>
            <th scope="col"> {{ __('replacement cost') }} </th>
            <th scope="col"> {{ __('total consumtion') }} </th>
            <th scope="col"> {{ __('final total consumption') }} </th>
            <th scope="col"> {{ __('Sale amount') }} </th>
            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </tfoot>

        <input type="hidden" name="index" id="items_count" value="{{isset($saleAsset) ? $saleAsset->items->count() : 0}}">
    </table>
</div>
</div>
