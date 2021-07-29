<div id="asset_to_print">
<div class="top-logo-print">
    <div class="logo-print text-center">
        <ul class="list-inline" style="margin:0">
            <li>
            <h5>{{optional($branchToPrint)->name_ar}}</h5>
            </li>
            <li>
            <img
            src="{{isset($branchToPrint->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}"
            style="width: 50px;
    height: 50px;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 21px;">
            </li>
        </ul>
    </div>
</div>


<div class="row row-right-data">
    <div class="col-xs-6"></div>
    <div class="col-xs-6 right-top-detail">
        <h3>
        {{ $asset->name }}
</h3>
        
    </div>
</div>


<div class="invoice-to">
    <div clas="row">

      
    </div>

</div>



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
<td>{{$total_replacements}}</td>
<td>{{$asset->book_value}}</td>
<td>{{$asset->total_current_consumtion}}</td>
</tr>

</tbody>
</table>
</div>


<div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">

    <div class="col-xs-12">

<div class="col-xs-6">
    <h5 class="title">{{__('Notes')}}<h5>
    <p style="font-size:14px">
   
    {!! $asset->asset_details !!}
       
    </p>
</div>
</div>


</div>

<div class="print-foot-wg position-relative ml-0" >
        <div class="row" style="display: flex;
    align-items: flex-end;">
            <div class="col-xs-7">
                <div class="row">
                    <div class="col-xs-12">

                        <div class="media">
                            <div class="media-left">
                                <h6 class="media-heading" style="line-height:30px;">{{__('address')}} </h6>
                            </div>

                            <div class="media-body">
                                <h6 style="padding:0 15px">{{optional($branchToPrint)->address_ar}} </h6>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-6">

                    </div>
                    <div class="col-xs-6">

                    </div>
                </div>

            </div>
            <div class="col-xs-5 small-data-wg">
                <div class="row">
                    <div class="col-xs-4">
                        <h6>{{__('contact numbers')}} : </h6>
                    </div>
                    <div class="col-xs-4">
                        <h6>{{optional($branchToPrint)->phone1}}</h6>
                    </div>

                    <div class="col-xs-4">
                        <h6>{{optional($branchToPrint)->phone2}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</div>



