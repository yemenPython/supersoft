<div id="assetDatatoPrint">
    <div class="border-container" style="">
        @foreach($assetExpense->assetExpensesItems()->get()->chunk(15) as $one)

            @if( $loop->first)
                <div class="col-xs-12 table-responsive">
                    <table class="table static-table-wg">
                        <tbody>
                        <tr>
                            <th>{{__('Asset expense Number')}}</th>
                            <td> {{ $assetExpense->number }} </td>
                            <th>{{__('Asset expense Type')}}</th>
                            <td> {{__($assetExpense->status)}} </td>
                        </tr>
                        </tbody>
                    </table>

                </div>
            @endif

            <div style="padding:0 20px;">
                <h5 class="invoice-to-title">{{__('Asset Purchase Invoice items')}}</h5>

                <div class="table-responsive">
                    <table class="table print-table-wg table-borderless"
                           @if(!$loop->first) style="margin-top: 20px;" @endif>
                        <thead>

                        <tr class="spacer" style="border-radius: 30px;">
                            <th>{{__('#')}}</th>
                            <th>{{__('Asset Group')}}</th>
                            <th>{{__('Asset name')}}</th>
                            <th>{{__('Expenses Types')}}</th>
                            <th>{{__('Expenses Items')}}</th>
                            <th>{{__('Expense Cost')}}</th>
                        </tr>

                        </thead>
                        <tbody>
                        @foreach($one as $index=>$assetExpensesItem)
                            <tr class="spacer">

                                <td>{{$index + 1}}</td>
                                <td>{{optional($assetExpensesItem->asset->group)->name}}</td>
                                <td>{{optional($assetExpensesItem->asset)->name}}</td>
                                <td>{{optional($assetExpensesItem->assetExpenseItem->assetsTypeExpense)->name}}</td>
                                <td>{{optional($assetExpensesItem->assetExpenseItem)->item}}</td>
                                <td>{{$assetExpensesItem->price}}</td>

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
                                    <h6> {{isset($assetExpense) ? $assetExpense->total : 0}} </h6>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-xs-12" style="padding:0 !important">
                        <div class="col-xs-12 text-center">


                            <div class="row last-total" style="background-color:#ddd !important">

                                <div class="col-xs-12">
                                    <h6 data-id="data-totalInLetters" id="totalInLettersShow"> {{isset($assetExpense) ? $assetExpense->total : 0}} </h6>
                                </div>
                            </div>

                        </div>


                    </div>

                    <div class="col-xs-12">
                        <h5 class="title">{{__('Notes')}}</h5>
                        <p style="font-size:14px">

                            {!! $assetExpense->note !!}

                        </p>
                    </div>
                </div>

            @endif
        @endforeach
    </div>
</div>
