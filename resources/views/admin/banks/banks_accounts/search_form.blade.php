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
                                    <select class="form-control select2" name="main_type_bank_account_id"
                                            id="main_type_bank_account_id">
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
                                            <option value="{{$type->id}}">
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

                        <div id="datesForCert">
                            <div class="col-md-3">
                            <div class="form-group">
                                <label for="inputNameAr" class="control-label">{{__('Last renewal date')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon">{{__('From')}}</span>
                                    <input  type="text" name="Last_renewal_date_from"  class="form-control datepicker">
                                </div>
                                <div class="input-group">
                                    <span class="input-group-addon">{{__('To')}}</span>
                                    <input  type="text" name="Last_renewal_date_to"  class="form-control datepicker">
                                </div>
                            </div>
                        </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputNameAr" class="control-label">{{__('Deposit opening date')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{__('From')}}</span>
                                        <input  type="text" name="deposit_opening_date_from"  class="form-control datepicker">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{__('To')}}</span>
                                        <input  type="text" name="deposit_opening_date_to"  class="form-control datepicker">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputNameAr" class="control-label">{{__('Deposit expiry date')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{__('From')}}</span>
                                        <input  type="text" name="deposit_expiry_date_from"  class="form-control datepicker">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{__('To')}}</span>
                                        <input  type="text" name="deposit_expiry_date_to"  class="form-control datepicker">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div id="">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputNameAr" class="control-label">{{__('Account Open Date')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{__('From')}}</span>
                                        <input  type="text" name="account_open_date_from"  class="form-control datepicker">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{__('To')}}</span>
                                        <input  type="text" name="account_open_date_to"  class="form-control datepicker">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputNameAr" class="control-label">{{__('Expiry or due date')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{__('From')}}</span>
                                        <input  type="text" name="expiry_date_from"  class="form-control datepicker">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">{{__('To')}}</span>
                                        <input  type="text" name="expiry_date_to"  class="form-control datepicker">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputNameAr" class="control-label">{{__('IBAN')}}</label>
                                    {!! drawSelect2ByAjax('iban','BAC','iban', 'iban',__('Select Branch'),request()->iban) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="inputNameAr" class="control-label">{{__('Customer Number')}}</label>
                                    {!! drawSelect2ByAjax('customer_number','BAC','customer_number', 'customer_number',__('Select Branch'),request()->customer_number) !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-12" style="margin-bottom: 15px">

                            <div class="form-group col-md-3">
                                <label> {{ __('Account Status') }}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                    <select class="form-control select2" name="status">
                                        <option value=""> {{ __('Select') }} </option>
                                        <option value=""> {{ __('All') }} </option>
                                        <option value="deposit_for"> {{__('Account Status')}}
                                            - {{__('Active')}} </option>
                                        <option value="savings_certificate"> {{__('Account Status')}}
                                            - {{__('inActive')}} </option>
                                    </select>
                                </div>
                            </div>

                                <div id="sectionSwitchCertAccounts">
                                    <div class="form-group col-md-3">
                                        <label> {{ __('auto renewal') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select class="form-control select2" name="auto_renewal">
                                                <option value=""> {{ __('Select') }} </option>
                                                <option value="all"> {{ __('All') }} </option>
                                                <option value="1">{{__('auto renewal')}} <span class="text-danger">({{__('Yes')}}) </span> </option>
                                                <option value="0">{{__('auto renewal')}} <span class="text-danger">({{__('No')}}) </span></option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div id="sectionSwitchForCurrentAccounts">

                                    <div class="form-group col-md-3">
                                        <label> {{ __('With Returning') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select class="form-control select2" name="with_returning">
                                                <option value=""> {{ __('Select') }} </option>
                                                <option value="all"> {{ __('All') }} </option>
                                                <option value="1">{{__('With Returning')}} </option>
                                                <option value="0">{{__('Without Returning')}}</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label> {{ __('check books') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select class="form-control select2" name="check_books">
                                                <option value=""> {{ __('Select') }} </option>
                                                <option value="all"> {{ __('All') }} </option>
                                                <option value="1">{{__('check books')}} <span class="text-danger">({{__('Yes')}})</span> </option>
                                                <option value="0">{{__('check books')}} <span class="text-danger">({{__('No')}})</span></option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="form-group col-md-3">
                                        <label> {{ __('Overdraft') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select class="form-control select2" name="overdraft">
                                                <option value=""> {{ __('Select') }} </option>
                                                <option value="all"> {{ __('All') }} </option>
                                                <option value="1">{{__('Overdraft')}} <span class="text-danger">({{__('Yes')}})</span> </option>
                                                <option value="0">{{__('Overdraft')}} <span class="text-danger">({{__('No')}})</span></option>
                                            </select>
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
