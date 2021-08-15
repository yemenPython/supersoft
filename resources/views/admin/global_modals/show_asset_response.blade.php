<div class="modal-header">
    <p class="text-center text-danger" style="font-size: 18px">{{__('Asset Details')}}  [ {{ $asset->name }}]</p>
</div>
<div class="modal-body">
        <div class="col-xs-12">
            <table class="table static-table-wg">
                <tbody>
                <tr>
                    <th style="width:20% !important">{{__('Asset Group')}}</th>
                    <td> {{optional($asset->group)->name}} </td>
                    <th style="width:20% !important">{{__('Asset Type')}}</th>
                    <td> {{$assetType->name}} </td>
                </tr>
                <tr>
                    <th style="width:20% !important">{{__('Asset name')}}</th>
                    <td>{{ $asset->name }} </td>
                    <th style="width:20% !important">{{__('Status')}}</th>
                    <td>@if($asset->asset_status == 1)
                            {{ __('continues') }}
                        @elseif($asset->asset_status == 2)
                            {{ __('sell') }}
                        @else
                            {{ __('ignore') }}
                        @endif</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="padding:0 20px;">
            <table class="table print-table-wg table-borderless">
                <thead>
                <tr class="spacer" style="border-radius: 30px;">
                    <th>{{__('annual consumtion rate')}}</th>
                    <th>{{__('asset age')}}</th>
                    <th>{{__('purchase date')}}</th>
                    <th>{{__('date of work')}}</th>
                    <th>{{__('purchase cost')}}</th>
                </tr>
                </thead>
                <tbody>
                <tr class="spacer">
                    <td>{{$asset->annual_consumtion_rate}}</td>
                    <td>{{$asset->asset_age}}</td>
                    <td>{{$asset->purchase_date}}</td>
                    <td>{{$asset->date_of_work}}</td>
                    <td>{{$asset->purchase_cost}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="padding:0 20px;">
            <table class="table print-table-wg table-borderless">
                <thead>
                <tr class="spacer" style="border-radius: 30px;">
                    <th>{{__('past consumtion')}}</th>
                    <th>{{__('current consumtion')}}</th>
                    <th>{{__('total replacements')}}</th>
                    <th>{{ __('book value') }} </th>
                    <th>{{ __('total current consumption') }}</th>
                </tr>
                </thead>
                <tbody>
                <tr class="spacer">
                    <td>{{$asset->past_consumtion}}</td>
                    <td>{{$asset->current_consumtion}}</td>
                    <td>{{$asset->total_replacements}}</td>
                    <td>{{$asset->book_value}}</td>
                    <td>{{$asset->total_current_consumtion}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="row right-peice-wg" >
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <h5 class="title">{{__('Notes')}} </h5>
                    <p style="font-size:14px">{!! $asset->asset_details !!}</p>
                </div>
            </div>
        </div>
</div>
<div class="modal-footer text-left">
    <button type="button" class="btn btn-danger waves-effect waves-light"
            data-dismiss="modal"><i class='fa fa-close'></i>
        {{__('Close')}}</button>
</div>
