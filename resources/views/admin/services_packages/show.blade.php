<div class="row small-spacing" id="assetDatatoPrint">


    <div class="print-wg-fatora">
        <div class="row">
            <div class="col-xs-4">

                <div style="text-align: right ">
                    <h5><i class="fa fa-home"></i> {{optional($servicePackage->branch)->name_ar}}</h5>
                    <h5><i class="fa fa-phone"></i> {{optional($servicePackage->branch)->phone1}} </h5>
                    <h5><i class="fa fa-globe"></i> {{optional($servicePackage->branch)->address}} </h5>
                    <h5><i class="fa fa-fax"></i> {{optional($servicePackage->branch)->fax}}</h5>
                    <h5><i class="fa fa-adjust"></i> {{optional($servicePackage->branch)->tax_card}}</h5>
                </div>
            </div>

            <div class="col-xs-4">

                <img class="text-center center-block" style="width: 100px; height: 100px;margin-top:20px"
                     src="{{optional($servicePackage->branch)->logo_img}}">
            </div>
            <div class="col-xs-4">

                <div style="text-align: left" class="my-1">
                    <h5>{{optional($servicePackage->branch)->name_en}} <i class="fa fa-home"></i></h5>
                    <h5>{{optional($servicePackage->branch)->phone1}} <i class="fa fa-phone"></i></h5>
                    <h5>{{optional($servicePackage->branch)->address}} <i class="fa fa-globe"></i></h5>
                    <h5>{{optional($servicePackage->branch)->fax}} <i class="fa fa-fax"></i></h5>
                    <h5>{{optional($servicePackage->branch)->tax_card}} <i class="fa fa-adjust"></i></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <h4 class="text-center">{{__('Services Package Details')}}</h4>
        <div class="row">
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
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
            <div class="col-xs-6">
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

                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="col-xs-12 wg-tb-snd">
        <h2 class="text-center">{{__('Service Items Data')}}</h2>
        <div style="margin:10px 15px">
            <table class="table table-bordered">
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

            </table>
        </div>
    </div>

</div>

<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Before Discount')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_price"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($servicePackage) ? $servicePackage->total_before_discount : 0}}" class="form-control">
        </td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total After Discount')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($servicePackage) ? $servicePackage->total_after_discount : 0}}" class="form-control">
        </td>
        </tbody>
    </table>
</div>
