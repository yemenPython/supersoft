<div id="asset_to_print">
    <div class="border-container" style="">
        @foreach($asset->items()->get()->chunk(15) as $one)
            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('Asset Purchase Invoice items')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th>{{__('Asset name')}}</th>
                            <th>{{__('Asset Group')}}</th>
                            <th>{{__('purchase cost')}}</th>
                            <th>{{__('past consumtion')}}</th>
                            <th>{{__('purchase date')}}</th>
                            <th>{{__('work date')}}</th>
                            <th>{{__('consumtion rate')}}</th>
                            <th>{{__('asset age')}}</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($one as $item)

                            <tr class="spacer">
                                <td>{{$item->asset->name}}</td>
                                <td>{{$item->asset->group->name}}</td>
                                <td>{{$item->purchase_cost}}</td>
                                <td>{{$item->past_consumtion}}</td>
                                <td>{{$item->asset->purchase_date}}</td>
                                <td>{{$item->asset->date_of_work}}</td>
                                <td>{{$item->annual_consumtion_rate}}</td>
                                <td>{{$item->asset_age}}</td>
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
                                    <h6>{{__('Paid Amount')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6>{{$asset->paid_amount}}</h6>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-6 text-center">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('Remaining Amount')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{$asset->remaining_amount}}</h6>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="col-xs-12" style="padding:0 !important">
                        <div class="col-xs-12 text-center">


                            <div class="row last-total" style="background-color:#ddd !important">

                                <div class="col-xs-12">
                                    <h6 data-id="data-totalInLetters"
                                        id="totalInLettersShow"> {{$asset->total_purchase_cost}} </h6>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="col-xs-12">

                        <div class="col-xs-12">
                            <h5 class="title">{{__('Notes')}}</h5>
                            <p style="font-size:14px">

                                {!! $asset->note !!}

                            </p>
                        </div>
                    </div>
                </div>

            @endif


            <div class="print-foot-wg position-relative ml-0">

            </div>
        @endforeach
    </div>
</div>



