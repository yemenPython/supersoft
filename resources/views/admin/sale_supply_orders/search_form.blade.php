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
                            <label> {{ __('Sale supply Number') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                {!! drawSelect2ByAjax('supply_number','SaleSupplyOrder', 'number', 'number', __('Select'), request()->supply_number) !!}
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Type') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <select name="type" id="supply_type" onchange="changeSupplyType()">

                                    <option value=""> {{__('Select')}}</option>

                                    <option value="from_sale_quotation">
                                        {{ __('From Sale Quotation') }}
                                    </option>

                                    <option value="normal">
                                        {{ __('Normal Sale Supply Order') }}
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-3" id="quotations_number" style="display: none;">
                            <label> {{ __('Quotation Number') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                {!! drawSelect2ByAjax('number_quotation','SaleQuotation','number', 'number',__('Select'), request()->number) !!}
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

                        <div class="col-md-3">
                            <label for="date" class="control-label">{{__('Invoice type form')}}</label>
                            <div class="form-group">
                                <div class="col-xs-4">
                                    <div class="radio primary ">
                                        <input type="radio" name="type_for" value="customer" id="customer_radio"
                                               onclick="changeType()">
                                        <label for="customer_radio">{{__('Customer')}}</label>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="radio primary ">
                                        <input type="radio" name="type_for" value="supplier" id="supplier_radio"
                                               onclick="changeType()">
                                        <label for="supplier_radio">{{__('Supplier')}}</label>
                                    </div>
                                </div>

                                <div class="col-xs-4">
                                    <div class="radio primary ">
                                        <input type="radio" name="type_for" value="supplier_customer" checked
                                               id="supplier_customer" onclick="changeType()">

                                        <label for="supplier_customer">{{__('Together')}}</label>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="col-md-6" id="customer_select">
                            <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label">{{__('Customers')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-user"></span>
                                    {!! drawSelect2ByAjax('customer_id','Customer','name_'.app()->getLocale().',phone1','name_'.app()->getLocale().',phone1',__('Select'),request()->customer_id) !!}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6" id="supplier_select">
                            <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label">{{__('Suppliers')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-user"></span>
                                    {!! drawSelect2ByAjax('supplier_id','Supplier','name_'.app()->getLocale().',phone_1','name_'.app()->getLocale().',phone_1',__('Select'),request()->supplier_id) !!}
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
