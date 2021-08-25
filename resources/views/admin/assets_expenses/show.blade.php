<div id="assetDatatoPrint">
    <div class="border-container" style="">
        @foreach($assetExpense->assetExpensesItems()->get()->chunk(18) as $one)


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

            <div class="row row-right-data" @if( !$loop->first)style="visibility: hidden !important;" @else style="margin-bottom: -40px;" @endif>
                <div class="col-xs-6"></div>
                <div class="col-xs-6 right-top-detail" @if( !$loop->first)style="visibility: hidden !important;" @endif>
                    <h3>
                        @if( $loop->first)
                            <span> {{__('Asset Expenses invoice')}} </span>
                        @endif
                    </h3>

                </div>
            </div>

            @if( $loop->first)
                <div class="middle-data-h-print">

                    <div class="invoice-to print-padding-top" @if($assetExpense->assetExpensesItems->count() <= 18) style="margin-bottom: -70px;" @endif>
                        <div class="row">
                            <div class="col-xs-6">
                                <h5>{{__('Asset Expenses invoice data')}}</h5>
                            </div>
                            <div class="col-xs-6" style="padding-right: 50px;">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <table class="table table-time-user">
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                                                <td style="font-weight: normal !important;">{{$assetExpense->time}}
                                                    - {{$assetExpense->date}}</td>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                                                <td style="font-weight: normal !important;">{{optional($assetExpense->user)->name}}</td>
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
                            <th>{{__('Asset expense Number')}}</th>
                            <td> {{ $assetExpense->number }} </td>
                            <th>{{__('Asset expense Type')}}</th>
                            <td> {{__($assetExpense->status)}} </td>
                        </tr>


                        </tbody>
                    </table>

                </div>
            @endif

            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('Asset Purchase Invoice items')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th>{{__('#')}}</th>
                            <th>{{__('Asset Group')}}</th>
                            <th>{{__('Asset name')}}</th>
                            <th>{{__('Expenses Types')}}</th>
                            <th>{{__('Expenses Items')}}</th>
                            <th>{{__('Expense Cost')}}</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($one as $index=>$assetExpensesItem)
                            <tr class="spacer">

                                <td>{{$index + 1}}</td>
                                <td>{{optional($assetExpensesItem->asset->group)->name}}</td>
                                <td>{{optional($assetExpensesItem->asset)->name}}</td>
                                <td>{{optional($assetExpensesItem->assetExpenseItem->assetsTypeExpense)->name}}</td>
                                <td>{{optional($assetExpensesItem->assetExpenseItem)->item}}</td>
                                <td>{{$assetExpensesItem->price}}</td>

                            </tr>

                        @endforeach


                        </tbody>
                    </table>

                </div>
            </div>

            @if( $loop->last)
                <div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">

                    <div class="col-xs-12" style="padding:0 !important">

                        <div class="col-xs-12 text-center">


                            <div class="row last-total" style="background-color:#ddd !important">
                                <div class="col-xs-7">
                                    <h6>{{__('total cost')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{isset($assetExpense) ? $assetExpense->total : 0}} </h6>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-xs-12" style="padding:0 !important">
                        <div class="col-xs-12 text-center">


                            <div class="row last-total" style="background-color:#ddd !important">

                                <div class="col-xs-12">
                                    <h6 data-id="data-totalInLetters" id="totalInLetters"> {{isset($assetExpense) ? $assetExpense->total : 0}} </h6>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="col-xs-12">
                        <h5 class="title">{{__('Notes')}}</h5>
                                <p style="font-size:14px">

                                    {!! $assetExpense->notes !!}

                                </p>
                    </div>
                </div>

            @endif


            <div class="print-foot-wg position-relative ml-0">
                <div class="row for-reverse-en" style="display: flex;
    align-items: flex-end;">
                    <div class="col-xs-7">
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="media">
                                    <div class="media-left">
                                        <h6 class="media-heading"
                                            style="line-height:30px;">{{__('address')}} </h6>
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



