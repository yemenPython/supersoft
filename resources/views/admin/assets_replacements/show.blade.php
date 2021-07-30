<div id="assetDatatoPrint" >
    <div class="border-container" style="border: 1px solid #3b3b3b;">

    <div class="print-header-wg">
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

                    <span> {{__('Assets Replacements')}} </span>

                </h3>

            </div>
        </div>
    </div>


    <div class="middle-data-h-print">

<div class="invoice-to print-padding-top">
    <div clas="row">
        <div class="col-xs-6">
            <h5>{{__('Assets Replacements data')}}</h5>
        </div>
        <div class="col-xs-6" style="padding-right: 50px;">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-time-user">
                        <tr>
                            <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                            <td style="font-weight: normal !important;">{{$assetReplacement->time}}
                                - {{$assetReplacement->date}}</td>
                        </tr>
                        <tr>
                            <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                            <td style="font-weight: normal !important;">{{optional($assetReplacement->user)->name}}</td>
                        </tr>
                    </table>
                    </div>

</div>
</div>
</div>

</div>
</div>


<div class="col-xs-12">

            <table class="table static-table-wg">
                <tbody>
                <tr>
                    <th>{{__('Asset expense Number')}}</th>
                    <td> {{ $assetReplacement->number }} </td>
                    <th>{{__('Asset expense Type')}}</th>
                    <td> {{__($assetReplacement->status)}} </td>
                  </tr>


                </tbody>
            </table>

        </div>


        <div style="padding:0 20px;">
            <h5 class="invoice-to-title">{{__('Asset Replacement items')}}</h5>


            <table class="table print-table-wg table-borderless">
                <thead>

                <tr class="spacer" style="border-radius: 30px;">
                <th> # </th>
                        <th> {{ __('Assets Groups') }} </th>
                        <th> {{ __('Assets') }} </th>
                        <th> {{ __('Operation Date') }} </th>
                        <th> {{ __('Purchase Cost') }} </th>
                        <th> {{ __('Cost Before Replacement') }} </th>
                        <th> {{ __('Cost Replacement') }} </th>
                        <th> {{ __('Cost After Replacement') }} </th>
                        <th> {{ __('Age') }} </th>
                </tr>

                </thead>
                <tbody>

                @if(isset($assetReplacement))
                        @foreach ($assetReplacement->assetReplacementItems as $index => $item)
<tr class="spacer">

<td>
                            {{$index}}
                                </td>

                                <td>
                                   {{optional($item->asset->group)->name}}
                                </td>

                                <td>
                                {{optional($item->asset)->name}}
                                </td>

                                <td>
                                   {{optional($item->asset)->date_of_work}}
                                </td>

                                <td>
                                 {{optional($item->asset)->purchase_cost}}
                                </td>

                                <td>
                                 {{optional($item->asset)->annual_consumtion_rate}}
                                </td>

                                <td>
                                {{$item->value_replacement}}
                                </td>

                                <td>
                                {{$item->value_after_replacement}}
                                </td>

                                <td>
                                {{$item->age}}
                                </td>

</tr>

@endforeach
                    @endif


</tbody>
</table>
</div>



<div class="col-xs-12" style="padding:0 !important">

                <div class="col-xs-12 text-center">


                    <div class="row last-total" style="background-color:#ddd !important">
                        <div class="col-xs-7">
                            <h6>{{__('total cost')}}</h6>
                        </div>
                        <div class="col-xs-5">
                            <h6> {{isset($assetReplacement) ? $assetReplacement->total_after_replacement : 0}} </h6>
                        </div>
                    </div>

                </div>
                </div>


                <div class="col-xs-12" style="padding:0 !important">
                <div class="col-xs-12 text-center" style="padding:5px !important">


                    <div class="row last-total" style="background-color:#ddd !important">

                        <div class="col-xs-12">
                            <h6 data-id="data-totalInLetters" id="totalInLetters"> {{isset($assetReplacement) ? $assetReplacement->total_after_replacement : 0}} </h6>
                            </div>
                    </div>

                </div>


            </div>
      


                
                <div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">
