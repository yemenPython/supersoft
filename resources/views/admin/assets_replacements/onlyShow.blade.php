<div id="assetDatatoPrint">
    <div class="border-container" style="">
        @foreach($assetReplacement->assetReplacementItems()->get()->chunk(15) as $one)


            @if( $loop->first)
                <div class="col-xs-12 table-responsive">

                    <table class="table static-table-wg">
                        <tbody>
                        <tr>
                            <th>{{__('Asset expense Number')}}</th>
                            <td> {{ $assetReplacement->number }} </td>
                            <th>{{__('Asset expense Type')}}</th>
                            <td> {{__($assetReplacement->status)}} </td>
                        </tr>


                        </tbody>
                    </table>

                </div>
            @endif

            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('Asset Replacement items')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th> # </th>
                            <th> {{ __('Asset Group') }} </th>
                            <th> {{ __('Asset name') }} </th>
                            <th> {{ __('Operation Date') }} </th>
                            <th> {{ __('Purchase Cost') }} </th>
                            <th> {{ __('Cost Before Replacement') }} </th>
                            <th> {{ __('Cost Replacement') }} </th>
                            <th> {{ __('Cost After Replacement') }} </th>
                            <th> {{ __('Age') }} </th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach ($one as $index => $item)
                            <tr class="spacer">

                                <td>
                                    {{$index}}
                                </td>

                                <td>
                                    {{optional($item->asset->group)->name}}
                                </td>

                                <td>
                                    {{optional($item->asset)->name}}
                                </td>

                                <td>
                                    {{optional($item->asset)->date_of_work}}
                                </td>

                                <td>
                                    {{optional($item->asset)->purchase_cost}}
                                </td>

                                <td>
                                    {{optional($item->asset)->annual_consumtion_rate}}
                                </td>

                                <td>
                                    {{$item->value_replacement}}
                                </td>

                                <td>
                                    {{$item->value_after_replacement}}
                                </td>

                                <td>
                                    {{$item->age}}
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

                        <div class="col-xs-12 text-center">


                            <div class="row last-total" style="background-color:#ddd !important">
                                <div class="col-xs-7">
                                    <h6>{{__('total cost')}}</h6>
                                </div>
                                <div class="col-xs-5">
                                    <h6> {{isset($assetReplacement) ? $assetReplacement->total_after_replacement : 0}} </h6>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-xs-12" style="padding:0 !important">
                        <div class="col-xs-12 text-center">


                            <div class="row last-total" style="background-color:#ddd !important">

                                <div class="col-xs-12">
                                    <h6 data-id="data-totalInLetters" id="totalInLettersShow">  {{isset($assetReplacement) ? $assetReplacement->total_after_replacement : 0}} </h6>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="col-xs-12">
                        <h5 class="title">{{__('Notes')}}</h5>
                        <p style="font-size:14px">

                            {!! $assetReplacement->note !!}

                        </p>
                    </div>
                </div>

            @endif
        @endforeach
    </div>
</div>



