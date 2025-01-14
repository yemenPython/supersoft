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
                                        onchange="changeBranch()" {{isset($purchaseQuotation) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($branches as $branch)
                                        <option
                                            value="{{$branch->id}}"
                                            {{isset($purchaseQuotation) && $purchaseQuotation->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                            @if(isset($purchaseQuotation))
                                <input type="hidden" name="branch_id" value="{{$purchaseQuotation->branch_id}}">
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
                                   value="{{old('number', isset($purchaseQuotation)? $purchaseQuotation->number :'')}}">
                        </div>
                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Type')}}</label>

                        <div class="input-group" style="{{request()->query('quotation') ? 'display:none':'' }}">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="type" id="quotation_type"
                                    onchange="quotationType()">

                                <option value="from_purchase_request"
                                    {{isset($purchaseQuotation) && $purchaseQuotation->type == 'from_purchase_request'? 'selected':'' }}>
                                    {{ __('From Purchase Request') }}
                                </option>

                                <option value="out_purchase_request"
                                    {{isset($purchaseQuotation) && $purchaseQuotation->type == 'out_purchase_request'? 'selected':'' }}>
                                    {{ __('Out Purchase Request') }}
                                </option>

                            </select>
                        </div>

                        <div class="input-group"
                             style="{{request()->query('quotation') ? '':'display:none'}}">
                            <input type="text" class="form-control" disabled value="{{ __('From Purchase Request') }}">
                        </div>


                        {{input_error($errors,'type')}}
                    </div>
                </div>

                <div class="col-md-3 purchase_request_type"
                     style="{{isset($purchaseQuotation) && $purchaseQuotation->type != 'from_purchase_request'? 'display:none':''}}">
                    <div class="form-group has-feedback">

                        <label for="inputStore" class="control-label">{{__('Purchase Requests')}}</label>

                        <div class="input-group" style="{{request()->query('quotation') ? 'display:none':'' }}">

                            <span class="input-group-addon fa fa-file-text-o"></span>

                            <select class="form-control js-example-basic-single" name="purchase_request_id"
                                    id="purchase_request_id" onchange="selectPurchaseRequest()">

                                <option value="" >
                                    {{__('Select')}}
                                </option>

                                @foreach($purchaseRequests as $purchaseRequest)
                                    <option value="{{$purchaseRequest->id}}"
                                        {{isset($purchaseQuotation) && $purchaseQuotation->purchase_request_id == $purchaseRequest->id? 'selected':''}}>
                                        {{$purchaseRequest->special_number}}
                                    </option>
                                @endforeach

                            </select>
                        </div>


                        <div class="input-group"
                             style="{{request()->query('quotation') ? '':'display:none'}}">
                            <input type="text" class="form-control" disabled id="disabled_purchase_request">
                        </div>

                        {{input_error($errors,'purchase_request_id')}}
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date" class="form-control datepicker" id="date"
                                   {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                                   value="{{old('date', isset($purchaseQuotation) ? $purchaseQuotation->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                                   {{isset($request_type) && $request_type == 'approval' ? 'disabled' : ''}}
                                   value="{{old('time',  isset($purchaseQuotation) ? $purchaseQuotation->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Suppliers')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-user"></span>
                            <select class="form-control js-example-basic-single" name="supplier_id" id="supplier_id"
                                    onchange="selectSupplier()">
                                <option value="">{{__('Select')}}</option>

                                @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}"
                                            data-discount="{{$supplier->group_discount}}"
                                            data-discount-type="{{$supplier->group_discount_type}}"
                                        {{isset($purchaseQuotation) && $purchaseQuotation->supplier_id == $supplier->id? 'selected':''}}>
                                        {{$supplier->name}}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{input_error($errors,'supplier_id')}}

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
                                    {{isset($purchaseQuotation) && $purchaseQuotation->status == 'pending'? 'selected':'' }}>
                                    {{ __('processing') }}
                                </option>

                                <option value="accepted"
                                    {{isset($purchaseQuotation) && $purchaseQuotation->status == 'accepted' ? 'selected':''}}>
                                    {{ __('Accepted') }}
                                </option>

                                <option value="rejected"
                                    {{isset($purchaseQuotation) && $purchaseQuotation->status == 'rejected' ? 'selected':''}}>
                                    {{ __('Rejected') }}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'status')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Supply Date From')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="supply_date_from" class="form-control datepicker" id="supply_date_from"
                                   value="{{old('supply_date_from',  isset($purchaseQuotation) ? $purchaseQuotation->supply_date_from : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'supply_date_from')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Supply Date To')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="supply_date_to" class="form-control datepicker" id="supply_date_to"
                                   value="{{old('supply_date_to',  isset($purchaseQuotation) ? $purchaseQuotation->supply_date_to : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'supply_date_to')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Period of quotation from')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date_from" class="form-control datepicker" id="date_from" onchange="getDate()"
                                   value="{{old('date_from', isset($purchaseQuotation)? $purchaseQuotation->date_from : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date_from')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Period of quotation to')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date_to" class="form-control datepicker" id="date_to" onchange="getDate()"
                                   value="{{old('date_to', isset($purchaseQuotation)? $purchaseQuotation->date_to : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                                   value="{{isset($purchaseQuotation) ? $purchaseQuotation->different_days : 0}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Remaining Days')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" class="form-control" id="remaining_days" disabled
                                   value="{{isset($purchaseQuotation) ? $purchaseQuotation->remaining_days : 0}}">
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                <label for="date" class="control-label">{{__('Quotation type')}}</label>
                <div class="form-group">
                <div class="col-xs-4">
                    <div class="radio primary ">
                        <input type="radio" name="quotation_type" value="cash" id="cash"
                            {{ !isset($purchaseQuotation) ? 'checked':'' }}
                            {{isset($purchaseQuotation) && $purchaseQuotation->quotation_type == 'cash' ? 'checked':''}} >
                        <label for="cash">{{__('Cash')}}</label>
                    </div>
                </div>


                <div class="col-xs-4">

                    <div class="radio primary ">

                        <input type="radio" name="quotation_type" id="credit" value="credit"
                            {{isset($purchaseQuotation) && $purchaseQuotation->quotation_type == 'credit' ? 'checked':''}} >
                        <label for="credit">{{__('Credit')}}</label>
                    </div>
                </div>

                </div>
            </div>

            </div>

        </div>


        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            <div class="col-md-4 out_purchase_request_type"
                 style="{{isset($purchaseQuotation) && $purchaseQuotation->type == 'from_purchase_request'? 'display:none':''}}
                 {{!isset($purchaseQuotation) ? 'display:none':''}}">
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

            <div class="col-md-4 out_purchase_request_type"
                 style="{{isset($purchaseQuotation) && $purchaseQuotation->type == 'from_purchase_request'? 'display:none':''}}
                 {{!isset($purchaseQuotation) ? 'display:none':''}}">
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

            <div class="col-md-4 out_purchase_request_type"
                 style="{{isset($purchaseQuotation) && $purchaseQuotation->type == 'from_purchase_request'? 'display:none':''}}
                 {{!isset($purchaseQuotation) ? 'display:none':''}}">
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

            @include('admin.purchase_quotations.table_items')

        </div>
    </div>
</div>
;
    <div class="row bottom-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    @include('admin.purchase_quotations.financial_details')

</div>


