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

                        <div class="col-md-3">
                            <div class="form-group">
                                <label> {{ __('Bank Account Type') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                    <select class="form-control select2" name="main_type_bank_account_id" id="main_type_bank_account_id">
                                        {!! $mainTypes !!}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label> {{ __('Current Account Type') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                    <select class="form-control select2" name="sub_type_bank_account_id">
                                        <option value=""> {{ __('Select') }} </option>
                                        @foreach($subTypes as $index=>$type)
                                            <option value="{{$type->id}}" >
                                                1. {{$index + 1}} {{ $type->name }} </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Bank Name') }} </label>
                            {!! drawSelect2ByAjax('bank_data_id','BankData', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->bank_commissioner_id) !!}
                        </div>

                            <div class="form-group col-md-3">
                                <label> {{ __('Branch Bank') }} </label>
                                {!! drawSelect2ByAjax('branch','BankData', 'branch','branch',  __('opening-balance.select-one'),request()->branch) !!}
                            </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('branch products') }} </label>
                            {!! drawSelect2ByAjax('branch_product_id','BranchProduct', 'name_ar','name_en',  __('opening-balance.select-one'),request()->branch_product_id) !!}
                        </div>

                        <div class="form-group col-md-3">
                            <label> {{ __('Currency') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                <select class="form-control select2" name="currency_id">
                                    <option value=""> {{ __('Select') }} </option>
                                    @foreach($currencies as $currency)
                                        <option value="{{$currency->id}}"> {{ $currency->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                       <div id="sectionSelectCertAccounts">
                           <div class="form-group col-md-3">
                               <label> {{ __('Type') }}</label>
                               <div class="input-group">
                                   <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                   <select class="form-control select2" name="type">
                                       <option value=""> {{ __('Select') }} </option>
                                       <option value="deposit_for"> {{ __('deposit for') }} </option>
                                       <option value="savings_certificate"> {{ __('savings certificate') }} </option>
                                   </select>
                               </div>
                           </div>

                           <div class="form-group col-md-3">
                               <label> {{ __('Yield rate type') }} </label>
                               <div class="input-group">
                                   <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                   <select class="form-control select2" name="yield_rate_type">
                                       <option value=""> {{ __('Select') }} </option>
                                       <option value="fixed"> {{ __('Fixed') }} </option>
                                       <option value="not_fixed"> {{ __('not Fixed') }} </option>
                                   </select>
                               </div>
                           </div>
                       </div>



                        <div class="col-md-12" style="margin-bottom: 15px">

                            <div class="switch primary col-md-3">
                                <input type="checkbox" id="switch-slam" name="active">
                                <label for="switch-slam">{{__('Account Status')}} - {{__('Active')}}</label>
                            </div>
                            <div class="switch primary col-md-3">
                                <input type="checkbox" id="switch-ali" name="inactive">
                                <label for="switch-ali">{{__('Account Status')}} - {{__('inActive')}}</label>
                            </div>

                           <div id="sectionSwitchCertAccounts">
                               <div class="switch primary col-md-3">
                                   <input type="checkbox" id="switch-with_auto_renewal" name="with_auto_renewal" value="1">
                                   <label for="switch-with_auto_renewal">{{__('auto renewal')}} <span class="text-danger">({{__('Yes')}})</span></label>
                               </div>

                               <div class="switch primary col-md-3">
                                   <input type="checkbox" id="switch-without_auto_renewal" name="without_auto_renewal" value="0">
                                   <label for="switch-without_auto_renewal">{{__('auto renewal')}} <span class="text-danger">({{__('No')}})</span></label>
                               </div>
                           </div>

                            <div id="sectionSwitchForCurrentAccounts">
                                <div class="switch primary col-md-3">
                                    <input type="checkbox" id="switch-with_returning" name="with_returning" value="1">
                                    <label for="switch-with_returning">{{__('With Returning')}}</label>
                                </div>

                                <div class="switch primary col-md-3">
                                    <input type="checkbox" id="switch-without_returning" name="without_returning" value="0">
                                    <label for="switch-without_returning">{{__('Without Returning')}} </label>
                                </div>

                                <div class="switch primary col-md-3">
                                    <input type="checkbox" id="switch-with_check_books" name="with_check_books" value="1">
                                    <label for="switch-with_check_books">{{__('check books')}}  <span class="text-danger">({{__('Yes')}})</span></label>
                                </div>

                                <div class="switch primary col-md-3">
                                    <input type="checkbox" id="switch-without_check_books" name="without_check_books" value="0">
                                    <label for="switch-without_check_books">{{__('check books')}} <span class="text-danger"> ({{__('No')}}) </span></label>
                                </div>

                                <div class="switch primary col-md-3">
                                    <input type="checkbox" id="switch-with_overdraft" name="with_overdraft" value="1">
                                    <label for="switch-with_overdraft">{{__('Overdraft')}}   <span class="text-danger">({{__('Yes')}})</span></label>
                                </div>

                                <div class="switch primary col-md-3">
                                    <input type="checkbox" id="switch-without_overdraft" name="without_overdraft" value="0">
                                    <label for="switch-without_overdraft">{{__('Overdraft')}}   <span class="text-danger">({{__('No')}})</span></label>
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
