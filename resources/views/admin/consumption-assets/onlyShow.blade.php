<div id="asset_to_print">
    <div class="border-container" style="">
        @foreach($asset->items()->get()->chunk(15) as $one)


            @if( $loop->first)
                <div class="col-xs-12 table-responsive">

                    <table class="table static-table-wg">
                        <tbody>
                        <tr>
                            <th style="width:20% !important">{{__('consumtion Number')}}</th>
                            <td> {{ $asset->number }} </td>
                            <th style="width:20% !important">{{__('consumtion Type')}}</th>
                            <td> {{__($asset->type)}} </td>
                        </tr>
                        <tr>
                            <th>{{__('Date From')}}</th>
                            <td>{{$asset->date_from}}</td>

                            <th>{{__('Date to')}}</th>
                            <td>{{$asset->date_to}}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            @endif

            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('consumtion Invoice items')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th>{{__('Asset name')}}</th>
                            <th>{{__('Asset Group')}}</th>
                            <th>{{__('consumption amount')}}</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($one as $item)

                            <tr class="spacer">
                                <td>{{$item->asset->name}}</td>
                                <td>{{$item->asset->group->name}}</td>
                                <td>{{$item->consumption_amount}}</td>
                            </tr>

                        @endforeach


                        </tbody>
                    </table>

                </div>
            </div>

            @if( $loop->last)
                <div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">


                    <div class="col-xs-12" style="padding:0 !important">


                        <div class="col-xs-6 text-center">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('total purchase cost')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{$asset->total_purchase_cost}} </h6>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-6 text-center">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('total past consumtion')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6>{{$asset->total_past_consumtion}}</h6>
                                </div>
                            </div>


                        </div>

                        <div class="col-xs-12" style="padding:0 !important">


                            <div class="col-xs-12 text-center">


                                <div class="row last-total" style="background-color:#ddd !important">
                                    <div class="col-xs-7">
                                        <h6>{{__('the total consumtion')}}</h6>
                                    </div>
                                    <div class="col-xs-5">
                                        <h6>{{$asset->total_replacement}}</h6>
                                    </div>
                                </div>

                            </div>


                        </div>

                        <div class="col-xs-12" style="padding:0 !important">


                            <div class="col-xs-12 text-center">


                                <div class="row last-total" style="background-color:#ddd !important">

                                    <h6 id="totalInLettersShow">{{$asset->total_replacement}}</h6>

                                </div>

                            </div>


                        </div>
                    </div>

                    <div class="col-xs-12">
                        <h5 class="title">{{__('Notes')}}</h5>
                        <p style="font-size:14px">

                            {!! $asset->note !!}

                        </p>
                    </div>
                </div>

            @endif
        @endforeach
    </div>
</div>



