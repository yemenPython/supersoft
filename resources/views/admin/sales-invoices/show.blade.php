<div id="sales_invoice_print">
    <div class="border-container" style="">
        @foreach($sales_invoice->items()->get()->chunk(15) as $one)


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
                            {{__('Sales Invoice')}}
                        @endif
                    </h3>

                </div>
            </div>

            @if( $loop->first)
                <div class="middle-data-h-print">

                    <div class="invoice-to print-padding-top">
                        <div class="row">
                            <div class="col-xs-4">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <th style="background:#CCC !important;color:black" scope="row">{{__('Invoice Number')}}</th>
                                        <td>{{$sales_invoice->invoice_number}}</td>
                                    </tr>
                                    <tr>
                                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                                        <td>{{$sales_invoice->date}}</td>
                                    </tr>
                                    <!-- <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Invoice Type')}}</th>
                        <td>{{$sales_invoice->type}}</td>
                    </tr> -->
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-xs-4">
                                <table class="table table-bordered">
                                    <tbody>
                                    <!-- <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Time')}}</th>
                        <td>{{$sales_invoice->time}}</td>
                    </tr> -->
                                    <tr>
                                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                                        <td>{{auth()->user()->name}}</td>
                                    </tr>
                                    <tr>
                                        <th style="background:#CCC !important;color:black" scope="row">{{__('Payment status')}}</th>
                                        <td>{{$sales_invoice->remaining == 0 ? __('Completed') : __('Not Completed')}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-xs-4">
                                <table class="table table-bordered">
                                    <tbody>
                                    <tr>
                                        <th style="background:#CCC !important;color:black" scope="row">{{__('Customer Name')}}</th>
                                        <td>{{optional($sales_invoice->customer)->name}}</td>
                                    </tr>
                                    <tr>
                                        <th style="background:#CCC !important;color:black" scope="row">{{__('Customer Phone')}}</th>
                                        <td>{{optional($sales_invoice->customer)->phone1 ?? optional($sales_invoice->customer)->phone2}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            @endif

            <div style="padding:0 20px;">
                <h5> {{__('Sales Invoice')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="heading">
                            <th style="background:#CCC !important;color:black">{{__('Unit')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Type')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('spart')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Sold Quantity')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Selling Price')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Discount Type')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Discount')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Total')}}</th>
                            <th style="background:#CCC !important;color:black">{{__('Total After Discount')}}</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($one as $item)

                            <tr class="item">
                                <td>{{optional($item->part->sparePartsUnit)->unit}}</td>
                                <td>{{optional($item->part->sparePartsType)->type}}</td>
                                <td>{{optional($item->part)->name}}</td>
                                <td>{{$item->sold_qty}}</td>
                                <td>{{$item->selling_price}}</td>
                                <td>{{__($item->discount_type)}}</td>
                                <td>{{$item->discount}}</td>
                                <td>{{number_format($item->total_before_discount, 2)}}</td>
                                <td>{{number_format($item->total_after_discount, 2)}}</td>
                            </tr>
                        @endforeach


                        </tbody>
                    </table>

                </div>
            </div>

            @if( $loop->last)
                <div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">

                    <div class="row">
                        <div class="col-xs-12 wg-tb-snd">
                            <div style="margin:10px 15px">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr class="heading">
                                        <th style="background:#CCC !important;color:black">{{__('Tax Name')}}</th>
                                        <th style="background:#CCC !important;color:black">{{__('Tax Type')}}</th>
                                        <th style="background:#CCC !important;color:black">{{__('Tax Value')}}</th>
                                        <th style="background:#CCC !important;color:black">{{__('Invoice Tax')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($taxes as $tax)
                                        <tr class="item">
                                            <td>{{$tax->name}}</td>
                                            <td>{{__($tax->tax_type)}}</td>
                                            <td>{{$tax->value}}</td>
                                            <td><span>---</span></td>
                                        </tr>
                                    @endforeach
                                    <tr class="item">
                                        <th style="background:#CCC !important;color:black" colspan="2">{{__('Total Tax')}}</th>
                                        <td>{{$totalTax}}</td>
                                        <td>{{$sales_invoice->tax}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th style="background:#CCC !important;color:black" scope="row">{{__('Paid')}}</th>
                                    <td>{{$sales_invoice->paid}}</td>
                                </tr>
                                <tr>
                                    <th style="background:#CCC !important;color:black" scope="row">{{__('Remaining')}}</th>
                                    <td>{{$sales_invoice->remaining}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-xs-4">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th style="background:#CCC !important;color:black" scope="row">{{__('Discount')}} </th>
                                    <td>{{$sales_invoice->discount}}</td>
                                </tr>
                                <tr>
                                    <th style="background:#CCC !important;color:black" scope="row">{{__('Discount Type')}}</th>
                                    <td>{{__($sales_invoice->discount_type)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-xs-4">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th style="background:#CCC !important;color:black" scope="row">{{__('Total in Numbers')}}</th>
                                    <td>{{$sales_invoice->total}}</td>
                                </tr>
                                <tr>
                                <!-- <th style="background:#CCC !important;color:black" scope="row">{{__('Total in letters')}}</th> -->
                                    <td data-id="data-totalInLetters" id="totalInLetters">{{$sales_invoice->total}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>

                    </div>

                    <div class="col-xs-12">
                        @if($setting)
                        <div class="col-xs-6">
                            <p style="font-size:14px">

                                {!! $setting->SalesInvoiceTerms !!}

                            </p>
                        </div>
                        @endif
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



