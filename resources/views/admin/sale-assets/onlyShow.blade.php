<div id="asset_to_print">
    <div class="border-container" style="">
        @foreach($asset->items()->get()->chunk(15) as $one)

            @if( $loop->first)

                <div class="col-xs-12 table-responsive">

                    <table class="table static-table-wg">
                        <tbody>
                        <tr>
                            <th>{{__('Sale assets Number')}}</th>
                            <td>  {{ $asset->number }} </td>
                            <th>{{__('Sale assets Type')}}</th>
                            <td> {{$asset->type === 'sale' ?  __('Sale') : __('exclusion')}} </td>
                        </tr>


                        </tbody>
                    </table>
                </div>
            @endif

            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('Sale assets items')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th> {{ __('Asset Group') }} </th>
                            <th> {{ __('Asset name') }} </th>
                            <th> {{ __('Sale amount') }} </th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($one as $item)
                            <tr class="spacer">


                                <td>
                                    {{optional($item->asset->group)->name}}
                                </td>

                                <td>
                                    {{optional($item->asset)->name}}
                                </td>

                                <td>
                                    {{$item->sale_amount}}
                                </td>


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
                                    <h6>{{__('total purchase cost')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{$asset->total_purchase_cost}} </h6>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('total past consumtion')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{$asset->total_past_consumtion}} </h6>
                                </div>
                            </div>

                        </div>


                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('total replacement')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{$asset->total_replacement}} </h6>
                                </div>
                            </div>

                        </div>

                    </div>


                    <div class="col-xs-12" style="padding:0 !important">

                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('total consumtion')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6>{{$asset->total_current_consumtion}}</h6>
                                </div>
                            </div>

                        </div>

                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('final total consumtion')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{$asset->final_total_consumtion}}</h6>
                                </div>
                            </div>

                        </div>


                        <div class="col-xs-4 text-center" style="padding:5px !important">


                            <div class="row last-total">
                                <div class="col-xs-7">
                                    <h6>{{__('total sale amount')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{$asset->total_sale_amount}} </h6>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-xs-12" style="padding:0 !important">


                        <div class="col-xs-12 text-center">


                            <div class="row last-total" style="background-color:#ddd !important">

                                <h6 id="totalInLettersShow"> {{$asset->total_sale_amount}} </h6>


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



