<div class="row small-spacing" id="asset_to_print">


    <div class="row" style="padding:0px 10px !important;">

        <div class="col-xs-4" style="{{ $lang == 'ar' ? 'float: left !important' : '' }}">
            <div style="text-align: left" class="my-1">
                <h5><b>{{optional($branchToPrint)->name_en}} </b></h5>
                <h5><b>Phone 1 : </b> {{optional($branchToPrint)->phone1}} </h5>
                <h5><b>Phone 2 : </b> {{optional($branchToPrint)->phone2}} </h5>
            <!-- <h5><b>Address : </b> {{optional($branchToPrint)->address_en}} </h5> -->
                <h5><b>Fax : </b> {{optional($branchToPrint)->fax}} </h5>
            <!-- <h5><b>Tax Number : </b> {{optional($branchToPrint)->tax_card}} </h5> -->
            </div>
        </div>
        <div class="col-xs-4 text-center" style="{{ $lang == 'ar' ? 'float: left !important' : '' }}">
            <img style="width: 200px; height: 100px"
                 src="{{isset($branchToPrint->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}">

        </div>
        <div class="col-xs-4">
            <div style="text-align: right">
                <h5><b> {{optional($branchToPrint)->name_ar}}</b></h5>
                <h5><b>{{__('phone1')}} : </b> {{optional($branchToPrint)->phone1}} </h5>
                <h5><b>{{__('phone2')}} : </b> {{optional($branchToPrint)->phone2}} </h5>
            <!-- <h5><b>{{__('address')}} : </b> {{optional($branchToPrint)->address_ar}} </h5> -->
                <h5><b>{{__('fax' )}} : </b> {{optional($branchToPrint)->fax}}</h5>
            <!-- <h5><b>{{__('Tax Card')}} : </b> {{optional($branchToPrint)->tax_card}}</h5> -->
            </div>
        </div>
    </div>

    <hr>

    <h4 class="text-center">

        {{ $asset->umber }}

    </h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">

            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('branch name')}}</th>
                        <td>{{$asset->branch->name }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$asset->date}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Time')}}</th>
                        <td>{{$asset->time}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Type')}}</th>
                        <td>{{$asset->type === 'sale' ?  __('Sale') : __('exclusion')}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>

                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('total purchase cost')}}</th>
                        <td>{{$asset->total_purchase_cost}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('total past consumtion')}}</th>
                        <td>{{$asset->total_past_consumtion}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('total replacement')}}</th>
                        <td>{{$asset->total_replacement}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('total consumtion')}}</th>
                        <td>{{$asset->total_current_consumtion}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('final total consumtion')}}</th>
                        <td>{{$asset->final_total_consumtion}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('total sale amount')}}</th>
                        <td>{{$asset->total_sale_amount}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-12">
                <table class="table table-bordered">

                    <thead>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Name')}}</th>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Sale amount')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($asset->items->isNotEmpty())
                        @foreach($asset->items as $item)
                            <tr>
                                <td>{{$item->asset->name}}</td>
                                <td>{{$item->sale_amount}}</td>
                            </tr>
                        @endforeach
                    @endif

                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <div>
        {!! $asset->note !!}
    </div>

</div>


