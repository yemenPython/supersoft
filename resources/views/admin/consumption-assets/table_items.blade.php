<div class="col-md-12">
    <div class="table-responsive">
    <table class="table table-responsive table-bordered table-striped">
        <thead>
        <tr>
            <th width="2%"> # </th>
            <th scope="col"> {{ __('Assets Groups') }} </th>
            <th width="9%"> {{ __('Name') }} </th>
            <th scope="col"> {{ __('work date') }} </th>
            <th scope="col"> {{ __('purchase cost') }} </th>
            <th scope="col"> {{ __('past consumtion') }} </th>
            <th scope="col"> {{ __('net purchase cost') }} </th>
            <th scope="col"> {{ __('consumtion rate') }} </th>
            <th scope="col"> {{ __('consumption amount') }} </th>
            <th width="5%"> {{ __('Action') }} </th>



        </tr>
        </thead>
        <tbody id="items_data">
        @if(isset($consumptionAsset))

            @foreach ($consumptionAsset->items as $index => $update_item)
                @php

                    $index +=1;
                    $asset = $update_item->asset;

                @endphp
                @include('admin.consumption-assets.row')
            @endforeach
        @endif

        </tbody>
        <tfoot>
        <tr>
            <th width="2%"> # </th>
            <th scope="col"> {{ __('Assets Groups') }} </th>
            <th width="9%"> {{ __('Name') }} </th>
            <th scope="col"> {{ __('work date') }} </th>
            <th scope="col"> {{ __('purchase cost') }} </th>
            <th scope="col"> {{ __('past consumtion') }} </th>
            <th scope="col"> {{ __('net purchase cost') }} </th>
            <th scope="col"> {{ __('consumtion rate') }} </th>
            <th scope="col"> {{ __('consumption amount') }} </th>
            <th width="5%"> {{ __('Action') }} </th>
        </tr>
        </tfoot>

        <input type="hidden" name="index" id="items_count" value="{{isset($consumptionAsset) ? $consumptionAsset->items->count() : 0}}">
    </table>
</div>
</div>
