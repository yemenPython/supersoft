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

                        <div class="form-group col-md-3">
                            <label> {{ __('Quotation Number') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                {!! drawSelect2ByAjax('number','SaleQuotation','number', 'number',__('Select'),request()->number) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="date" class="control-label">{{__('Invoice type form')}}</label>
                            <div class="form-group">

                                <div class="col-xs-6">
                                    <div class="radio primary ">
                                        <input type="radio" name="type_for" value="customer" id="customer_radio" checked onclick="changeType()">
                                        <label for="customer_radio">{{__('Customer')}}</label>
                                    </div>
                                </div>

                                <div class="col-xs-6">
                                    <div class="radio primary ">
                                        <input type="radio" name="type_for" value="supplier" id="supplier_radio" onclick="changeType()">
                                        <label for="supplier_radio">{{__('Supplier')}}</label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-5" style="display: none;" id="supplier_select">
                            <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label">{{__('Suppliers')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-user"></span>
                                    {!! drawSelect2ByAjax('supplier_id','Supplier','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select'),request()->supplier_id) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5" id="customer_select">
                            <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label">{{__('Customers')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-user"></span>
                                    {!! drawSelect2ByAjax('customer_id','Customer','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select'),request()->customer_id) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Date Add From') }}</label>
                            <div class="input-group">

                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" class="form-control datepicker" name="date_add_from">
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Date Add To') }}</label>
                            <div class="input-group">

                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" class="form-control datepicker" name="date_add_to">
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Supply Date From') }}</label>
                            <div class="input-group">

                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" class="form-control datepicker" name="supply_date_from">
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Supply Date To') }}</label>
                            <div class="input-group">

                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" class="form-control datepicker" name="supply_date_to">
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Period of quotation from') }}</label>
                            <div class="input-group">

                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" class="form-control datepicker" name="date_from">
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Period of quotation to') }}</label>
                            <div class="input-group">

                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" class="form-control datepicker" name="date_to">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="date" class="control-label">{{__('Quotation type')}}</label>
                            <div class="form-group">
                                <div class="col-xs-4">
                                    <div class="radio primary ">
                                        <input type="radio" name="quotation_type" value="cash" id="cash">
                                        <label for="cash">{{__('Cash')}}</label>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="radio primary ">
                                        <input type="radio" name="quotation_type" value="credit" id="credit">
                                        <label for="credit">{{__('Credit')}}</label>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="radio primary ">
                                        <input type="radio" name="quotation_type" value="cash_credit" id="cash_credit">
                                        <label for="cash_credit">{{__('Together')}}</label>
                                    </div>
                                </div>

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
