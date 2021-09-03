<div id="concession_to_print">
    <div class="border-container" style="">
        @foreach($concession->concessionItems()->get()->chunk(18) as $one)


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
                        @if($concession->type == 'add' )
                        {{ __('Add Concession') }}
                        @else
                        {{ __('Withdrawal Concession') }} </span>
                        @endif
                        @endif
                    </h3>

                </div>
            </div>

            @if( $loop->first)
                <div class="middle-data-h-print">

                    <div class="invoice-to print-padding-top">
                        <div clas="row">
                            <div class="col-xs-6">
                                <h5>{{__('concession data')}}</h5>
                            </div>
                            <div class="col-xs-6" style="padding-right: 50px;">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <table class="table table-time-user">
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                                                <td style="font-weight: normal !important;">{{$concession->time}} - {{$concession->date}}</td>
                                            </tr>
                                            <tr>
                                                <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                                                <td style="font-weight: normal !important;">{{optional($concession->user)->name}}</td>
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
                            <th>{{__('Concession number')}}</th>
                            <td>{{$concession->type == 'add' ? $concession->add_number : $concession->withdrawal_number }}</td>
                            <th>{{__('Item Number')}}</th>
                            <td>{{optional($concession->concessionable)->number}}</td>
                            <th>{{__('Type')}}</th>
                            <td>{{__($concession->type . ' concession')}}</td>
                        </tr>
                        <tr>
                            <th>{{__('Concession Type')}}</th>
                            <td>{{optional($concession->concessionType)->name}}</td>
                            <th>{{__('Status')}}</th>
                            <td>{{__($concession->status)}}</td>
                            <th>{{__('Execution Status')}}</th>
                            <td>@if($concession->concessionExecution)

                                    @if($concession->concessionExecution ->status == 'pending' )
                                        {{__('Processing')}}

                                    @elseif($concession->concessionExecution ->status == 'finished' )
                                        {{__('Finished')}}

                                    @elseif($concession->concessionExecution ->status == 'late' )
                                        {{__('Late')}}
                                    @endif


                                @else
                                    {{__('Not determined')}}

                                @endif</td>
                        </tr>


                        </tbody></table>

                </div>
            @endif

            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('items')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th scope="col">#</th>
                            <th scope="col">{{__('Name')}}</th>
                            <th scope="col">{{__('Part Type')}}</th>
                            <th scope="col">{{__('Unit')}}</th>
                            <th scope="col">{{__('Quantity')}}</th>
                            <th scope="col">{{__('Price')}}</th>
                            <th scope="col">{{__('Total')}}</th>
                            <th> {{ __('Barcode') }} </th>
                            <th> {{ __('Supplier Barcode') }} </th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($one as $index=>$item)

                            <tr class="spacer">
                                <td>{{$index + 1}}</td>
                                <td>{{optional($item->part)->name}}</td>
                                <td> {{ $item->sparePart ? $item->sparePart->type : '---' }}</td>
                                <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : __('Not determined')}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>{{$item->price}}</td>
                                <td> {{ $item->price * $item->quantity}}</td>
                                <td>{{$item->partPrice  ? $item->partPrice->barcode : '---'}}</td>
                                <td>{{$item->partPrice ? $item->partPrice->supplier_barcode : '---'}}</td>
                            </tr>

                        @endforeach


                        </tbody>
                    </table>

                </div>
            </div>

            @if( $loop->last)
                <div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">
                    <div class="col-xs-7">
                        <h5 class="title">{{__('Notes')}}</h5>
                        <p style="width: 80%;font-size:12px">
                            {!! $concession->description !!}

                        </p>

                    </div>
                    <div class="col-xs-5 text-center">


                        <div class="row last-total">
                            <div class="col-xs-6">
                                <h6>{{__('Total Price')}}</h6>
                            </div>
                            <div class="col-xs-6">
                                <h6>{{$concession->total}}</h6>
                            </div>
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


