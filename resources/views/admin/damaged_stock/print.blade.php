
<div id="concession_to_print" >
    <div class="border-container" style="">
@foreach($damagedStock->items()->get()->chunk(16) as $one)


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
                                <span > {{__('Damaged Stock')}} </span>
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
                    <h5>{{__('Damaged Stock data')}}</h5>
                </div>
                <div class="col-xs-6" style="padding-right: 50px;">
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-time-user">
                                <tr>
                                    <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                                    <td style="font-weight: normal !important;">{{$damagedStock->time}}
                                        - {{$damagedStock->date}}</td>
                                </tr>
                                <tr>
                                    <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                                    <td style="font-weight: normal !important;">{{optional($damagedStock->user)->name}}</td>
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
                    <th style="width:20% !important;">{{__('Number')}}</th>
                    <td style="width:30% !important;"> {{$damagedStock->number }} </td>
                    <th>{{__('damage type')}}</th>
                    <td>
                        @if($damagedStock->type == 'natural' )
                             {{__('Natural')}}
                        @else
                            {{__('un_natural')}}
                        @endif

                    </td>
                </tr>

                </tbody>
            </table>

        </div>
@endif

        <div style="padding:0 20px;">
            <h5 class="invoice-to-title">{{__('Damaged Stock items')}}</h5>

            <div class="table-responsive">
            <table class="table print-table-wg table-borderless" @if(!$loop->first) style="margin-top: 20px;" @endif>
                <thead>

                <tr class="spacer" style="border-radius: 30px;">
                    <th>{{__('#')}}</th>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Part Type')}}</th>
                    <th>{{__('Unit')}}</th>
                    <th>{{__('Price Segments')}}</th>
                    <th>{{__('Quantity')}}</th>
                    <th>{{__('Price')}}</th>
                    <th>{{__('Total')}}</th>
                    <th>{{__('Store')}}</th>
                </tr>

                </thead>
                <tbody>
                @foreach($one as $index=>$item)

                        <tr class="spacer">
                            <td>{{$index + 1}}</td>
                            <td>{{optional($item->part)->name}}</td>
                            <td>{{optional($item->sparePart)->type}}</td>
                            <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : __('Not determined')}}</td>
                            <td>{{$item->partPriceSegment ? $item->partPriceSegment->name : __('Not determined')}}</td>
                            <td>{{$item->quantity}}</td>
                            <td>{{$item->price}}</td>
                            <td>{{$item->price * $item->quantity}}</td>
                            <td>{{$item->store ? $item->store->name :  __('Not determined')}}</td>
                        </tr>

                @endforeach


                </tbody>
            </table>

            </div>
        </div>

@if( $loop->last)
        <div class="row right-peice-wg" style="padding:0 15px 20px 15px;">

            <div class="col-xs-12" style="padding:0px !important">
                <div class="col-xs-6 text-center" style="padding:5px !important">
                    <div class="row last-total">
                    <div class="col-xs-6">
                            <h6>{{__('Quantity')}}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h6>{{$damagedStock->items->sum('quantity') }}</h6>
                        </div>
                    </div>

                </div>

                <div class="col-xs-6 text-center" style="padding:5px !important">
                    <div class="row last-total" style="background-color:#ddd !important">
                    <div class="col-xs-6">
                            <h6>{{__('Final Total')}}</h6>
                        </div>
                        <div class="col-xs-6">
                            <h6>{{$damagedStock->total}}</h6>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-xs-12" style="padding:0 !important">
                <div class="col-xs-12 text-center" style="padding:5px !important">


                    <div class="row last-total" style="background-color:#ddd !important">

                        <div class="col-xs-12">
                            <h6 data-id="data-totalInLetters" id="totalInLetters"> {{$damagedStock->total}} </h6>
                        </div>
                    </div>

                </div>


            </div>


            <div class="" id="employees_percent"
         style="{{isset($damagedStock) && $damagedStock->type == 'un_natural' ? '':'display: none;' }}">
            <div style="padding:0 20px;">
            <h5 class="invoice-to-title">{{__('Damage Employees')}}</h5>

            <div class="table-responsive">



            <table class="table print-table-wg table-borderless">
                <thead>

                <tr class="spacer" style="border-radius: 30px;">
                <th>{{__('Name')}}</th>
                <th>{{__('Damage Percent')}}</th>
                <th>{{__('Total Amount')}}</th>
                </tr>

                </thead>
                <tbody>
                @if(isset($damagedStock))

@foreach($damagedStock->employees as $damaged_employee)
                        <tr class="spacer">
                        <td>{{$damaged_employee->name}}</td>
                        <td>{{$damaged_employee->pivot->percent}} % </td>
                        <td>{{$damaged_employee->pivot->amount}}</td>
                        </tr>

                        @endforeach

@endif
                </tbody>
            </table>



            </div>
            </div>
            </div>

<div class="row right-peice-wg">
    <div class="col-xs-12">
         <h5 class="title">{{__('Notes')}}</h5>
         <p style="font-size:12px">
         {{$damagedStock->description}}

    </p>
    </div>

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


