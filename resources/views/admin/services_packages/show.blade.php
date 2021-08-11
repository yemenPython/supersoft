<section id="assetDatatoPrint">
<div class="border-container" style="">
  
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
        {{__('Services Package')}}
        </h3>

</div>
</div>
</div>


<div class="middle-data-h-print">

        <div class="invoice-to print-padding-top">
            <div clas="row">
                <div class="col-xs-6">
        <h5>{{__('Services Package Details')}}</h5>
        </div>
                <div class="col-xs-6" style="padding-right: 50px;">
                    <div class="row">
                        <div class="col-xs-12">
                            <table class="table table-time-user">
                                <tr>
                        <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                        <td style="font-weight: normal !important;">{{$servicePackage->time}} - {{$servicePackage->date}}</td>
                        </tr>
                        <tr>
                        <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                        <td style="font-weight: normal !important;">{{optional($servicePackage->user)->name}}</td>
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
                    <th style="width:20% !important">{{__('Service Package name')}}</th>
                    <td>{{$servicePackage->name }} </td>
                </tr>
                                
                </tbody>
            </table>

                <table class="table static-table-wg">
                <tbody>
                <tr>
                    <th style="width:20% !important">{{__('Hours')}}</th>
                    <td>{{$servicePackage->number_of_hours}}</td>
                    <th style="width:20% !important">{{__('Minutes')}}</th>
                    <td>{{$servicePackage->number_of_min}}</td>
                  </tr>

                
                  </tbody>
            </table>

        </div>

        
        <div style="padding:0 20px;">
            <h5 class="invoice-to-title">{{__('Services Package Details')}}</h5>

<div class="table-responsive">
            <table class="table print-table-wg table-borderless">
                <thead>

                <tr class="spacer" style="border-radius: 30px;">
                <th scope="col">{!! __('Service Name') !!}</th>
                    <th scope="col">{!! __('Price') !!}</th>
                    <th scope="col">{!! __('Hours') !!}</th>
                    <th scope="col">{!! __('Minutes') !!}</th>
                    <th scope="col">{!! __('Quantity') !!}</th>
                    <th scope="col">{!! __('Total') !!}</th>
                </tr>

                </thead>
                <tbody>

                @if(isset($services))
                    @foreach($services  as $service)
                        <tr>
                            <td>{{ $service['name'] }}</td>
                            <td>{{$service['price']}}</td>
                            <td>{{$service['hours']}}</td>
                            <td>{{$service['minutes']}}</td>
                            <td>{{$service['q']}}</td>
                            <td>{{$service['total']}}</td>
                        </tr>
                @endforeach
                @endif

                
                </tbody>
            </table>

            </div>
        </div>

        
    <div class="col-xs-12" style="padding:0 !important">
                <div class="col-xs-6 text-center" style="padding:5px">

                <div class="row last-total">
                        <div class="col-xs-7">
                            <h6>{{__('Total Before Discount')}}</h6>
                        </div>
                        <div class="col-xs-5">
                            <h6> {{isset($servicePackage) ? $servicePackage->total_before_discount : 0}}</h6>
                        </div>
                    </div>

                </div>

                <div class="col-xs-6 text-center" style="padding:5px">


<div class="row last-total">
    <div class="col-xs-7">
        <h6>{{__('Total After Discount')}}</h6>
    </div>
    <div class="col-xs-5">
        <h6>{{isset($servicePackage) ? $servicePackage->total_after_discount : 0}}</h6>
    </div>
</div>

</div>


</div>

    <div class="col-xs-12" style="padding:0 !important">
                <div class="col-xs-4 text-center" style="padding:5px">

                <div class="row last-total">
                        <div class="col-xs-7">
                            <h6>{{__('Services Number')}}</h6>
                        </div>
                        <div class="col-xs-5">
                            <h6> {{$servicePackage->services_number}}</h6>
                        </div>
                    </div>

                </div>

                <div class="col-xs-4 text-center" style="padding:5px">


<div class="row last-total">
    <div class="col-xs-7">
        <h6>{{__('Discount Type')}}</h6>
    </div>
    <div class="col-xs-5">
        <h6> {{$servicePackage->discount_type === 'value' ? __('Value') : __('Percent') }} </h6>
    </div>
</div>

</div>


<div class="col-xs-4 text-center" style="padding:5px">
<div class="row last-total">
    <div class="col-xs-7">
        <h6>{{__('Discount Mount')}}</h6>
    </div>
    <div class="col-xs-5">
        <h6> {{$servicePackage->discount_value }}</h6>
    </div>
</div>

</div>


</div>


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

    </div>
</div>


