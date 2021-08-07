@if(filterSetting())
    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card top-search">
            <h4 class="box-title with-control">
                <i class="fa fa-search"></i>{{__('Search filters')}}
                <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
				</span>
                <!-- /.controls -->
            </h4>
            <!-- /.box-title -->
            <div class="card-content js__card_content" style="padding:30px">
                <form onsubmit="filterFunction($(this));return false;">
                    <input type="hidden" name="filter" value="1">
                    <div class="list-inline margin-bottom-0 row">

                        @if(authIsSuperAdmin())
                            <div class="form-group col-md-12">
                                <label> {{ __('Branches') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-file"></span>
                                    {!! drawSelect2ByAjax('branchId','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch'),request()->branch_id) !!}
                                </div>
                            </div>
                        @endif


                            <div class="form-group col-md-4">
                                <label> {{ __('Type') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-info"></span>
                                    <select class="form-control js-example-basic-single" name="invoice_type" id="invoice_type">
                                        <option value="">{{ __('Select') }}</option>
                                        <option value="from_supply_order">{{ __('From Supply Order') }}</option>
                                        <option value="normal">{{ __('Normal purchase invoice') }}</option>
                                    </select>
                                </div>
                            </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Invoice Number') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                {!! drawSelect2ByAjax('invoice_number','PurchaseInvoice','invoice_number', 'invoice_number',__('Select'),request()->invoice_number) !!}
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label">{{__('Suppliers')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-user"></span>
                                    {!! drawSelect2ByAjax('supplier_id','Supplier','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select'),request()->supplier_id) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Supply Orders') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                {!! drawSelect2ByAjax('supply_order_number','SupplyOrder','number', 'number',__('Select'),request()->number) !!}
                            </div>
                        </div>

                            <div class="form-group col-md-4">
                                <label> {{ __('Purchase Receipts') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-file"></span>
                                    {!! drawSelect2ByAjax('purchase_receipt_id','PurchaseReceipt','number', 'number',__('Select'),request()->number) !!}
                                </div>
                            </div>



                        <div class="form-group col-md-4">
                            <label> {{ __('Date Add From') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" class="form-control datepicker" name="date_add_from">
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Date Add To') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" class="form-control datepicker" name="date_add_to">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label style="display:block">{{__('Quotation type')}}</label>
                        <div class="col-md-4" style="padding:0">
                            <div class="radio primary ">
                                <input type="radio" name="type" value="cash" id="cash">
                                <label for="cash">{{__('Cash')}}</label>
                            </div>
                        </div>

                        <div class="col-md-4" style="padding:0">
                            <div class="radio primary ">
                                <input type="radio" name="type" id="credit" value="credit">
                                <label for="credit">{{__('Credit')}}</label>
                            </div>
                        </div>

                        <div class="col-md-4" style="padding:0">
                            <div class="radio primary ">
                                <input type="radio" name="type" id="together" value="together">
                                <label for="together">{{__('Together')}}</label>
                            </div>
                        </div>
                    </div>

                    @include('admin.btns.btn_search')

                </form>
            </div>
            <!-- /.card-content -->
        </div>
        <!-- /.box-content -->
    </div>
@endif