<div class="col-xs-12">

<div class="col-xs-12">
    <h5 class="title">{{__('Notes')}}<h5>
    <p style="font-size:14px">
   
    {!! $assetReplacement->note !!}
       
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


<!-- 

<div class="row small-spacing" id="assetDatatoPrint">


    <h4 class="text-center">{{__('Assets Replacements')}}</h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Number')}}</th>
                        <td>{{$assetReplacement->number }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$assetReplacement->time}} - {{$assetReplacement->date}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{optional($assetReplacement->user)->name}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th width="2%"> # </th>
                        <th width="10%"> {{ __('Assets Groups') }} </th>
                        <th width="10%"> {{ __('Assets') }} </th>
                        <th width="10%"> {{ __('Operation Date') }} </th>
                        <th width="10%"> {{ __('Purchase Cost') }} </th>
                        <th width="10%"> {{ __('Cost Before Replacement') }} </th>
                        <th width="10%"> {{ __('Cost Replacement') }} </th>
                        <th width="10%"> {{ __('Cost After Replacement') }} </th>
                        <th width="12%"> {{ __('Age') }} </th>
                    </tr>
                    </thead>
                    <tbody id="items_data">
                    @if(isset($assetReplacement))
                        @foreach ($assetReplacement->assetReplacementItems as $index => $item)
                            <tr class="text-center-inputs" id="item_{{$index}}">

                                <td>
                                    <span>{{$index}}</span>
                                </td>

                                <td>
                                    <span>{{optional($item->asset->group)->name}}</span>
                                </td>

                                <td class="inline-flex-span">
                                    <span>{{optional($item->asset)->name}}</span>
                                    <input type="hidden" class="assetExist" value="{{optional($item->asset)->id}}" name="items[{{$index}}][asset_id]">
                                </td>

                                <td class="inline-flex-span">
                                    <span>{{optional($item->asset)->date_of_work}}</span>
                                </td>

                                <td class="inline-flex-span">
                                    <span style="background:#D7FDF9 !important">{{optional($item->asset)->purchase_cost}}</span>
                                    <input type="hidden" id="purchase_cost{{$index}}" value="{{optional($item->asset)->purchase_cost}}">
                                </td>

                                <td class="inline-flex-span">
                                    <span>{{optional($item->asset)->annual_consumtion_rate}}</span>
                                    <input type="hidden" class="replacement_before" id="replacement_before{{$index}}"
                                           value="{{optional($item->asset)->annual_consumtion_rate}}">
                                </td>

                                <td class="inline-flex-span">
                                    <input type="number" class="value_replacement"
                                           id="value_replacement{{$index}}"
                                           name="items[{{$index}}][value_replacement]"
                                           onkeyup="addReplacementValue('{{$index}}')"
                                           value="{{$item->value_replacement}}" style="width: 100px">
                                </td>

                                <td class="inline-flex-span">
                                    <input type="number" class="replacement_after"
                                           id="replacement_after{{$index}}"
                                           name="items[{{$index}}][value_after_replacement]"
                                           value="{{$item->value_after_replacement}}" style="width: 100px" >
                                </td>

                                <td class="inline-flex-span">
                                    <input type="text" readonly style="width: 100px" id="age{{$index}}"
                                           name="items[{{$index}}][age]" value="{{$item->age}}">
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
        </div>
    </div>

</div>

<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Purchase')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_before_replacement"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($assetReplacement) ? $assetReplacement->total_before_replacement : 0}}" class="form-control">
        </td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Value Of Replacement')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_after_replacement"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($assetReplacement) ? $assetReplacement->total_after_replacement : 0}}" class="form-control">
        </td>
        </tbody>
    </table>



</div> -->
