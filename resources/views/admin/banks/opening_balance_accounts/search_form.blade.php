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
                    <div class="list-inline margin-bottom-0 row">
                        <input type="hidden" name="filter" value="1">

                        @if(authIsSuperAdmin())
                            <div class="form-group col-md-4">
                                <label> {{ __('Branch') }} </label>
                                {!! drawSelect2ByAjax('branchId','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select'), request()->branchId) !!}
                            </div>
                        @endif

                        <div class="form-group col-md-4">
                            <label> {{ __('Number') }} </label>
                            {!! drawSelect2ByAjax('number','OpeningBalanceAccount','number','number',__('Select'), request()->number) !!}
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Bank Name') }} </label>
                            {!! drawSelect2ByAjax('bank_data_id','BankData', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->bank_commissioner_id) !!}
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputNameAr" class="control-label">{{__('IBAN')}}</label>
                                {!! drawSelect2ByAjax('iban','BAC','iban', 'iban',__('Select Branch'),request()->iban) !!}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputNameAr" class="control-label">{{__('Customer Number')}}</label>
                                {!! drawSelect2ByAjax('customer_number','BAC','customer_number', 'customer_number',__('Select Branch'),request()->customer_number) !!}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label text-new1">{{__('Status')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-check"></span>
                                    <select class="form-control js-example-basic-single" name="status">
                                        <option
                                            value="">{{__('Select')}}</option>
                                        <option
                                            value="progress">{{__('Progress')}}</option>
                                        <option
                                            value="accepted">{{__('Accept')}}</option>
                                        <option
                                            value="rejected">{{__('Reject')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('date From') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input type="text" class="form-control datepicker" name="date_from">
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('date To') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input type="text" class="form-control datepicker" name="date_to">
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
