<div class="row small-spacing" id="concession_to_print">


    <div class="print-wg-fatora">
        <div class="row">
            <div class="col-xs-4">

                <div style="text-align: right ">
                    <h5><i class="fa fa-home"></i> {{optional($saleQuotation->branch)->name_ar}}</h5>
                    <h5><i class="fa fa-phone"></i> {{optional($saleQuotation->branch)->phone1}} </h5>
                    <h5><i class="fa fa-globe"></i> {{optional($saleQuotation->branch)->address}} </h5>
                    <h5><i class="fa fa-fax"></i> {{optional($saleQuotation->branch)->fax}}</h5>
                    <h5><i class="fa fa-adjust"></i> {{optional($saleQuotation->branch)->tax_card}}</h5>
                </div>
            </div>

            <div class="col-xs-4">

                <img class="text-center center-block" style="width: 100px; height: 100px;margin-top:20px"
                     src="{{$saleQuotation->branch->logo_img}}">
            </div>
            <div class="col-xs-4">

                <div style="text-align: left" class="my-1">
                    <h5>{{optional($saleQuotation->branch)->name_en}} <i class="fa fa-home"></i></h5>
                    <h5>{{optional($saleQuotation->branch)->phone1}} <i class="fa fa-phone"></i></h5>
                    <h5>{{optional($saleQuotation->branch)->address}} <i class="fa fa-globe"></i></h5>
                    <h5>{{optional($saleQuotation->branch)->fax}} <i class="fa fa-fax"></i></h5>
                    <h5>{{optional($saleQuotation->branch)->tax_card}} <i class="fa fa-adjust"></i></h5>
                </div>
            </div>
        </div>
    </div>

{{--    <h4 class="text-center">{{__($saleQuotation->type . ' Purchase Request')}}</h4>--}}

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Number')}}</th>
                        <td>{{$saleQuotation->number }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$saleQuotation->time}} - {{$saleQuotation->date}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{optional($saleQuotation->user)->name}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Status')}}</th>
                        <td>{{__($saleQuotation->status)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Type')}}</th>
                        <td>{{__($saleQuotation->type )}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Customer')}}</th>
                        <td>{{ $saleQuotation->customer ? $saleQuotation->customer->name : '---'}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date From')}}</th>
                        <td>{{__($saleQuotation->supply_date_from)}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date To')}}</th>
                        <td>{{__($saleQuotation->supply_date_to )}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>


            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Period of quotation from')}}</th>
                        <td>{{__($saleQuotation->date_from)}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Different Days')}}</th>
                        <td>{{__($saleQuotation->different_days)}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Period of quotation to')}}</th>
                        <td>{{__($saleQuotation->date_to )}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Remaining Days')}}</th>
                        <td>{{__($saleQuotation->remaining_days)}}</td>
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
                <tr class="heading">
                    <th style="background:#CCC !important;color:black">{{__('#')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Name')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Part Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Unit')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Price')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Sub Total')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total After Discount')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Tax')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Total')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($saleQuotation->items()->where('active', 1)->get() as $index=>$item)

                    <tr class="item">
                        <td>{{$index + 1}}</td>
                        <td>{{optional($item->part)->name}}</td>
                        <td>{{ $item->sparePart ? $item->sparePart->type : '---'}}</td>
                        <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : '---'}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->price}}</td>
                        <td>{{$item->discount_type}}</td>
                        <td>{{$item->discount}}</td>
                        <td>{{$item->sub_total}}</td>
                        <td>{{$item->total_after_discount}}</td>
                        <td>{{$item->tax}}</td>
                        <td>{{$item->total}}</td>
                    </tr>

                @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">

        <div class="row">
            <div class="col-xs-12 wg-tb-snd">
                <div style="margin:10px 15px">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="heading">
                            <th style="background:#CCC !important;color:black">{{__('Tax Name')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Tax Type')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Tax Value')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Calculated Tax Value')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $tax_value = 0;
                        @endphp

                        @foreach($saleQuotation->taxes()->where('type', 'tax')->get() as $tax)

                            @php
                                $tax_value += $tax->value;
                            @endphp

                            <tr class="item">
                                <td>{{$tax->name}}</td>
                                <td>{{__($tax->tax_type)}}</td>
                                <td>{{$tax->value}}</td>
                                <td>{{round(taxValueCalculated($saleQuotation->total_after_discount, $saleQuotation->sub_total, $tax),2)}}</td>
                            </tr>
                        @endforeach

                        <tr class="item">
                            <th style="background:#CCC !important;color:black" colspan="2">{{__('Total Tax')}}</th>
                            <td>{{$tax_value}}</td>
                            <td>{{$saleQuotation->tax}}</td>
                        </tr>
                        </tbody>
                    </table>


                    <table class="table table-bordered">
                        <thead>
                        <tr class="heading">
                            <th style="background:#CCC !important;color:black">{{__('Tax Name')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Tax Type')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Tax Value')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Calculated Tax Value')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @php
                            $tax_value = 0;
                        @endphp

                        @foreach($saleQuotation->taxes()->where('type', 'additional_payments')->get() as $tax)

                            @php
                                $tax_value += $tax->value;
                            @endphp

                            <tr class="item">
                                <td>{{$tax->name}}</td>
                                <td>{{__($tax->tax_type)}}</td>
                                <td>{{$tax->value}}</td>
                                <td>{{round(taxValueCalculated($saleQuotation->total_after_discount, $saleQuotation->sub_total, $tax),2)}}</td>
                            </tr>
                        @endforeach

                        <tr class="item">
                            <th style="background:#CCC !important;color:black" colspan="2">{{__('Total Additional Payments')}}</th>
                            <td>{{$tax_value}}</td>
                            <td>{{$saleQuotation->additional_payments}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('SubTotal')}} </th>
                        <td>{{$saleQuotation->sub_total}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Total After Discount')}}</th>
                        <td>{{__($saleQuotation->total_after_discount)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Discount')}} </th>
                        <td>{{$saleQuotation->discount}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Discount Type')}}</th>
                        <td>{{__($saleQuotation->discount_type)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Total in Numbers')}}</th>
                        <td>{{$saleQuotation->total}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Total in letters')}}</th>
                        <td data-id="data-totalInLetters" id="totalInLetters">{{$saleQuotation->total}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">

        <div class="row">
            <div class="col-xs-12 wg-tb-snd">
                <h4 class="text-center">{{__('Supply Terms')}}</h4>
                <div style="margin:10px 15px">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="heading">
                            <th style="background:#CCC !important;color:black">#</th>
                            <th style="background:#CCC !important;color:black">{{__('Term')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($saleQuotation->terms()->where('type','supply')->get() as $index=>$term)
                            <tr class="item">
                                <td>{{$index+1}}</td>
                                <td>{{$term->term}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-xs-12 wg-tb-snd">
                <h4 class="text-center">{{__('Payment Terms')}}</h4>
                <div style="margin:10px 15px">
                    <table class="table table-bordered">
                        <thead>
                        <tr class="heading">
                            <th style="background:#CCC !important;color:black">#</th>
                            <th style="background:#CCC !important;color:black">{{__('Term')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($saleQuotation->terms()->where('type','payment')->get() as $index=>$term)
                            <tr class="item">
                                <td>{{$index+1}}</td>
                                <td>{{$term->term}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{--    @if($purchaseRequest->description)--}}
    {{--        <div class="col-xs-12 wg-tb-snd">--}}
    {{--            <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">--}}
    {{--                {!! $purchaseRequest->description !!}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    @endif--}}
</div>
