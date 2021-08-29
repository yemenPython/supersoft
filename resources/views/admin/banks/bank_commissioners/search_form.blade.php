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
                <form  onsubmit="filterFunction($(this));return false;">
                    <div class="list-inline margin-bottom-0 row">
                        <div class="form-group col-md-3">
                            <label> {{ __('Bank Commissioners') }} </label>
                            {!! drawSelect2ByAjax('bank_commissioner_id','BankCommissioner', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->bank_commissioner_id) !!}
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('words.date-from') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="start_date" id="start_date"
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('words.date-to') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="end_date" id="end_date"
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
