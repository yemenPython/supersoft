<div id="assetDatatoPrint">
    <div class="border-container" style="">
        @foreach(collect($services)->chunk(15) as $one)


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
                            <span> {{__('Services Package')}} </span>
                        @endif
                    </h3>

                </div>
            </div>

            @if( $loop->first)
{{--                <div class="middle-data-h-print">--}}

{{--                    <div class="invoice-to print-padding-top">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-xs-12">--}}
{{--                                <h5>{{__('Services Package Details')}}</h5>--}}
{{--                            </div>--}}
{{--                            <div class="col-xs-12" style="padding-right: 50px;">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-xs-12">--}}
{{--                                        <table class="table table-bordered">--}}
{{--                                            <tbody>--}}
{{--                                            <tr>--}}
{{--                                                <th style="background:#CCC !important;color:black" scope="row">{{__('Services Number')}}</th>--}}
{{--                                                <td>{{$servicePackage->services_number}}</td>--}}
{{--                                            </tr>--}}

{{--                                            <tr>--}}
{{--                                                <th style="background:#CCC !important;color:black" scope="row">{{__('Service Package')}}</th>--}}
{{--                                                <td>{{$servicePackage->name }}</td>--}}
{{--                                            </tr>--}}
{{--                                            <tr>--}}
{{--                                                <th style="background:#CCC !important;color:black" scope="row">{{__('Hours')}}</th>--}}
{{--                                                <td>{{$servicePackage->number_of_hours}}</td>--}}
{{--                                            </tr>--}}

{{--                                            </tbody>--}}
{{--                                        </table>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                    </div>--}}
{{--                </div>--}}

                <div class="col-xs-12 table-responsive">
                    <div class="col-xs-12">
                        <h5>{{__('Services Package Details')}}</h5>
                    </div>
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Discount Type')}}</th>
                            <td>{{$servicePackage->discount_type === 'value' ? __('Value') : __('Percent') }}</td>
                        </tr>

                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Discount Mount')}}</th>
                            <td>{{$servicePackage->discount_value }}</td>
                        </tr>


                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Minutes')}}</th>
                            <td>{{$servicePackage->number_of_min}}</td>
                        </tr>
                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Services Number')}}</th>
                            <td>{{$servicePackage->services_number}}</td>
                        </tr>

                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Service Package')}}</th>
                            <td>{{$servicePackage->name }}</td>
                        </tr>
                        <tr>
                            <th style="background:#CCC !important;color:black" scope="row">{{__('Hours')}}</th>
                            <td>{{$servicePackage->number_of_hours}}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            @endif

            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('Service Items Data')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="heading">
                            <th scope="col">{!! __('Service Name') !!}</th>
                            <th scope="col">{!! __('Price') !!}</th>
                            <th scope="col">{!! __('Hours') !!}</th>
                            <th scope="col">{!! __('Minutes') !!}</th>
                            <th scope="col">{!! __('Quantity') !!}</th>
                            <th scope="col">{!! __('Total') !!}</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($one  as $service)
                            <tr>
                                <td>{{ $service['name'] }}</td>
                                <td>{{$service['price']}}</td>
                                <td>{{$service['hours']}}</td>
                                <td>{{$service['minutes']}}</td>
                                <td>{{$service['q']}}</td>
                                <td>{{$service['total']}}</td>
                            </tr>
                        @endforeach


                        </tbody>
                    </table>

                </div>
            </div>

            @if( $loop->last)
                <div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">


                    <div class="col-xs-12" style="padding:0 !important">

                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('Total Before Discount')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{isset($servicePackage) ? $servicePackage->total_before_discount : 0}} </h6>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('total past consumtion')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{$servicePackage->total_past_consumtion}} </h6>
                                </div>
                            </div>

                        </div>


                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('Total After Discount')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{isset($servicePackage) ? $servicePackage->total_after_discount : 0}} </h6>
                                </div>
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



