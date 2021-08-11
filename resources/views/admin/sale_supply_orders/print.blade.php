<div id="concession_to_print">
    <div class="border-container" style="">
        @foreach($saleSupplyOrder->items()->where('active', 1)->get()->chunk(15) as $one)


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

            <div class="row row-right-data" @if( !$loop->first)style="visibility: hidden !important;" @endif>
                <div class="col-xs-6"></div>
                <div class="col-xs-6 right-top-detail" @if( !$loop->first)style="visibility: hidden !important;" @endif>
                    <h3>
                        @if( $loop->first)
                            {{__('Sale Supply Order')}}
                        @endif
                    </h3>

                </div>
            </div>

            @if( $loop->first)
                <div class="middle-data-h-print">

                    <div class="invoice-to print-padding-top">
                        <div class="row">
                            <div class="col-xs-6">
                                <h5>{{__('Sale Supply Order data')}}</h5>
                            </div>
                            <div class="col-xs-6" style="padding-right: 50px;">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <table class="table table-time-user">
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                                                <td style="font-weight: normal !important;">{{$saleSupplyOrder->time}}
                                                    - {{$saleSupplyOrder->date}}</td>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                                                <td style="font-weight: normal !important;">{{optional($saleSupplyOrder->user)->name}}</td>
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
                            <th>{{__('supply order Number')}}</th>
                            <td> {{$saleSupplyOrder->number }} </td>

                            <th>{{__('Type')}}</th>
                            <td> {{__($saleSupplyOrder->type)}} </td>

                            <th>{{__('Status')}}</th>
                            <td> {{__($saleSupplyOrder->status)}} </td>
                        </tr>

                        <tr>
                            <th>{{__('Period of supply order from')}}</th>
                            <td> {{__($saleSupplyOrder->supply_date_from)}} </td>
                            <th>{{__('Period of supply order to')}}</th>
                            <td> {{__($saleSupplyOrder->supply_date_to )}}</td>
                            <th>{{__('supply order days')}}</th>
                            <td>{{__($saleSupplyOrder->different_days)}} </td>
                        </tr>

                        <tr>
                            <th>{{__('Customer name')}}</th>
                            <td colspan="6">{{ $saleSupplyOrder->customer ? $saleSupplyOrder->customer->name : '---'}} </td>
                        </tr>
                        <tr>
                            @if($saleSupplyOrder->type == 'from_sale_quotation')
                                <th>{{__('Sale Quotations Number')}}</th>
                                <td colspan="6">
                                    @foreach($saleSupplyOrder->saleQuotations as $index=>$saleQuotation)

                                        <span>{{$saleQuotation->number}} ,</span>

                                    @endforeach

                                </td>
                            @endif
                        </tr>

                        </tbody>
                    </table>

                </div>
            @endif

            <div style="padding:0 20px;">
                <h5>{{__('Sale Supply Order items')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th>{{__('#')}}</th>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Unit')}}</th>
                            <th>{{__('Quantity')}}</th>
                            <th>{{__('Price')}}</th>
                            <th>{{__('Total')}}</th>
                            <th>{{__('Discount Type')}}</th>
                            <th>{{__('Discount value')}}</th>
                            <th>{{__('Total After Discount')}}</th>
                            <th>{{__('The Tax')}}</th>
                            <th>{{__('Final Total')}}</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($one as $index=>$item)

                            <tr class="spacer">
                                <td>{{$index + 1}}</td>
                                <td>{{optional($item->part)->name}}</td>
                                <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : __('Not determined')}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>{{$item->price}}</td>
                                <td>{{$item->sub_total}}</td>
                                <td>{{__($item->discount_type)}}</td>
                                <td>{{$item->discount}}</td>
                                <td>{{$item->total_after_discount}}</td>
                                <td>{{$item->tax}}</td>
                                <td>{{$item->total}}</td>

                            </tr>

                        @endforeach


                        </tbody>
                    </table>

                </div>
            </div>

            @if( $loop->last)
                <div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">

                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <thead>
                            <tr class="heading">
                                <th style="background:#CCC !important;color:black">{{__('Tax Name')}}</th>
                            <!-- <th style="background:#CCC !important;color:black">{{__('Tax Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('The Tax')}}</th> -->
                                <th style="background:#CCC !important;color:black">{{__('Tax Value')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $tax_value = 0;
                            @endphp

                            @foreach($saleSupplyOrder->taxes()->where('type', 'tax')->get() as $tax)

                                @php
                                    $tax_value += $tax->value;
                                @endphp

                                <tr class="item">
                                    <td>{{$tax->name}}</td>
                                <!-- <td>{{__($tax->tax_type)}}</td>
                        <td>{{$tax->value}}</td> -->
                                    <td>{{round(taxValueCalculated($saleSupplyOrder->total_after_discount, $saleSupplyOrder->sub_total, $tax),2)}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="col-xs-6">
                        <table class="table table-bordered">
                            <thead>
                            <tr class="heading">
                                <th style="background:#CCC !important;color:black">{{__('Payment Name')}}</th>
                            <!-- <th style="background:#CCC !important;color:black">{{__('Payment type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('The Payment')}}</th> -->
                                <th style="background:#CCC !important;color:black">{{__('Payment Value')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @php
                                $tax_value = 0;
                            @endphp

                            @foreach($saleSupplyOrder->taxes()->where('type', 'additional_payments')->get() as $tax)

                                @php
                                    $tax_value += $tax->value;
                                @endphp

                                <tr class="item">
                                    <td>{{$tax->name}}</td>
                                <!-- <td>{{__($tax->tax_type)}}</td>
                        <td>{{$tax->value}}</td> -->
                                    <td>{{round(taxValueCalculated($saleSupplyOrder->total_after_discount, $saleSupplyOrder->sub_total, $tax),2)}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    <div class="col-xs-12" style="padding:0px !important">
                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6>{{__('Total Price')}}</h6>
                                </div>
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6> {{$saleSupplyOrder->sub_total}} </h6>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6>{{__('Discount Type')}}</h6>
                                </div>
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6> {{__($saleSupplyOrder->discount_type)}} </h6>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6>{{__('Discount')}}</h6>
                                </div>
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6> {{$saleSupplyOrder->discount}} </h6>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xs-12" style="padding:0px !important">
                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6>{{__('Total After Discount')}}</h6>
                                </div>
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6> {{__($saleSupplyOrder->total_after_discount)}} </h6>
                                </div>
                            </div>

                        </div>


                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6>{{__('Additional Payments')}}</h6>
                                </div>
                                <div class="col-xs-6" style="padding:0px !important">

                                    <h6> {{$saleSupplyOrder->additional_payments}} </h6>

                                </div>
                            </div>

                        </div>

                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-6" style="padding:0px !important">
                                    <h6>{{__('Total Tax')}}</h6>
                                </div>
                                <div class="col-xs-6" style="padding:0px !important">

                                    <h6> {{$saleSupplyOrder->tax}} </h6>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xs-12" style="padding:0px !important">
                        <div class="col-xs-12 text-center" style="padding:5px !important">


                            <div class="row last-total" style="background-color:#ddd !important">
                                <div class="col-xs-3">
                                    <h6>{{__('Final Total')}}</h6>
                                </div>
                                <div class="col-xs-9">
                                    <h6>{{$saleSupplyOrder->total}}</h6>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="" style="padding:0px !important">
                        <div class="col-xs-12 text-center" style="padding:5px !important">


                            <div class="row last-total" style="background-color:#ddd !important">

                                <div class="col-xs-12" style="padding:5px">
                                    <h6 data-id="data-totalInLetters" id="totalInLetters"> {{$saleSupplyOrder->total}} </h6>
                                </div>
                            </div>

                        </div>


                    </div>


                    <div class="col-xs-12">
                        <br>
                        <div class="col-xs-6">
                            <h5 class="title">{{__('Supply Terms')}}</h5>
                            <p style="font-size:14px">
                                @foreach($saleSupplyOrder->terms()->where('type','supply')->get() as $index=>$term)

                                    {{$index+1}}.
                                    {{$term->term}}
                                    <br> <br>

                                @endforeach
                            </p>
                        </div>

                        <div class="col-xs-6">
                            <h5 class="title">{{__('Payment Terms')}}
                                </h5>
                                    <p style="font-size:14px">
                                        @foreach($saleSupplyOrder->terms()->where('type','payment')->get() as $index=>$term)

                                            {{$index+1}}.
                                            {{$term->term}}
                                            <br> <br>

                                        @endforeach
                                    </p>
                        </div>

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



