<div id="asset_to_print">
    <div class="border-container" style="">

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

                        <span> {{__('Sale assets')}} </span>

                    </h3>

                </div>
            </div>
        </div>


        <div class="middle-data-h-print">

            <div class="invoice-to print-padding-top">
                <div clas="row">
                    <div class="col-xs-6">
                        <h5>{{__('Sale assets data')}}</h5>
                    </div>
                    <div class="col-xs-6" style="padding-right: 50px;">
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-time-user">
                                    <tr>
                                        <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                                        <td style="font-weight: normal !important;">{{$asset->time}}
                                            - {{$asset->date}}</td>
                                    </tr>
                                    <tr>
                                        <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                                        <td style="font-weight: normal !important;">{{optional($asset->user)->name}}</td>
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
                    <th>{{__('Sale assets Number')}}</th>
                    <td>  {{ $asset->number }} </td>
                    <th>{{__('Sale assets Type')}}</th>
                    <td> {{$asset->type === 'sale' ?  __('Sale') : __('exclusion')}} </td>
                </tr>


                </tbody>
            </table>

        </div>


        <hr>

        <h4 class="text-center">

            {{ $asset->umber }}

        </h4>

        <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
            <div class="row">


                <div style="padding:0 20px;">
                    <h5 class="invoice-to-title">{{__('Sale assets items')}}</h5>


                    <table class="table print-table-wg table-borderless">
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th> {{ __('Asset Group') }} </th>
                            <th> {{ __('Asset name') }} </th>
                            <th> {{ __('Sale amount') }} </th>
                        </tr>

                        </thead>
                        <tbody>
                        @if($asset->items->isNotEmpty())
                            @foreach($asset->items as $item)
                                <tr class="spacer">


                                    <td>
                                        {{optional($item->asset->group)->name}}
                                    </td>

                                    <td>
                                        {{optional($item->asset)->name}}
                                    </td>

                                    <td>
                                        {{$item->sale_amount}}
                                    </td>


                                </tr>

                            @endforeach
                        @endif


                        </tbody>
                    </table>
                </div>


                <div class="col-xs-12" style="padding:0 !important">

                    <div class="col-xs-4 text-center" style="padding:5px !important">


                        <div class="row last-total">
                            <div class="col-xs-7">
                                <h6>{{__('total purchase cost')}}</h6>
                            </div>
                            <div class="col-xs-5">
                                <h6> {{$asset->total_purchase_cost}} </h6>
                            </div>
                        </div>

                    </div>

                    <div class="col-xs-4 text-center" style="padding:5px !important">


                        <div class="row last-total">
                            <div class="col-xs-7">
                                <h6>{{__('total past consumtion')}}</h6>
                            </div>
                            <div class="col-xs-5">
                                <h6> {{$asset->total_past_consumtion}} </h6>
                            </div>
                        </div>

                    </div>


                    <div class="col-xs-4 text-center" style="padding:5px !important">


                        <div class="row last-total">
                            <div class="col-xs-7">
                                <h6>{{__('total replacement')}}</h6>
                            </div>
                            <div class="col-xs-5">
                                <h6> {{$asset->total_replacement}} </h6>
                            </div>
                        </div>

                    </div>

                </div>


                <div class="col-xs-12" style="padding:0 !important">

                    <div class="col-xs-4 text-center" style="padding:5px !important">


                        <div class="row last-total">
                            <div class="col-xs-7">
                                <h6>{{__('total consumtion')}}</h6>
                            </div>
                            <div class="col-xs-5">
                                <h6>{{$asset->total_current_consumtion}}</h6>
                            </div>
                        </div>

                    </div>

                    <div class="col-xs-4 text-center" style="padding:5px !important">


                        <div class="row last-total">
                            <div class="col-xs-7">
                                <h6>{{__('final total consumtion')}}</h6>
                            </div>
                            <div class="col-xs-5">
                                <h6> {{$asset->final_total_consumtion}}</h6>
                            </div>
                        </div>

                    </div>


                    <div class="col-xs-4 text-center" style="padding:5px !important">


                        <div class="row last-total">
                            <div class="col-xs-7">
                                <h6>{{__('total sale amount')}}</h6>
                            </div>
                            <div class="col-xs-5">
                                <h6> {{$asset->total_sale_amount}} </h6>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xs-12" style="padding:0 !important">


<div class="col-xs-12 text-center">


<div class="row last-total" style="background-color:#ddd !important">
     
<h6 id="totalInLetters"> {{$asset->total_sale_amount}} </h6>

       
    </div>

</div>


</div>

                <div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">
                    <div class="col-xs-12">
                        <div class="col-xs-12">
                            <h5 class="title">{{__('Notes')}}</h5>
                            <p style="font-size:14px">
                                {!! $asset->note !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="print-foot-wg position-relative ml-0">
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



