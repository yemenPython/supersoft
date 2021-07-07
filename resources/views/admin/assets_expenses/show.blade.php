<div class="row small-spacing" id="assetDatatoPrint">


    <div class="print-wg-fatora">
        <div class="row">
            <div class="col-xs-4">

                <div style="text-align: right ">
                    <h5><i class="fa fa-home"></i> {{optional($assetExpense->branch)->name_ar}}</h5>
                    <h5><i class="fa fa-phone"></i> {{optional($assetExpense->branch)->phone1}} </h5>
                    <h5><i class="fa fa-globe"></i> {{optional($assetExpense->branch)->address}} </h5>
                    <h5><i class="fa fa-fax"></i> {{optional($assetExpense->branch)->fax}}</h5>
                    <h5><i class="fa fa-adjust"></i> {{optional($assetExpense->branch)->tax_card}}</h5>
                </div>
            </div>

            <div class="col-xs-4">

                <img class="text-center center-block" style="width: 100px; height: 100px;margin-top:20px"
                     src="{{optional($assetExpense->branch)->logo_img}}">
            </div>
            <div class="col-xs-4">

                <div style="text-align: left" class="my-1">
                    <h5>{{optional($assetExpense->branch)->name_en}} <i class="fa fa-home"></i></h5>
                    <h5>{{optional($assetExpense->branch)->phone1}} <i class="fa fa-phone"></i></h5>
                    <h5>{{optional($assetExpense->branch)->address}} <i class="fa fa-globe"></i></h5>
                    <h5>{{optional($assetExpense->branch)->fax}} <i class="fa fa-fax"></i></h5>
                    <h5>{{optional($assetExpense->branch)->tax_card}} <i class="fa fa-adjust"></i></h5>
                </div>
            </div>
        </div>
    </div>

    <h4 class="text-center">{{__($assetExpense->type . ' Purchase Request')}}</h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Number')}}</th>
                        <td>{{$assetExpense->number }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$assetExpense->time}} - {{$assetExpense->date}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{optional($assetExpense->user)->name}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Status')}}</th>
                        <td>{{__($assetExpense->status)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
            <table class="table table-bordered">
                <thead>
                <tr class="heading">
                    <th style="background:#CCC !important;color:black">{{__('#')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Assets Groups')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Assets')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Expenses Types')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Expenses Items')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Expense Cost')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($assetExpense->assetExpensesItems as $index=>$assetExpensesItem)

                    <tr class="item">
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

</div>

<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_price"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($assetExpense) ? $assetExpense->total : 0}}" class="form-control">
            <input id="total_price_hidden" type="hidden" name="total"
                   value="{{isset($assetExpense) ? $assetExpense->total : 0}}">
        </td>
        </tbody>
    </table>
</div>
