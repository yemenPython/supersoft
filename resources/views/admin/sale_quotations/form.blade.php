<div class="row">
    <div class="col-xs-12">

        <div class="row top-data-wg for-error-margin-group" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            @if(authIsSuperAdmin())
                <div class="col-md-12">
                    <div class="">
                        <div class="form-group has-feedback">
                            <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                            <div class="input-group">

                                <span class="input-group-addon fa fa-file"></span>

                                <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                        onchange="changeBranch()" {{isset($saleQuotation) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($branches as $branch)
                                        <option
                                            value="{{$branch->id}}"
                                            {{isset($saleQuotation) && $saleQuotation->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                            @if(isset($saleQuotation))
                                <input type="hidden" name="branch_id" value="{{$saleQuotation->branch_id}}">
                            @endif
                        </div>

                    </div>
                </div>
            @endif

            <div class="">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputNameAr" class="control-label">{{__('Quotation Number')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                            <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}"
                                   value="{{old('number', isset($saleQuotation)? $saleQuotation->number :'')}}">
                        </div>
                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="date" name="date" class="form-control" id="date"
                                   value="{{old('date', isset($saleQuotation) ? $saleQuotation->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                                   value="{{old('time',  isset($saleQuotation) ? $saleQuotation->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Status')}}</label>
                        <div class="input-group">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="status">

                            <!-- <option value="">{{__('Select Status')}}</option> -->

                                <option value="pending"
                                    {{isset($saleQuotation) && $saleQuotation->status == 'pending'? 'selected':'' }}>
                                    {{ __('Pending') }}
                                </option>

                                <option value="processing"
                                    {{isset($saleQuotation) && $saleQuotation->status == 'processing' ? 'selected':''}}>
                                    {{ __('Processing') }}
                                </option>

                                <option value="finished"
                                    {{isset($saleQuotation) && $saleQuotation->status == 'finished' ? 'selected':''}}>
                                    {{ __('Finished') }}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'status')}}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Customer name')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-user"></span>
                            <select class="form-control js-example-basic-single" name="customer_id" id="supplier_id"
                                    onchange="selectSupplier()">
                                <option value="">{{__('Select')}}</option>

                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}"
                                            data-discount="{{$customer->group_sales_discount}}"
                                            data-discount-type="{{$customer->group_sales_discount_type}}"
                                        {{isset($saleQuotation) && $saleQuotation->customer_id == $customer->id? 'selected':''}}>
                                        {{$customer->name}}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{input_error($errors,'customer_id')}}

                    </div>

                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Supply Date From')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="date" name="supply_date_from" class="form-control" id="supply_date_from"
                                   value="{{old('supply_date_from',  isset($saleQuotation) ? $saleQuotation->supply_date_from : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'supply_date_from')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Supply Date To')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="date" name="supply_date_to" class="form-control" id="supply_date_to"
                                   value="{{old('supply_date_to',  isset($saleQuotation) ? $saleQuotation->supply_date_to : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'supply_date_to')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Period of quotation from')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="date" name="date_from" class="form-control" id="date_from" onchange="getDate()"
                                   value="{{old('date_from', isset($saleQuotation)? $saleQuotation->date_from : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date_from')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Period of quotation to')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="date" name="date_to" class="form-control" id="date_to" onchange="getDate()"
                                   value="{{old('date_to', isset($saleQuotation)? $saleQuotation->date_to : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date_to')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('quotation days')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" class="form-control" id="different_days" disabled
                                   value="{{isset($saleQuotation) ? $saleQuotation->different_days : 0}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Remaining Days')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" class="form-control" id="remaining_days" disabled
                                   value="{{isset($saleQuotation) ? $saleQuotation->remaining_days : 0}}">
                        </div>
                    </div>
                </div>
<div class="col-md-4">
<label style="display:block">{{__('Quotation type')}}</label>
                <div class="col-xs-4" style="padding:0">
                    <div class="radio primary ">

                        <input type="radio" name="type" value="cash" id="cash"
                            {{ !isset($saleQuotation) ? 'checked':'' }}
                            {{isset($saleQuotation) && $saleQuotation->type == 'cash' ? 'checked':''}} >
                        <label for="cash">{{__('Cash')}}</label>
                    </div>
                </div>

                <div class="col-xs-4" style="padding:0">

                    <div class="radio primary ">

                        <input type="radio" name="type" id="credit" value="credit"
                            {{isset($saleQuotation) && $saleQuotation->type == 'credit' ? 'checked':''}} >
                        <label for="credit">{{__('Credit')}}</label>
                    </div>
                </div>
                </div>
            </div>
        </div>


        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            <div class="col-md-4 ">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label text-new1">{{__('Main Types')}}</label>
                    <div class="input-group" id="main_types">

                        <span class="input-group-addon fa fa-cubes"></span>

                        <select class="form-control js-example-basic-single" id="main_types_select"
                                onchange="dataByMainType()">

                            <option value="">{{__('Select Type')}}</option>

                            @foreach($mainTypes as $key => $type)
                                <option value="{{$type->id}}" data-order="{{$key+1}}">
                                    {{$key+1}} . {{$type->type}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4 ">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label text-new1">{{__('Sub Types')}}</label>
                    <div class="input-group" id="sub_types">

                        <span class="input-group-addon fa fa-cube"></span>

                        <select class="form-control js-example-basic-single" id="sub_types_select"
                                onchange="dataBySubType()">

                            <option value="">{{__('Select Type')}}</option>

                            @foreach($subTypes as $id=>$type)
                                <option value="{{$id}}">
                                    {{$type}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-4 ">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label text-new1">{{__('Parts')}}</label>
                    <div class="input-group" id="parts">

                        <span class="input-group-addon fa fa-gears"></span>

                        <select class="form-control js-example-basic-single" id="parts_select" onchange="selectPart()">

                            <option value="">{{__('Select Part')}}</option>

                            @foreach($parts as $part)
                                <option value="{{$part->id}}">
                                    {{$part->Prices->first() ? $part->Prices->first()->barcode . ' - ' : ''}} {{$part->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            @include('admin.sale_quotations.table_items')

        </div>
    </div>
</div>

    <div class="row bottom-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    @include('admin.sale_quotations.financial_details')

</div>


