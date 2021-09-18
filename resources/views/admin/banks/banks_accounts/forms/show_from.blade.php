@if (isset($item) && optional($item->mainType)->name ==  'حسابات ودائع وشهادات')
    <div class="">

        <div class="col-md-12 mb-5" style="margin-bottom: 20px">
            <hr>
            <h4 class="text-center" >{{__('Data of deposit accounts and certificates')}} <span class="text-danger">[ {{__('Balance')}} {{$item->balance}} ]</span></h4>
        </div>


        <div class="form-group col-md-4">
            <label> {{ __('Bank Name') }} </label>
            <div class="input-group">
                <input type="text" readonly class="form-control" value="{{isset($item) ? optional($item->bankData)->name : ''}}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Branch Bank')}}</label>
                <div class="input-group">
                    <input type="text"  readonly class="form-control" value="{{isset($item) ? optional($item->bankData)->branch : ''}}">
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label> {{ __('Account Type') }} </label>
            <div class="input-group">
                <input type="text"  readonly class="form-control" value="{{isset($item) ? optional($item->product)->name : ''}}">
            </div>
        </div>


        <div class="form-group col-md-4">
            <label> {{ __('Type') }} </label>
            <div class="input-group">
                <input type="text"  readonly class="form-control" value="{{isset($item) && $item->type == 'deposit_for' ? __('deposit for') : __('savings certificate')}}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Last renewal date')}}</label>
                <div class="input-group">
                    <input  type="text"  readonly class="form-control text-danger" value="{{isset($item) ? $item->Last_renewal_date : ''}}">
                </div>
            </div>
        </div>


        <div class="form-group col-md-4">
            <label> {{ __('Currency') }}  </label>
            <div class="input-group">
                <input type="text"  readonly class="form-control" value="{{isset($item) ? optional($item->currency)->name : ''}}">
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('deposit number')}}</label>
                <div class="input-group">
                    <input type="text"  readonly class="form-control" value="{{isset($item) ? $item->deposit_number : ''}}">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Deposit opening date')}}</label>
                <div class="input-group">
                    <input   type="text"  readonly class="form-control" value="{{isset($item) ? $item->deposit_opening_date : ''}}">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Deposit term')}}</label>
                <div class="input-group">
                    <input  type="text"  readonly  name="deposit_term"  class="form-control" value="{{isset($item) ? $item->deposit_term : ''}}">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Deposit expiry date')}}</label>
                <div class="input-group">
                    <input type="text" readonly  class="form-control" value="{{isset($item) ? $item->deposit_expiry_date : ''}}">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('deposit amount')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    <input type="text"  readonly  class="form-control" value="{{isset($item) ? $item->deposit_amount : ''}}">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Interest Rate')}}</label>
                <div class="input-group">
                    <input type="text" readonly   class="form-control" value="{{isset($item) ? $item->interest_rate : old('interest_rate')}}">
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label> {{ __('Yield rate type') }} </label>
            <div class="input-group">
                <input type="text"  readonly class="form-control" value="{{isset($item) && $item->yield_rate_type == 'fixed' ? __('Fixed') : __('not Fixed')}}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Periodicity of return disbursement')}}</label>
                <div class="input-group">
                    <input type="text" readonly  class="form-control" value="{{isset($item) ? $item->periodicity_return_disbursement : ''}}">
                </div>
            </div>
        </div>


        <div class="form-group col-md-4">
            <label> {{ __('Add interest in an account') }} </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                <select class="form-control select2" name="bank_account_child_id">
                    <option value=""> {{ __('Select') }} </option>
                </select>
            </div>
        </div>


        <div class="col-md-12" style="margin-bottom: 30px">
            <div class="switch primary col-md-3">
                <input type="checkbox" id="switch-status" disabled name="status" value="1" {{isset($item) && $item->status ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
                <label for="switch-status"> {{__('Account Status')}} - {{__('Active')}} / {{__('inActive')}}</label>
            </div>

            <div class="switch primary col-md-3">
                <input type="checkbox" id="switch-check_books" disabled name="auto_renewal" value="1" {{isset($item) && $item->auto_renewal ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
                <label for="switch-check_books">{{__('auto renewal')}} {{__('yes / no')}}</label>
            </div>

        </div>
    </div>

@endif

@if (isset($item) && optional($item->subType)->name ==  'حسابات جارية مدينة' || isset($item) && optional($item->subType)->name ==  'حسابات جارية دائنة')
    <div class="">

        <div class="col-md-12 mb-5" style="margin-bottom: 20px">
            <hr>
            @if (isset($subTypeBank) && $subTypeBank->name == 'حسابات جارية مدينة' || isset($item) && optional($item->mainType)->name)
                <h4 class="text-center" >{{__('Debit current account data')}} <span class="text-danger">[ {{__('Balance')}} {{$item->balance}} ]</span></h4>
            @else
                <h4 class="text-center" >{{__('Credit Current Account Data')}} <span class="text-danger">[ {{__('Balance')}} {{$item->balance}} ]</span></h4>
            @endif
        </div>
        <div class="form-group col-md-4">
            <label> {{ __('Bank Name') }} </label>
            <div class="input-group">
                <input type="text" readonly class="form-control" value="{{isset($item) ? optional($item->bankData)->name : ''}}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Branch Bank')}}</label>
                <div class="input-group">
                    <input type="text"  readonly class="form-control" value="{{isset($item) ? optional($item->bankData)->branch : ''}}">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Account Number')}} </label>
                <div class="input-group">
                    <input type="text"  readonly class="form-control" value="{{isset($item) ? $item->account_number : ''}}">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Account Name')}}</label>
                <div class="input-group">
                    <input type="text"  readonly class="form-control" value="{{isset($item) ? $item->account_name : ''}}">
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label> {{ __('Account Type') }} </label>
            <div class="input-group">
                <input type="text"  readonly class="form-control" value="{{isset($item) ? optional($item->product)->name : ''}}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Account Open Date')}} </label>
                <div class="input-group">
                    <input type="text" readonly   class="form-control" value="{{isset($item) ? $item->account_open_date : ''}}">
                </div>
            </div>
        </div>

        @if (isset($subTypeBank) && $subTypeBank->name == 'حسابات جارية مدينة' || isset($item) && optional($item->mainType)->name)
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputNameAr" class="control-label">{{__('Expiry or due date')}}</label>
                    <div class="input-group">
                        <input type="text" readonly  class="form-control" value="{{isset($item) ? $item->expiry_date : ''}}">
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('IBAN')}}</label>
                <div class="input-group">
                    <input type="text" readonly  class="form-control" value="{{isset($item) ? $item->iban : ''}}">
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label> {{ __('Currency') }}  </label>
            <div class="input-group">
                <input type="text"  readonly class="form-control" value="{{isset($item) ? optional($item->currency)->name : ''}}">
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Interest Rate')}}</label>
                <div class="input-group">
                    <input type="text" readonly   class="form-control" value="{{isset($item) ? $item->interest_rate : old('interest_rate')}}">
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Customer Number')}}</label>
                <div class="input-group">
                    <input type="text" readonly  class="form-control" value="{{isset($item) ? $item->customer_number : ''}}">
                </div>
            </div>
        </div>

        @if (isset($subTypeBank) && $subTypeBank->name == 'حسابات جارية مدينة' || isset($item) && optional($item->mainType)->name)
            <div class="col-md-4">
                <div class="form-group">
                    <label for="inputNameAr" class="control-label">{{__('Granted limit')}}</label>
                    <div class="input-group">
                        <input type="text" readonly  class="form-control" value="{{isset($item) ? $item->granted_limit : ''}}">
                    </div>
                </div>
            </div>
        @endif

        <div class="col-md-12" style="margin-bottom: 30px">
            <div class="switch primary col-md-3">
                <input type="checkbox" id="switch-with_returning" disabled name="with_returning" value="1"
                    {{isset($item) && $item->with_returning ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
                <label for="switch-with_returning">{{__('With Returning')}}  /  {{__('Without Returning')}}</label>
            </div>

            <div class="switch primary col-md-3">
                <input type="checkbox" id="switch-status" disabled name="status" value="1"
                    {{isset($item) && $item->status ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
                <label for="switch-status"> {{__('Account Status')}} - {{__('Active')}} / {{__('inActive')}}</label>
            </div>

            <div class="switch primary col-md-3">
                <input type="checkbox" id="switch-check_books" disabled name="check_books" value="1"
                    {{isset($item) && $item->check_books ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
                <label for="switch-check_books">{{__('check books')}} {{__('yes / no')}}</label>
            </div>

            <div class="switch primary col-md-3">
                <input type="checkbox" id="switch-overdraft" disabled name="overdraft" value="1"
                    {{isset($item) && $item->overdraft ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
                <label for="switch-overdraft">{{__('Overdraft')}}</label>
            </div>
        </div>
    </div>
@endif
