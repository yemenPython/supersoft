<div id="assetDatatoPrint">
    <div class="border-container" style="">
        @foreach($item->items()->get()->chunk(18) as $one)
            <div class="print-header-wg">
                <div class="top-logo-print">
                    <div class="logo-print text-center">
                        <ul class="list-inline" style="margin:0">
                            <li>
                                <h5>{{optional($branchToPrint)->name_ar}}</h5>
                            </li>
                            <li>
                                <img src="{{isset($branchToPrint->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}"
                                    style="width: 50px; height: 50px;border-radius: 50%; position: absolute; top: 2px;left: 21px;">
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="row row-right-data" @if( !$loop->first)style="visibility: hidden !important;"
                 @else style="margin-bottom: -40px;" @endif>
                <div class="col-xs-6"></div>
                <div class="col-xs-6 right-top-detail" @if( !$loop->first)style="visibility: hidden !important;" @endif>
                    <h3>
                        @if( $loop->first)
                            <span> {{__('Bank Accounts Opening Balance')}} </span>
                        @endif
                    </h3>

                </div>
            </div>

            @if( $loop->first)
                <div class="middle-data-h-print">
                    <div class="invoice-to print-padding-top"
                         @if($item->items->count() <= 18) style="margin-bottom: -70px;" @endif>
                        <div class="row">
                            <div class="col-xs-6">
                                <h5>{{__('Bank Accounts Opening Balance')}}</h5>
                            </div>
                            <div class="col-xs-6" style="padding-right: 50px;">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <table class="table table-time-user">
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                                                <td style="font-weight: normal !important;">{{$item->time}}
                                                    - {{$item->date}}</td>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                                                <td style="font-weight: normal !important;">{{optional($item->user)->name}}</td>
                                            </tr>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xs-12 table-responsive"
                     @if($item->items->count() <= 18) style="margin-bottom: -10px;" @endif>

                    <table class="table static-table-wg">
                        <tbody>
                        <tr>
                            <th>{{__('Number')}}</th>
                            <td> {{ $item->number }} </td>
                            <th>{{__('Asset expense Type')}}</th>
                            <td> {{__($item->status)}} </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            @endif

            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('Locker Opening Balance items')}}</h5>
                <div class="table-responsive" @if($item->items->count() <= 18) style="margin-bottom: -20px;" @endif>
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>
                        <tr class="spacer" style="border-radius: 30px;">
                            <th> #</th>
                            <th> {{ __('Bank Account') }} </th>
                            <th> {{ __('Total') }} </th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach ($one as $index => $account)
                            <tr class="spacer">
                                <td>
                                    {{$index+1}}
                                </td>
                                <td>
                                    {{ optional($account->bankAccount->mainType)->name }}
                                    @if ($account->bankAccount->subType)
                                        <strong class="text-danger">[   {{ optional($account->bankAccount->subType)->name }}  ]</strong>
                                    @endif
                                    @if ($account->bankAccount)
                                        <strong class="text-danger">[   {{ optional($account->bankAccount->bankData)->name }}  ]</strong>
                                    @endif
                                </td>

                                <td>
                                    {{$account->total}}
                                </td>
                            </tr>

                        @endforeach


                        </tbody>
                    </table>

                </div>
            </div>



            <div class="print-foot-wg position-relative ml-0">
                <div class="row for-reverse-en" style="display: flex;align-items: flex-end;">
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



