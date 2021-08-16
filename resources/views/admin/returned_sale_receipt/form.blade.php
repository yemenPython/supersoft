<div class="row">
    <div class="col-xs-12">

        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            @if(authIsSuperAdmin())
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                            <div class="input-group">

                                <span class="input-group-addon fa fa-file"></span>

                                <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                        onchange="changeBranch()" {{isset($returnedSaleReceipt) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($data['branches'] as $branch)
                                        <option value="{{$branch->id}}"
                                            {{isset($returnedSaleReceipt) && $returnedSaleReceipt->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                            @if(isset($returnedSaleReceipt))
                                <input type="hidden" name="branch_id" value="{{$returnedSaleReceipt->branch_id}}">
                            @endif
                        </div>

                    </div>
                </div>
            @endif

            <div class="col-md-12">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                        <div class="input-group">
                    <span class="input-group-addon">
                        <li class="fa fa-bars"></li>
                    </span>
                            <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}" disabled
                                   value="{{old('number', isset($returnedSaleReceipt)? $returnedSaleReceipt->number : '' )}}">
                        </div>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date" class="form-control datepicker" id="date"
                                   value="{{old('date', isset($returnedSaleReceipt) ? $returnedSaleReceipt->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date')}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Time')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                            <input type="time" name="time" class="form-control" id="time"
                                   value="{{old('time',  isset($returnedSaleReceipt) ? $returnedSaleReceipt->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>

                <div class="col-md-4 ">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Type')}}</label>
                        <div class="input-group">

                            <span class="input-group-addon fa fa-file-text-o"></span>

                            <select class="form-control js-example-basic-single" name="type" id="type" onchange="selectReturnedType()">

                                <option value="">{{__('Select')}}</option>

{{--                                @foreach($data['supply_orders'] as $supply_order)--}}
                                    <option value="from_invoice"
                                        {{isset($returnedSaleReceipt) && $returnedSaleReceipt->type == 'from_invoice'? 'selected':''}}>
                                        {{__('From Invoice')}}
                                    </option>

                                    <option value="from_sale_quotation"
                                        {{isset($returnedSaleReceipt) && $returnedSaleReceipt->type == 'from_sale_quotation'? 'selected':''}}>
                                        {{__('From Sale Quotation')}}
                                    </option>

                                    <option value="from_sale_supply_order"
                                        {{isset($returnedSaleReceipt) && $returnedSaleReceipt->type == 'from_sale_supply_order'? 'selected':''}}>
                                        {{__('From Invoice')}}
                                    </option>
{{--                                @endforeach--}}

                            </select>
                        </div>
                        {{input_error($errors,'type')}}
                    </div>
                </div>



                <div class="col-md-4">
                    <div class="form-group has-feedback" id="returned_type_items">
                        <label for="inputStore" class="control-label">{{__('sasa')}}</label>
                        <div class="input-group">

                            <span class="input-group-addon fa fa-file-text-o"></span>

                            <select class="form-control js-example-basic-single" name="salesable_id"
                                    id="salesable_id" onchange="selectSupplyOrder()">

                                <option value="">{{__('Select')}}</option>

{{--                                @foreach($data['supply_orders'] as $supply_order)--}}
{{--                                    <option value="{{$supply_order->id}}"--}}
{{--                                        {{isset($purchaseReceipt) && $purchaseReceipt->supply_order_id == $supply_order->id? 'selected':''}}>--}}
{{--                                        {{$supply_order->number}} - {{ optional($supply_order->supplier)->name }}--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}

                            </select>
                        </div>
                        {{input_error($errors,'supply_order_id')}}
                    </div>
                </div>

            </div>
        </div>

        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            @include('admin.purchase_receipts.table_items')

        </div>


        <div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

            <table class="table table-bordered">
                <tbody>
                <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{__('Total receipts')}}</label>
                <td style="background:#FFC5D7">
                    <input type="text" disabled id="total" style="background:#FFC5D7; border:none;text-align:center !important;" class="form-control"
                           value="{{isset($purchaseReceipt) ? $purchaseReceipt->total : 0}}">
                </td>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Accepted')}}</label>
                <td style="background:#F9EFB7">
                    <input type="text" disabled id="total_accepted" style="background:#F9EFB7;border:none;text-align:center !important;"
                           value="{{isset($purchaseReceipt) ? $purchaseReceipt->total_accepted : 0}}" class="form-control">
                </td>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Rejected')}}</label>
                <td style="background:#F9EFB7">
                    <input type="text" disabled id="total_rejected" style="background:#F9EFB7;border:none;text-align:center !important;"
                           value="{{isset($purchaseReceipt) ? $purchaseReceipt->total_rejected : 0}}" class="form-control">
                </td>
                </tbody>
            </table>

            <table class="table table-bordered">
                <tbody>
                <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</label>
                <td style="background:#D2F4F6">
                <textarea name="notes" style="background:#D2F4F6;border:none;" class="form-control" rows="4"
                          cols="150"
                >{{old('description', isset($purchaseReceipt) ? $purchaseReceipt->notes : '')}}</textarea>

                    {{input_error($errors,'description')}}
                </td>
                </tbody>
            </table>

        </div>

    </div>
</div>
