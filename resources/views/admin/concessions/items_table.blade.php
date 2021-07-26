<div class="col-md-12">
    <div class="table-responsive scroll-table">
<table class="table table-responsive table-bordered table-hover">
    <thead>
    <tr>
        <th width="2%"> {{ __('#') }} </th>
        <th width="16%"> {{ __('Name') }} </th>
        <th width="16%"> {{ __('Part Type') }} </th>
        <th width="16%"> {{ __('Unit Quantity') }} </th>
        <th width="12%"> {{ __('Unit') }} </th>
        <th width="12%"> {{ __('Price Segments') }} </th>
        <th> {{ __('quantity') }} </th>
        <th> {{ __('Price') }} </th>
        <th> {{ __('Total') }} </th>
    </tr>
    </thead>
    <tbody id="parts_data">
    @if(isset($concession))
        @foreach ($concession->concessionItems as $key=>$item)
            @include('admin.concessions.edit_row')
        @endforeach
    @endif
    </tbody>
    <tfoot>
    <tr>
        <th width="13%"> {{ __('#') }} </th>
        <th width="5%"> {{ __('Name') }} </th>
        <th width="16%"> {{ __('Part Type') }} </th>
        <th width="16%"> {{ __('Unit Quantity') }} </th>
        <th width="12%"> {{ __('Unit') }} </th>
        <th width="12%"> {{ __('Price Segments') }} </th>
        <th> {{ __('quantity') }} </th>
        <th> {{ __('Price') }} </th>
        <th> {{ __('Total') }} </th>
    </tr>
    </tfoot>
</table>
</div>
</div>
