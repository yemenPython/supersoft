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
                                        onchange="changeBranch()" {{isset($saleSupplyOrder) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($data['branches'] as $branch)
                                        <option value="{{$branch->id}}"
                                            {{isset($saleSupplyOrder) && $saleSupplyOrder->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                            @if(isset($saleSupplyOrder))
                                <input type="hidden" name="branch_id" value="{{$saleSupplyOrder->branch_id}}">
                            @endif
                        </div>

                    </div>
                </div>
            @endif

            <div class="col-md-12">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                            <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}"
                                   value="{{old('number', isset($saleSupplyOrder)? $saleSupplyOrder->number :'')}}">
                        </div>
                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Type')}}</label>

                        <div class="input-group">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="type" id="quotation_type"
                                    onchange="quotationType()">

                                <option value="from_sale_quotation"
                                    {{isset($saleSupplyOrder) && $saleSupplyOrder->type == 'from_sale_quotation'? 'selected':'' }}>
                                    {{ __('From Sale Quotation') }}
                                </option>

                                <option value="normal"
                                    {{isset($saleSupplyOrder) && $saleSupplyOrder->type == 'normal'? 'selected':'' }}>
                                    {{ __('Normal Sale Supply Order') }}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'type')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date" class="form-control datepicker" id="date"
                                   value="{{old('date', isset($saleSupplyOrder) ? $saleSupplyOrder->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Time')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                            <input type="time" name="time" class="form-control" id="time"
                                   value="{{old('time',  isset($saleSupplyOrder) ? $saleSupplyOrder->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>
            </div>


            <div class="col-md-12">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Period of sale supply order from')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="supply_date_from" class="form-control datepicker" id="date_from"
                                   onchange="getDate()"
                                   value="{{old('date_from', isset($saleSupplyOrder)? $saleSupplyOrder->supply_date_from : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'supplydate_from')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Period of sale supply order to')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="supply_date_to" class="form-control datepicker" id="date_to"
                                   onchange="getDate()"
                                   value="{{old('date_to', isset($saleSupplyOrder)? $saleSupplyOrder->supply_date_to : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'supply_date_to')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('supply order days')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" class="form-control" id="different_days" disabled
                                   value="{{isset($saleSupplyOrder) ? $saleSupplyOrder->different_days : 0}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Remaining Days')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" class="form-control" id="remaining_days" disabled
                                   value="{{isset($saleSupplyOrder) ? $saleSupplyOrder->remaining_days : 0}}">
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-12">

                <div class="col-md-3 sale_quotation_type"
                     style="{{isset($saleSupplyOrder) && $saleSupplyOrder->type != 'from_sale_quotation'? 'display:none':''}}">
                    <div class="form-group">

                        <div class="input-group">
                            <label style="opacity:0">{{__('select')}}</label>
                            <ul class="list-inline" style="display:flex">

                                <li class="col-md-6">
                                    <button type="button" class="btn btn-new2 waves-effect waves-light btn-xs"
                                            data-toggle="modal" data-target="#purchase_quotations"
                                            style="margin-right: 10px;">
                                        <i class="fa fa-file-text-o"></i>
                                        {{__('Show Sale Quotations')}}
                                    </button>
                                <li>
                            </ul>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Customer name')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-user"></span>

                            <select class="form-control js-example-basic-single" name="customer_id" id="customer_id"
                                    onchange="quotationType(); selectSupplier()">
                                <option value="">{{__('Select')}}</option>

                                @foreach($data['customers'] as $customer)
                                    <option value="{{$customer->id}}"

                                            data-discount="{{$customer->group_sales_discount}}"
                                            data-discount-type="{{$customer->group_sales_discount_type}}"

                                        {{isset($saleSupplyOrder) && $saleSupplyOrder->customer_id == $customer->id? 'selected':''}}>
                                        {{$customer->name}}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{input_error($errors,'customer_id')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Status')}}</label>
                        <div class="input-group">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="status">

                                <option value="pending"
                                    {{isset($saleSupplyOrder) && $saleSupplyOrder->status == 'pending'? 'selected':'' }}>
                                    {{ __('pending') }}
                                </option>

                                <option value="processing"
                                    {{isset($saleSupplyOrder) && $saleSupplyOrder->status == 'processing' ? 'selected':''}}>
                                    {{ __('Processing') }}
                                </option>

                                <option value="finished"
                                    {{isset($saleSupplyOrder) && $saleSupplyOrder->status == 'finished' ? 'selected':''}}>
                                    {{ __('Finished') }}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'status')}}
                    </div>
                </div>

            </div>
        </div>


        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            <div class="col-md-4 out_sale_quotations_type"
                 style="{{isset($saleSupplyOrder) && $saleSupplyOrder->type == 'from_sale_quotation'? 'display:none':''}}
                 {{!isset($saleSupplyOrder) ? 'display:none':''}}">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label text-new1">{{__('Main Types')}}</label>
                    <div class="input-group" id="main_types">

                        <span class="input-group-addon fa fa-cubes"></span>

                        <select class="form-control js-example-basic-single" id="main_types_select"
                                onchange="dataByMainType()">

                            <option value="">{{__('Select Type')}}</option>

                            @foreach($data['mainTypes'] as $key => $type)
                                <option value="{{$type->id}}" data-order="{{$key+1}}">
                                    {{$key+1}} . {{$type->type}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4 out_sale_quotations_type"
                 style="{{isset($saleSupplyOrder) && $saleSupplyOrder->type == 'from_sale_quotation'? 'display:none':''}}
                 {{!isset($saleSupplyOrder) ? 'display:none':''}}">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label text-new1">{{__('Sub Types')}}</label>
                    <div class="input-group" id="sub_types">

                        <span class="input-group-addon fa fa-cube"></span>

                        <select class="form-control js-example-basic-single" id="sub_types_select"
                                onchange="dataBySubType()">

                            <option value="">{{__('Select Type')}}</option>

                            @foreach($data['subTypes'] as $id=>$type)
                                <option value="{{$id}}">
                                    {{$type}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4 out_sale_quotations_type"
                 style="{{isset($saleSupplyOrder) && $saleSupplyOrder->type == 'from_sale_quotation'? 'display:none':''}}
                 {{!isset($saleSupplyOrder) ? 'display:none':''}}">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label text-new1">{{__('Parts')}}</label>
                    <div class="input-group" id="parts">

                        <span class="input-group-addon fa fa-gears"></span>

                        <select class="form-control js-example-basic-single" id="parts_select" onchange="selectPart()">

                            <option value="">{{__('Select Part')}}</option>

                            @foreach($data['parts'] as $part)
                                <option value="{{$part->id}}">
                                    {{$part->Prices->first() ? $part->Prices->first()->barcode . ' - ' : ''}} {{$part->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            @include('admin.sale_supply_orders.table_items')

        </div>

        <div class="row bottom-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">
            @include('admin.sale_supply_orders.financial_details')
        </div>
    </div>

    <table style="display: none" >

        @foreach( $data['saleQuotations'] as $saleQuotation)

            <tr>
                <td>
                    <input type="checkbox" name="sale_quotations[]" value="{{$saleQuotation->id}}"
                           onclick="selectSaleQuotation('{{$saleQuotation->id}}')"
                           class="real_sale_quotation_box_{{$saleQuotation->id}}"
                        {{isset($saleSupplyOrder) && in_array($saleQuotation->id, $saleSupplyOrder->saleQuotations->pluck('id')->toArray()) ? 'checked':'' }}
                    >
                </td>
                <td>
                    <span>{{$saleQuotation->number}}</span>
                </td>
                <td>
                    <span>{{optional($saleQuotation->customer)->name}}</span>
                </td>
            </tr>

        @endforeach


    </table>

</div>
