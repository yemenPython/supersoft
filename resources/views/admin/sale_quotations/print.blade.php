
<div id="concession_to_print">
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
                    <span > {{__('Sale Quotation')}} </span>

                </h3>

            </div>
        </div>
    </div>



    <div class="middle-data-h-print">

<div class="invoice-to print-padding-top">
    <div clas="row">
        <div class="col-xs-6">
            <h5>{{__('Sale Quotation data')}}</h5>
        </div>
        <div class="col-xs-6" style="padding-right: 50px;">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-time-user">
                        <tr>
                            <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                            <td style="font-weight: normal !important;">{{$saleQuotation->time}}
                                - {{$saleQuotation->date}}</td>
                        </tr>
                        <tr>
                            <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                            <td style="font-weight: normal !important;">{{optional($saleQuotation->user)->name}}</td>
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
                    <th>{{__('Quotation Number')}}</th>
                    <td> {{$saleQuotation->number }} </td>
                    <th>{{__('Type')}}</th>
                    <td> {{__($saleQuotation->type)}} </td>
                    <th>{{__('Status')}}</th>
                    <td> {{__($saleQuotation->status)}} </td>
                </tr>
                <tr>
                    <th>{{__('Period of quotation from')}}</th>
                    <td> {{__($saleQuotation->date_from)}} </td>
                    <th>{{__('Period of quotation to')}}</th>
                    <td> {{__($saleQuotation->date_to )}} </td>
                    <th>{{__('quotation days')}}</th>
                    <td> {{__($saleQuotation->remaining_days)}} </td>
                </tr>
                <tr>
                <th>{{__('Supply Date From')}}</th>
                <td>{{__($saleQuotation->supply_date_from)}} </td>
                    <th>{{__('Supply Date To')}}</th>
                    <td> {{__($saleQuotation->supply_date_to )}} </td>
                    <th>{{__('Quotation type')}}</th>
                    <td>

                        @if ($saleQuotation->type === "cash")
                            {{__($saleQuotation->type)}}
                        @else
                            {{__($saleQuotation->type)}}
                        @endif

                    </td>
                </tr>

                <tr>
                    <th>{{__('Customer name')}}</th>
                    <td colspan="6">{{ $saleQuotation->customer ? $saleQuotation->customer->name : __('Not determined')}}</td>
                </tr>


                </tbody>
            </table>

        </div>


        <div style="padding:0 20px;">
            <h5 class="invoice-to-title">{{__('Sale Quotation items')}}</h5>

            <div class="table-responsive">
            <table class="table print-table-wg table-borderless">
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
                @foreach($saleQuotation->items()->where('active', 1)->get() as $index=>$item)

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


        <div class="row right-peice-wg" style="padding:0 30px 50px 30px;">

<div class="col-xs-6">
    <table class="table table-bordered static-table-wg">
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

                        @foreach($saleQuotation->taxes()->where('type', 'tax')->get() as $tax)

                            @php
                                $tax_value += $tax->value;
                            @endphp

            <tr class="item">
                <td>{{$tax->name}}</td>
            <!-- <td>{{__($tax->tax_type)}}</td>
            <td>{{$tax->value}}</td> -->
            <td>{{round(taxValueCalculated($purchaseQuotation->total_after_discount, $purchaseQuotation->sub_total, $tax),2)}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>

<div class="col-xs-6">
    <table class="table table-bordered static-table-wg">
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

                        @foreach($saleQuotation->taxes()->where('type', 'additional_payments')->get() as $tax)

                            @php
                                $tax_value += $tax->value;
                            @endphp
            <tr class="item">
                <td>{{$tax->name}}</td>
            <!-- <td>{{__($tax->tax_type)}}</td>
            <td>{{$tax->value}}</td> -->
                <td>{{round(taxValueCalculated($saleQuotation->total_after_discount, $saleQuotation->sub_total, $tax),2)}}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
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
