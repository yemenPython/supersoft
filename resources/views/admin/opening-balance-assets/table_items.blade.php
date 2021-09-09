<div class="col-md-12">
    <div class="table-responsive scroll-table">
    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th width="2%"> # </th>
            <th width="col"> {{ __('Asset name') }} </th>
            <th scope="col"> {{ __('Assets Groups') }} </th>

            <th scope="col"> {{ __('purchase cost') }} </th>
            <th scope="col"> {{ __('opening balance') }} </th>
{{--            <th scope="col"> {{ __('current consumtion') }} </th>--}}
            <th scope="col"> {{ __('purchase date') }} </th>
            <th scope="col"> {{ __('work date') }} </th>
            <th scope="col"> {{ __('consumtion rate') }} </th>
            <th scope="col"> {{ __('asset age') }} </th>
            <th width="5%"> {{ __('Action') }} </th>



        </tr>
        </thead>
        <tbody id="items_data">
        @if(isset($openingBalanceAsset))

            @foreach ($openingBalanceAsset->items as $index => $update_item)
                @php

                    $index +=1;
                    $asset = $update_item->asset;

                @endphp
                @include('admin.opening-balance-assets.row')
            @endforeach
        @endif

        </tbody>
        <tfoot>
        <tr>
            <th width="2%"> # </th>

            <th width="col"> {{ __('Asset name') }} </th>
            <th scope="col"> {{ __('Assets Groups') }} </th>
            <th scope="col"> {{ __('purchase cost') }} </th>
            <th scope="col"> {{ __('opening balance') }} </th>
            <th scope="col"> {{ __('purchase date') }} </th>
            <th scope="col"> {{ __('work date') }} </th>
            <th scope="col"> {{ __('consumtion rate') }} </th>
            <th scope="col"> {{ __('asset age') }} </th>
            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </tfoot>

        <input type="hidden" name="index" id="items_count" value="{{isset($openingBalanceAsset) ? $openingBalanceAsset->items->count() : 0}}">
    </table>
</div>
</div>
