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
            <div class="card-content js__card_content">
                <form onsubmit="filterFunction($(this));return false;">
                    <div class="list-inline margin-bottom-0 row">
                        <input type="hidden" name="filter" value="1">
                        @if(authIsSuperAdmin())
                            <div class="form-group col-md-3">
                                <label> {{ __('Branches') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-file"></span>
                                    {!! drawSelect2ByAjax('branchId','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch'),request()->branch_id) !!}
                                </div>
                            </div>
                        @endif

                        <div class="form-group col-md-3">
                            <label> {{ __('Bank Name') }} </label>
                            {!! drawSelect2ByAjax('bank_data_id','BankData', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->bank_commissioner_id) !!}
                        </div>

                            <div class="form-group col-md-3">
                                <label> {{ __('Branch Code') }} </label>
                                {!! drawSelect2ByAjax('code','BankData', 'code','code',  __('opening-balance.select-one'),request()->branch_code) !!}
                            </div>

                            <div class="form-group col-md-3">
                                <label> {{ __('Swift Code') }} </label>
                                {!! drawSelect2ByAjax('swift_code','BankData', 'swift_code','branch',  __('opening-balance.select-one'),request()->branch) !!}
                            </div>

                            <div class="form-group col-md-3">
                                <label> {{ __('Branch Bank') }} </label>
                                {!! drawSelect2ByAjax('branch','BankData', 'branch','branch',  __('opening-balance.select-one'),request()->branch) !!}
                            </div>

                            <div class="form-group col-md-3">
                                <label> {{ __('Phone') }} </label>
                                {!! drawSelect2ByAjax('phone','BankData', 'phone','phone',  __('opening-balance.select-one'),request()->phone) !!}
                            </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Bank Commissioners') }} </label>
                            {!! drawSelect2ByAjax('employee_id','EmployeeData', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->bank_commissioner_id) !!}
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Bank Officials') }} </label>
                            {!! drawSelect2ByAjax('bank_official_id','BankOfficial', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->bank_official_id) !!}
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Start Date Dealing From') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="start_date_from" id="start_date_from"
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Start Date Dealing To') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="start_date_to" id="start_date_to"
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>

                            <div class="form-group col-md-3">
                                <label> {{ __('Stop Date Dealing From') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                    <input name="stop_date_from" id="stop_date_from"
                                           class="form-control date js-example-basic-single" type="date"/>
                                </div>
                            </div>

                            <div class="form-group col-md-3">
                                <label> {{ __('Stop Date Dealing To') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                    <input name="stop_date_to" id="stop_date_to"
                                           class="form-control date js-example-basic-single" type="date"/>
                                </div>
                            </div>


                        <div class="switch primary col-md-1">
                            <input type="checkbox" id="switch-slam" name="active">
                            <label for="switch-slam">{{__('Active')}}</label>
                        </div>
                        <div class="switch primary col-md-2">
                            <input type="checkbox" id="switch-ali" name="inactive">
                            <label for="switch-ali">{{__('inActive')}}</label>
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
