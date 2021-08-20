
<div id="concession_to_print" >
    <div class="border-container" style="">
        @foreach($openingBalance->items()->get()->chunk(15) as $one)


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


            </div>

            @if( $loop->first)
                <div class="row row-right-data" @if( !$loop->first)style="visibility: hidden !important;" @endif>
                    <div class="col-xs-6"></div>
                    <div class="col-xs-6 right-top-detail"  @if( !$loop->first)style="visibility: hidden !important;" @endif>
                        <h3>
                            @if( $loop->first)
                                <span> {{__('words.stores-transfers')}} </span>
                            @endif
                        </h3>

                    </div>
                </div>
            @endif

            @if( $loop->first)
                <div class="middle-data-h-print">

                    <div class="invoice-to print-padding-top">
                        <div clas="row">
                            <div class="col-xs-6">
                                <h5>{{__('stores transfers data')}}</h5>
                            </div>
                            <div class="col-xs-6" style="padding-right: 50px;">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <table class="table table-time-user">
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                                                <td style="font-weight: normal !important;">{{$openingBalance->time}}
                                                    - {{$openingBalance->transfer_date}}</td>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                                                <td style="font-weight: normal !important;">{{optional($openingBalance->user)->name}}</td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 table-responsive">

                    <table class="table static-table-wg">
                        <tbody>
                        <tr>
                            <th style="width: 51% !important;">{{ __('opening-balance.serial-number') }}</th>
                            <td> {{old('number', isset($openingBalance)? $openingBalance->number :'')}} </td>
                        </tr>

                        </tbody>
                    </table>


                </div>
            @endif

            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('stores transfers items')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless" @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th width="2%"> #</th>
                            <th width="16%"> {{ __('Name') }} </th>
                            <th width="12%"> {{ __('Part Type') }} </th>
                            <th width="12%"> {{ __('Unit Quantity') }} </th>
                            <th width="12%"> {{ __('Unit') }} </th>
                            <th width="13%"> {{ __('Price Segments') }} </th>
                            <th> {{ __('Quantity') }} </th>
                            <th> {{ __('Price') }} </th>
                            <th> {{ __('Total') }} </th>
                        </tr>

                        </thead>
                        <tbody>
                        @if(isset($openingBalance))

                            @foreach ($one as $index => $item)
                                @php
                                    $index +=1;
                                    $part = $item->part;
                                @endphp
                                <tr class="spacer">
                                    <td>
                                        {{$index}}
                                    </td>

                                    <td>
                                        {{$part->name}}

                                    </td>
                                    <td>
                                        {{$item->sparePart ? $item->sparePart->type :__('Not determined')}}
                                    </td>

                                    <td>
                                        <span> {{optional($item->partPrice)->quantity}}  </span>
                                        <span> {{ $part->sparePartsUnit->unit }} </span>

                                    </td>

                                    <td>
                                        {{optional($item->partPrice->unit)->unit}}
                                    </td>

                                    <td>
                        <span id="price_segments_part_{{$index}}">
                                    {{ $item->partPriceSegment ? $item->partPriceSegment->name : __('Not determined')}}
                        </span>
                                    </td>

                                    <td>
                                        {{isset($item) ? $item->quantity : 0}}
                                    </td>

                                    <td>
                                        {{isset($item) ? $item->price : 0}}
                                    </td>

                                    <td>
                                        {{isset($item) ? ($item->price * $item->quantity) : 0}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif


                        </tbody>
                    </table>

                </div>
            </div>

            @if( $loop->last)
                <div class="row right-peice-wg" style="padding:0 30px 50px 30px;">

                    <div class="col-xs-12" style="padding:0px !important">
                        <div class="col-xs-6 text-center" style="padding:5px !important">
                            <div class="row last-total">
                                <div class="col-xs-6">
                                    <h6>{{__('Quantity')}}</h6>
                                </div>
                                <div class="col-xs-6">
                                    <h6>{{isset($openingBalance) ? $openingBalance->items->sum('quantity') : 0}}</h6>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-6 text-center" style="padding:5px !important">
                            <div class="row last-total" style="background-color:#ddd !important">
                                <div class="col-xs-6">
                                    <h6>{{__('Final Total')}}</h6>
                                </div>
                                <div class="col-xs-6">
                                    <h6>{{isset($openingBalance) ? $openingBalance->total_money : 0}}</h6>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xs-12" style="padding:0 !important">
                        <div class="col-xs-12 text-center" style="padding:5px !important">


                            <div class="row last-total" style="background-color:#ddd !important">

                                <div class="col-xs-12">
                                    <h6 data-id="data-totalInLetters" id="totalInLetters"> {{$openingBalance->total_money}} </h6>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">
                        <div class="col-xs-7">
                            <h5 class="title">{{__('Notes')}}</h5>
                            <p style="width: 80%;font-size:12px">
                                {{old('description', isset($openingBalance)? $openingBalance->description :'')}}

                            </p>
                        </div>

                    </div>

                </div>
            @endif

            <div class="print-foot-wg position-relative ml-0" >
                <div class="row for-reverse-en" style="display: flex;
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
            @if(!$loop->last)
                <p style="page-break-before: always;">&nbsp;</p>
            @endif
        @endforeach
    </div>
</div>


