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
                <form action="{{route('admin:assets_expenses.index')}}" method="get" id="filtration-form">
                    <ul class="list-inline margin-bottom-0 row">
                        @if (authIsSuperAdmin())
                            <li class="form-group col-md-12">
                                <label> {{ __('Branches') }} </label>
                                <select class="form-control select2" name="branch_id" id="branchId">
                                    <option value=""> {{ __('Select Branch') }} </option>
                                    @foreach(\App\Models\Branch::all() as $branch)
                                        <option
                                            {{ isset($_GET['branch']) && $_GET['branch'] == $branch->id ? 'selected' : '' }}
                                            value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                    @endforeach
                                </select>
                            </li>
                        @endif


                        <li class="form-group col-md-4">
                            <label> {{ __('opening-balance.serial-number') }}</label>
                            {!! drawSelect2ByAjax('number','AssetExpense','number', 'number', __('opening-balance.serial-number'),  request()->number) !!}
                        </li>


                        <li class="form-group col-md-4">
                            <label>{{__('Date From')}}</label>
                            <input type="date" name="dateFrom" class="form-control">
                        </li>
                        <li class="form-group col-md-4">
                            <label>{{__('Date To')}}</label>
                            <input type="date" name="dateTo" class="form-control">
                        </li>


                        <li class="form-group col-md-4">
                            <label>{{__('assets Groups')}}</label>
                            {!! drawSelect2ByAjax('asset_group_name','AssetGroup', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'), request()->asset_group_name) !!}
                        </li>

                        <li class="form-group col-md-4">
                            <label> {{ __('words.assets') }}</label>
                            {!! drawSelect2ByAjax('asset_name','Asset', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'), request()->asset_group_name) !!}
                        </li>



                        <li class="form-group col-md-4">
                            <label> {{ __('Concession Status') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-cubes"></span>
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
                                {!! drawSelect2ByAjax('asset_expense_type','AssetsTypeExpense', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'), request()->asset_expense_type) !!}
                            </li>

                            <li class="form-group col-md-4">
                                <label> {{ __('Expenses Items') }}</label>
                                {!! drawSelect2ByAjax('asset_expense_item','AssetsItemExpense', 'item_'.app()->getLocale(),'item_'.app()->getLocale(),  __('opening-balance.select-one'), request()->asset_expense_item) !!}
                            </li>


                    </ul>
                    <button type="submit"
                            class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out"><i
                            class=" fa fa-search "></i> {{__('Search')}} </button>
                    <a href="{{route('admin:assets_expenses.index')}}"
                       class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out"><i
                            class=" fa fa-reply"></i> {{__('Back')}}
                    </a>
                </form>

            </div>
        </div>
    </div>
@endif
