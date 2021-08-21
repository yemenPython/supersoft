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
                                        onchange="changeBranch()" {{isset($purchaseReturn) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($data['branches'] as $branch)
                                        <option value="{{$branch->id}}"
                                            {{isset($purchaseReturn) && $purchaseReturn->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                            @if(isset($purchaseReturn))
                                <input type="hidden" name="branch_id" value="{{$purchaseReturn->branch_id}}">
                            @endif
                        </div>

                    </div>
                </div>
            @endif

            <div class="col-md-12">

                {{--  Number  --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                            <input type="text" name="invoice_number" class="form-control" placeholder="{{__('Number')}}"
                                   value="{{old('number', isset($purchaseReturn)? $purchaseReturn->invoice_number :'')}}">
                        </div>
                        {{input_error($errors,'invoice_number')}}
                    </div>
                </div>

                {{--  Date  --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date" class="form-control datepicker" id="date"
                                   value="{{old('date', isset($purchaseReturn) ? $purchaseReturn->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date')}}
                    </div>
                </div>

                {{--  Time  --}}
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Time')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                            <input type="time" name="time" class="form-control" id="time"
                                   value="{{old('time',  isset($purchaseInvoice) ? $purchaseInvoice->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>

                {{--  Status  --}}
                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Status')}}</label>
                        <div class="input-group">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="status">

                                <option value="pending"
                                    {{isset($purchaseReturn) && $purchaseReturn->status == 'pending'? 'selected':'' }}>
                                    {{ __('Pending') }}
                                </option>

                                <option value="processing"
                                    {{isset($purchaseReturn) && $purchaseReturn->status == 'processing' ? 'selected':''}}>
                                    {{ __('Processing') }}
                                </option>

                                <option value="finished"
                                    {{isset($purchaseReturn) && $purchaseReturn->status == 'finished' ? 'selected':''}}>
                                    {{ __('Finished') }}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'status')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <label style="display:block">{{__('type')}}</label>
                    <div class="col-xs-6">
                        <div class="radio primary ">

                            <input type="radio" name="type" value="cash" id="cash"
                                {{ !isset($purchaseReturn) ? 'checked':'' }}
                                {{isset($purchaseReturn) && $purchaseReturn->type == 'cash' ? 'checked':''}} >
                            <label for="cash">{{__('Cash')}}</label>
                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="radio primary ">

                            <input type="radio" name="type" id="credit" value="credit"
                                {{isset($purchaseReturn) && $purchaseReturn->type == 'credit' ? 'checked':''}} >
                            <label for="credit">{{__('Credit')}}</label>
                        </div>
                    </div>
                </div>

                {{--  Type  --}}
                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Type')}}</label>

                        <div class="input-group">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="invoice_type"
                                    id="invoice_type" onchange="getTypeItems()" >

                                <option value="">{{__('Select')}}</option>

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

                        {{input_error($errors,'type')}}
                    </div>
                </div>

                <div class="col-md-3" id="">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('return.Invoices')}}</label>

                        <div class="input-group">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="invoiceable_id"
                                    id="invoiceable_id" onchange="selectInvoiceOrReceipt()">

{{--                                <option value="">{{__('Select')}}</option>--}}


                            </select>
                        </div>

                        {{input_error($errors,'invoiceable_id')}}
                    </div>
                </div>

            </div>
        </div>

        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            @include('admin.purchase_returns.table_items')

        </div>


        <div class="row bottom-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


            @include('admin.purchase_returns.financial_details')

            <table id="purchase_receipts_selected" style="display: none;">

                @if(isset($purchaseReturn))
                    @include('admin.purchase-invoices.real_purchase_receipts', ['purchaseReceipts'=> $data['purchaseReceipts'],
                    'purchase_return_receipts' => $purchaseReturn->purchaseReceipts->pluck('id')->toArray()])
                @endif

            </table>

        </div>
    </div>
</div>
