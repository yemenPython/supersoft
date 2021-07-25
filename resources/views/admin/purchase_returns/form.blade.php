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
                            <input type="date" name="date" class="form-control" id="date"
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

                <div class="col-md-1">
                    <div class="radio primary ">
                    <label style="opacity:0">{{__('select')}}</label>

                        <input type="radio" name="type" value="cash" id="cash"
                            {{ !isset($purchaseReturn) ? 'checked':'' }}
                            {{isset($purchaseReturn) && $purchaseReturn->type == 'cash' ? 'checked':''}} >
                        <label for="cash">{{__('Cash')}}</label>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="radio primary ">
                    <label style="opacity:0;display:block">{{__('select')}}</label>

                        <input type="radio" name="type" id="credit" value="credit"
                            {{isset($purchaseReturn) && $purchaseReturn->type == 'credit' ? 'checked':''}} >
                        <label for="credit">{{__('Credit')}}</label>
                    </div>
                </div>

                {{--  Type  --}}
                <div class="col-md-3">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Type')}}</label>

                        <div class="input-group">

                            <span class="input-group-addon fa fa-info"></span>

                            <select class="form-control js-example-basic-single" name="invoice_type" id="invoice_type"
                                    onchange="changeType()">

                                <option value="from_supply_order"
                                    {{isset($purchaseReturn) && $purchaseReturn->invoice_type == 'from_supply_order'? 'selected':'' }}>
                                    {{ __('From Supply Order') }}
                                </option>

                                <option value="normal"
                                    {{isset($purchaseReturn) && $purchaseReturn->invoice_type == 'normal'? 'selected':'' }}>
                                    {{ __('Normal purchase invoice') }}
                                </option>

                            </select>
                        </div>
                        {{input_error($errors,'type')}}
                    </div>
                </div>

                {{-- Purchase Invoices --}}
                <div class="col-md-6 from_purchase_invoice"
                     style="{{isset($purchaseReturn) && $purchaseReturn->invoice_type != 'from_supply_order'? '':'display:none'}}
                     {{!isset($purchaseReturn) ? 'display:none':''}}">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Purchase Invoices')}}</label>
                        <div class="input-group">

                            <span class="input-group-addon fa fa-file-text-o"></span>

                            <select class="form-control js-example-basic-single" name="purchase_invoice_id"
                                    id="purchase_invoice_id" onchange="changeType(); selectPurchaseInvoice(); selectSupplier('from_invoice')">
                                <option value="">{{__('Select')}}</option>

                                @foreach($data['purchaseInvoices'] as $purchaseInvoice)
                                    <option value="{{$purchaseInvoice->id}}"
                                            data-supplier-discount="{{$purchaseInvoice->supplier ? $purchaseInvoice->supplier->group_discount : 0}}"
                                            data-supplier-discount-type="{{$purchaseInvoice->supplier ? $purchaseInvoice->supplier->group_discount_type : 'amount'}}"
                                        {{isset($purchaseReturn) && $purchaseReturn->purchase_invoice_id == $purchaseInvoice->id? 'selected':''}}>
                                        {{$purchaseInvoice->invoice_number}}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        {{input_error($errors,'purchase_invoice_id')}}
                    </div>
                </div>

                {{-- Supply Orders --}}
                <div class="col-md-6 from_supply_order"
                     style="{{isset($purchaseReturn) && $purchaseReturn->invoice_type != 'from_supply_order'? 'display:none':''}}">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Supply Orders')}}</label>
                        <div class="input-group">

                            <span class="input-group-addon fa fa-file-text-o"></span>

                            <select class="form-control js-example-basic-single" name="supply_order_ids[]" multiple
                                    id="supply_order_ids" onchange="changeType()">
                                <option value="">{{__('Select')}}</option>

                                @foreach($data['supplyOrders'] as $supplyOrder)
                                    <option value="{{$supplyOrder->id}}"
                                        {{isset($purchaseReturn) &&  in_array($supplyOrder->id, $purchaseReturn->supplyOrders->pluck('id')->toArray())? 'selected':''}}>
                                        {{$supplyOrder->number}} - {{ $supplyOrder->supplier ? $supplyOrder->supplier->name : ''}}
                                    </option>
                                @endforeach

                            </select>
                        </div>
                        {{input_error($errors,'supply_order_ids')}}
                    </div>
                </div>

                {{-- Buttons --}}

                <div class="col-md-12 from_supply_order" style="{{isset($purchaseReturn) && $purchaseReturn->invoice_type != 'from_supply_order'? 'display:none':''}}">
            <div class="form-group">
         

                <div class="input-group">
                <label style="opacity:0">{{__('select')}}</label>
                <ul class="list-inline" style="display:flex">
                <li class="col-md-6">
                                           <button type="button" onclick="getPurchaseReceipts(); changeType()"
                                    class="btn btn-new1 waves-effect waves-light btn-xs">
                                    <i class="fa fa-file-text-o"></i> 
                                    {{__('Get Purchase Receipt')}}
                            </button>
                </li>
                <li class="col-md-6">
                    <button type="button" class="btn btn-new2 waves-effect waves-light btn-xs"
                                    data-toggle="modal" data-target="#purchase_receipts" style="margin-right: 10px;">
                                    <i class="fa fa-file-text-o"></i> 
                                    {{__('Show selected Receipts')}}
                            </button>
                    <li>
                </ul>    
                </div>
                </div>
            </div>


                
                <!-- <div class="col-md-3 from_supply_order" style="{{isset($purchaseReturn) && $purchaseReturn->invoice_type != 'from_supply_order'? 'display:none':''}}">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('')}}</label>

                        <div class="input-group">
          

                           
                        </div>
                    </div>
                </div> -->

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
