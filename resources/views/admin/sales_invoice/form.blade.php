<div class="row">
    <div class="col-xs-12">

        <div class="row top-data-wg for-error-margin-group"
             style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            @if(authIsSuperAdmin())
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                            <div class="input-group">

                                <span class="input-group-addon fa fa-file"></span>

                                <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                        onchange="changeBranch()" {{isset($purchaseInvoice) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($data['branches'] as $branch)
                                        <option value="{{$branch->id}}"
                                            {{isset($saleInvoice) && $saleInvoice->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                            @if(isset($saleInvoice))
                                <input type="hidden" name="branch_id" value="{{$saleInvoice->branch_id}}">
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
                            <input type="text" name="invoice_number" class="form-control" placeholder="{{__('Number')}}"
                                   value="{{old('invoice_number', isset($saleInvoice)? $saleInvoice->number :'')}}">
                        </div>

                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Type')}}</label>

                        <div class="input-group">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="invoice_type" id="quotation_type"
                                    onchange="changeType()">

                                <option value="normal"
                                    {{isset($saleInvoice) && $saleInvoice->invoice_type == 'normal'? 'selected':'' }}>
                                    {{ __('Normal sale invoice') }}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'invoice_type')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="date" name="date" class="form-control" id="date"
                                   value="{{old('date', isset($saleInvoice) ? $saleInvoice->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                                   value="{{old('time',  isset($saleInvoice) ? $saleInvoice->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>

{{--                <div class="col-md-6 purchase_request_type"--}}
{{--                     style="{{isset($purchaseInvoice) && $purchaseInvoice->invoice_type != 'from_supply_order'? 'display:none':''}}">--}}
{{--                    <div class="form-group">--}}


{{--                        <div class="input-group">--}}
{{--                            <label style="opacity:0">{{__('select')}}</label>--}}
{{--                            <ul class="list-inline" style="display:flex">--}}
{{--                                <li class="col-md-6">--}}
{{--                                    <button type="button" onclick="getPurchaseReceipts(); changeType()"--}}
{{--                                            class="btn btn-new1 waves-effect waves-light btn-xs">--}}
{{--                                        <i class="fa fa-file-text-o"></i>--}}
{{--                                        {{__('Get Purchase Receipt')}}--}}
{{--                                    </button>--}}
{{--                                </li>--}}

{{--                                <li class="col-md-6">--}}
{{--                                    <button type="button" class="btn btn-new2 waves-effect waves-light btn-xs"--}}
{{--                                            data-toggle="modal" data-target="#purchase_receipts"--}}
{{--                                            style="margin-right: 10px;">--}}
{{--                                        <i class="fa fa-file-text-o"></i>--}}
{{--                                        {{__('Show selected Receipts')}}--}}
{{--                                    </button>--}}
{{--                                <li>--}}
{{--                            </ul>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}


                <div class="col-md-6">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Suppliers')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-user"></span>

                            <select class="form-control js-example-basic-single" name="supplier_id" id="supplier_id"
                                    onchange="selectSupplier()">
                                <option value="">{{__('Select')}}</option>

                                @foreach($data['suppliers'] as $supplier)
                                    <option value="{{$supplier->id}}"
                                            data-discount="{{$supplier->group_discount}}"
                                            data-discount-type="{{$supplier->group_discount_type}}"
                                        {{isset($saleInvoice) && $saleInvoice->supplier_id == $supplier->id? 'selected':''}}>
                                        {{$supplier->name}}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{input_error($errors,'supplier_id')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <label style="display:block">{{__('Quotation type')}}</label>

                    <div class="col-md-6" style="padding:0">

                        <div class="radio primary ">

                            <input type="radio" name="type" value="cash" id="cash"
                                {{ !isset($saleInvoice) ? 'checked':'' }}
                                {{isset($saleInvoice) && $saleInvoice->type == 'cash' ? 'checked':''}} >
                            <label for="cash">{{__('Cash')}}</label>
                        </div>
                    </div>

                    <div class="col-md-6" style="padding:0">

                        <div class="radio primary ">

                            <input type="radio" name="type" id="credit" value="credit"
                                {{isset($saleInvoice) && $saleInvoice->type == 'credit' ? 'checked':''}} >
                            <label for="credit">{{__('Credit')}}</label>
                        </div>
                    </div>

                </div>

                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Status')}}</label>
                        <div class="input-group">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="status">

                                <option value="pending"
                                    {{isset($saleInvoice) && $saleInvoice->status == 'pending'? 'selected':'' }}>
                                    {{ __('processing') }}
                                </option>

                                <option value="accept"
                                    {{isset($saleInvoice) && $saleInvoice->status == 'accept' ? 'selected':''}}>
                                    {{ __('Accept') }}
                                </option>

                                <option value="reject"
                                    {{isset($saleInvoice) && $saleInvoice->status == 'reject' ? 'selected':''}}>
                                    {{ __('Reject') }}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'status')}}
                    </div>
                </div>

            </div>
        </div>

        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            <div class="col-md-4 out_purchase_request_type"
                 style="{{isset($saleInvoice) && $saleInvoice->invoice_type == 'from_supply_order'? 'display:none':''}}">
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

            <div class="col-md-4 out_purchase_request_type"
                 style="{{isset($saleInvoice) && $saleInvoice->invoice_type == 'from_supply_order'? 'display:none':''}}"
            >
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

            <div class="col-md-4 out_purchase_request_type"
                 style="{{isset($saleInvoice) && $saleInvoice->invoice_type == 'from_supply_order'? 'display:none':''}}">
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

            @include('admin.sales_invoice.table_items')

        </div>

        <div class="row bottom-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            @include('admin.sales_invoice.financial_details')

        </div>
    </div>
</div>
