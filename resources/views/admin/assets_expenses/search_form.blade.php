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
            <div class="card-content js__card_content" style="padding:20px">
                <form onsubmit="filterFunction($(this));return false;" id="filtration-form">
                    <ul class="list-inline margin-bottom-0 row">
                        @if (authIsSuperAdmin())
                            <li class="form-group col-md-12">
                                <label> {{ __('Branches') }} </label>
                                <div class="input-group">
                                                <span class="input-group-addon fa fa-file"></span>
                                <select class="form-control select2" name="branch_id" id="branchId">
                                    <option value=""> {{ __('Select Branch') }} </option>
                                    @foreach(\App\Models\Branch::all() as $branch)
                                        <option
                                            {{ isset($_GET['branch']) && $_GET['branch'] == $branch->id ? 'selected' : '' }}
                                            value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                    @endforeach
                                </select>
                                </div>
                            </li>

                        @endif


                        <li class="form-group col-md-4">
                            <label> {{ __('opening-balance.serial-number') }}</label>
                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>

                            {!! drawSelect2ByAjax('number','AssetExpense','number', 'number', __('opening-balance.serial-number'),  request()->number) !!}
                            </div>
                        </li>


                        <li class="form-group col-md-4">
                            <label>{{__('Date From')}}</label>
                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                            <input type="text" name="dateFrom" class="form-control datepicker">
                            </div>
                        </li>
                        <li class="form-group col-md-4">
                            <label>{{__('Date To')}}</label>
                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                            <input type="text" name="dateTo" class="form-control datepicker">
                            </div>
                        </li>


                        <li class="form-group col-md-4">
                            <label>{{__('assets Groups')}}</label>
                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>

                            {!! drawSelect2ByAjax('asset_group_name','AssetGroup', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'), request()->asset_group_name) !!}
                            </div>
                        </li>

                        <li class="form-group col-md-4">
                            <label> {{ __('words.assets') }}</label>
                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>

                            {!! drawSelect2ByAjax('asset_name','Asset', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'), request()->asset_group_name) !!}
                            </div>
                        </li>



                        <li class="form-group col-md-4">
                            <label> {{ __('Concession Status') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-info"></span>
                                <select class="form-control js-example-basic-single" name="status">
                                    <option value="">{{__('Select Status')}}</option>
                                    <option
                                        value="pending" {{ isset($_GET['status']) && $_GET['status'] == 'pending' ? 'selected' : '' }}>
                                        {{__('Pending')}}
                                    </option>
                                    <option
                                        value="accept" {{ isset($_GET['status']) && $_GET['status'] == 'accept' ? 'selected' : '' }}>{{__('Accepted')}}</option>
                                    <option
                                        value="cancel" {{ isset($_GET['status']) && $_GET['status'] == 'cancel' ? 'selected' : '' }}>{{__('Rejected')}}</option>
                                </select>
                            </div>
                        </li>

                            <li class="form-group col-md-4">
                                <label> {{ __('Expenses Types') }}</label>
                                <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-o"></i></span>

                                {!! drawSelect2ByAjax('asset_expense_type','AssetsTypeExpense', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'), request()->asset_expense_type) !!}
                                </div>
                            </li>

                            <li class="form-group col-md-4">
                                <label> {{ __('Expenses Items') }}</label>
                                <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-o"></i></span>

                                {!! drawSelect2ByAjax('asset_expense_item','AssetsItemExpense', 'item_'.app()->getLocale(),'item_'.app()->getLocale(),  __('opening-balance.select-one'), request()->asset_expense_item) !!}
                                </div>
                            </li>


                    </ul>
                    @include('admin.btns.btn_search')
                </form>

            </div>
        </div>
    </div>
@endif
