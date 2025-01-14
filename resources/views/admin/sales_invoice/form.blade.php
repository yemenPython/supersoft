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
                                        onchange="changeBranch()" {{isset($salesInvoice) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($data['branches'] as $branch)
                                        <option value="{{$branch->id}}"
                                            {{isset($salesInvoice) && $salesInvoice->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                            @if(isset($salesInvoice))
                                <input type="hidden" name="branch_id" value="{{$salesInvoice->branch_id}}">
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
                            <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}" disabled
                                   value="{{old('number', isset($salesInvoice)? $salesInvoice->number : $data['number'] )}}">
                        </div>

                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Type')}}</label>

                        <div class="input-group"
                             style="{{!request()->query('quotations') && !request()->query('type') && !request()->query('invoice_type')  && !request()->query('orders')  ? '':'display:none'}}">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="invoice_type" id="invoice_type"
                                    onchange="changeType()">

                                {{-- with concession --}}
                                <option value="normal"
                                    {{isset($salesInvoice) && $salesInvoice->invoice_type == 'normal'? 'selected':'' }}>
                                    {{ __('Normal sale invoice') }}
                                </option>

                                {{-- without concession --}}
                                <option value="direct_invoice"
                                    {{isset($salesInvoice) && $salesInvoice->invoice_type == 'direct_invoice'? 'selected':'' }}>
                                    {{ __('Direct Invoice') }}
                                </option>

                                <option value="direct_sale_quotations"
                                    {{isset($salesInvoice) && $salesInvoice->invoice_type == 'direct_sale_quotations'? 'selected':'' }}>
                                    {{ __('Direct Sale Quotations') }}
                                </option>

                                <option value="from_sale_quotations"
                                    {{isset($salesInvoice) && $salesInvoice->invoice_type == 'from_sale_quotations'? 'selected':'' }}>
                                    {{ __('From Sale Quotations') }}
                                </option>

                                <option value="from_sale_supply_order"
                                    {{isset($salesInvoice) && $salesInvoice->invoice_type == 'from_sale_supply_order'? 'selected':'' }}>
                                    {{ __('From Sale Supply Order') }}
                                </option>

                            </select>
                        </div>

                        <div class="input-group"
                             style="{{request()->query('quotations') || request()->query('orders')  && (request()->query('type') && request()->query('invoice_type')) ? '':'display:none'}}">
                            <input type="text" id="disabled_invoice_type" class="form-control" disabled>
                        </div>

                        {{input_error($errors,'invoice_type')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date" class="form-control datepicker" id="date"
                                   value="{{old('date', isset($salesInvoice) ? $salesInvoice->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                                   value="{{old('time',  isset($salesInvoice) ? $salesInvoice->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>

                <div class="col-md-3"
                     style="{{!request()->query('quotations') && !request()->query('type') && !request()->query('invoice_type') && !request()->query('orders') ? '':'display:none'}}">

                    <label style="display:block">{{__('Invoice type form')}}</label>

                    <div class="col-md-6" style="padding:0">

                        <div class="radio primary ">

                            <input type="radio" name="type_for" value="customer" id="customer_radio"
                                   onclick="changeTypeFor()"
                                {{ !isset($salesInvoice) ? 'checked':'' }}
                                {{isset($salesInvoice) && $salesInvoice->type_for == 'customer' ? 'checked':''}} >
                            <label for="customer_radio">{{__('Customer')}}</label>
                        </div>
                    </div>

                    <div class="col-md-6" style="padding:0">

                        <div class="radio primary ">

                            <input type="radio" name="type_for" id="supplier_radio" value="supplier"
                                   onclick="changeTypeFor()"
                                {{isset($salesInvoice) && $salesInvoice->type_for == 'supplier' ? 'checked':''}} >
                            <label for="supplier_radio">{{__('Supplier')}}</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-3"
                     style="{{request()->query('quotations') || request()->query('orders') && (request()->query('type') && request()->query('invoice_type')) ? '':'display:none'}}">

                    <label style="display:block">{{__('Invoice type form')}}</label>

                    <div class="input-group">
                        <input type="text" id="disabled_type_for" class="form-control" disabled>
                    </div>
                </div>


                <div class="col-md-3" id="suppliers_data"
                     style="{{!isset($salesInvoice) || (isset($salesInvoice) && $salesInvoice->type_for == 'customer')? 'display:none;':'' }}">
                    <div class="form-group has-feedback">

                        <label for="inputStore" class="control-label">{{__('Suppliers')}}</label>

                        <div class="input-group"
                             style="{{!request()->query('quotations') && !request()->query('type') && !request()->query('invoice_type') && !request()->query('orders') ? '':'display:none'}}">

                            <span class="input-group-addon fa fa-user"></span>

                            <select
                                class="form-control js-example-basic-single {{(isset($salesInvoice) && $salesInvoice->type_for == 'supplier' ? 'client_select':'')}}"
                                name="salesable_id" id="supplier_id" onchange="selectClient()"
                                {{!isset($salesInvoice) || (isset($salesInvoice) && $salesInvoice->type_for == 'customer') ? 'disabled':''}}>

                                <option value="">{{__('Select')}}</option>

                                @foreach($data['suppliers'] as $supplier)
                                    <option value="{{$supplier->id}}"
                                            data-discount="{{$supplier->group_discount}}"
                                            data-discount-type="{{$supplier->group_discount_type}}"
                                        {{isset($salesInvoice) && $salesInvoice->type_for == 'supplier' && $salesInvoice->salesable_id == $supplier->id? 'selected':''}}>
                                        {{$supplier->name}}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="input-group"
                             style="{{request()->query('quotations') || request()->query('orders') &&  (request()->query('type') && request()->query('invoice_type')) ? '':'display:none'}}">
                            <input type="text" id="disabled_supplier_name" class="form-control" disabled>
                        </div>

                        {{input_error($errors,'salesable_id')}}
                    </div>
                </div>

                <div class="col-md-3" id="customers_data" style="{{ (isset($salesInvoice) && $salesInvoice->type_for != 'customer')? 'display:none;':'' }}">
                    <div class="form-group has-feedback">

                        <label for="inputStore" class="control-label">{{__('Customer name')}}</label>

                        <div class="input-group"
                             style="{{!request()->query('quotations') && !request()->query('type') && !request()->query('invoice_type') ? '':'display:none'}}">

                            <span class="input-group-addon fa fa-user"></span>

                            <select
                                class="form-control js-example-basic-single {{(isset($salesInvoice) && $salesInvoice->type_for != 'customer' ? '':'client_select')}}"
                                onchange="selectClient()" name="salesable_id" id="customer_id"
                                {{(isset($salesInvoice) && $salesInvoice->type_for != 'customer')? 'disabled':''}}>
                                <option value="">{{__('Select')}}</option>

                                @foreach($data['customers'] as $customer)
                                    <option value="{{$customer->id}}"

                                            data-discount="{{$customer->group_sales_discount}}"
                                            data-discount-type="{{$customer->group_sales_discount_type}}"

                                        {{isset($salesInvoice) && $salesInvoice->type_for == 'customer' && $salesInvoice->salesable_id == $customer->id? 'selected':''}}>
                                        {{$customer->name}}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        <div class="input-group"
                             style="{{request()->query('quotations') || request()->query('orders') && (request()->query('type') && request()->query('invoice_type')) ? '':'display:none'}}">
                            <input type="text" id="disabled_customer_name" class="form-control" disabled>
                        </div>

                        {{input_error($errors,'salesable_id')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <label style="display:block">{{__('Invoice type')}}</label>

                    <div class="col-md-6" style="padding:0">

                        <div class="radio primary ">

                            <input type="radio" name="type" value="cash" id="cash"
                                {{ !isset($salesInvoice) ? 'checked':'' }}
                                {{isset($salesInvoice) && $salesInvoice->type == 'cash' ? 'checked':''}} >
                            <label for="cash">{{__('Cash')}}</label>
                        </div>
                    </div>

                    <div class="col-md-6" style="padding:0">

                        <div class="radio primary ">

                            <input type="radio" name="type" id="credit" value="credit"
                                {{isset($salesInvoice) && $salesInvoice->type == 'credit' ? 'checked':''}} >
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
                                    {{isset($salesInvoice) && $salesInvoice->status == 'pending'? 'selected':'' }}>
                                    {{ __('pending') }}
                                </option>

                                <option value="processing"
                                    {{isset($salesInvoice) && $salesInvoice->status == 'processing' ? 'selected':''}}>
                                    {{ __('Processing') }}
                                </option>

                                <option value="finished"
                                    {{isset($salesInvoice) && $salesInvoice->status == 'finished' ? 'selected':''}}>
                                    {{ __('Finished') }}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'status')}}
                    </div>
                </div>

                <div class="col-md-3 un_normal direct_sale_quotations"
                     style="{{!isset($salesInvoice) || isset($salesInvoice) && !in_array($salesInvoice->invoice_type,['direct_sale_quotations', 'from_sale_quotations']) ? 'display:none':''}}">
                    <div class="form-group">

                        <div class="input-group">
                            <label style="opacity:0">{{__('select')}}</label>
                            <ul class="list-inline" style="display:flex">

                                <li class="col-md-6">
                                    <button type="button" class="btn btn-new2 waves-effect waves-light btn-xs"
                                            data-toggle="modal"
                                            data-target="#purchase_quotations"
                                            onclick="{{request()->query('quotations') && request()->query('invoice_type') ? '':'getSaleQuotations()' }}"
                                            style="margin-right: 10px;">
                                        <i class="fa fa-file-text-o"></i>
                                        {{__('Show Sale Quotations')}}
                                    </button>
                                <li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 un_normal from_sale_supply_order" style="{{ isset($salesInvoice) && $salesInvoice->invoice_type == 'from_sale_supply_order'? '':'display:none'}}">
                    <div class="form-group">

                        <div class="input-group">
                            <label style="opacity:0">{{__('select')}}</label>
                            <ul class="list-inline" style="display:flex">

                                <li class="col-md-6">
                                    <button type="button" class="btn btn-new2 waves-effect waves-light btn-xs"
                                            data-toggle="modal"
                                            data-target="#sale_supply_order"
                                            onclick="{{request()->query('orders') && request()->query('invoice_type') ? '':'getSaleSupply()'}}" style="margin-right: 10px;">
                                        <i class="fa fa-file-text-o"></i>
                                        {{__('Show Sale Supply Order')}}
                                    </button>
                                <li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            <div class="col-md-4 normal"
                 style="{{ isset($salesInvoice) && in_array($salesInvoice->invoice_type, ['direct_sale_quotations', 'from_sale_supply_order', 'from_sale_quotations'])? 'display:none':''}}">
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

            <div class="col-md-4 normal"
                 style="{{ isset($salesInvoice) && in_array($salesInvoice->invoice_type, ['direct_sale_quotations', 'from_sale_supply_order', 'from_sale_quotations'])? 'display:none':''}}">
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

            <div class="col-md-4 normal"
                 style="{{ isset($salesInvoice) && in_array($salesInvoice->invoice_type, ['direct_sale_quotations', 'from_sale_supply_order', 'from_sale_quotations'])? 'display:none':''}}">
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

        {{--  SELECTED SALE QUOTATIONS  --}}
        <div id="sale_quotation_ids">

            @if(isset($salesInvoice))

                @foreach( $salesInvoice->saleQuotations->pluck('id')->toArray() as $id)
                    <input type="hidden" name="sale_quotation_ids[]" value="{{$id}}">
                    @endforeach

            @endif
        </div>

        {{--  SELECTED SALE SUPPLY ORDERS  --}}
        <div id="sale_supply_orders_ids">

            @if(isset($salesInvoice))

                @foreach( $salesInvoice->saleSupplyOrders->pluck('id')->toArray() as $id)
                    <input type="hidden" name="sale_supply_orders[]" value="{{$id}}">
                @endforeach

            @endif
        </div>


    </div>
</div>
