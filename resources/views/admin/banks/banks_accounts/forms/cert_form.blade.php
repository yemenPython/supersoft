<div class="">

    <div class="col-md-12 mb-5" style="margin-bottom: 20px">
        <hr>
            <h4 class="text-center" >{{__('Data of deposit accounts and certificates')}}</h4>
    </div>


    <div class="form-group col-md-4">
        <label> {{ __('Bank Name') }} {!! required() !!}</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select class="form-control select2" required name="bank_data_id" id="bank_data_id" onchange="setBankData()">
                <option value=""> {{ __('Select') }} </option>
                @foreach($banksData as $bank)
                    <option value="{{$bank->id}}" data-bankBranch="{{$bank->branch}}"
                    {{isset($item) && $item->bank_data_id == $bank->id ? 'selected' : ''}}> {{ $bank->name }} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Branch Bank')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                <input required type="text" id="bankBranch" readonly class="form-control" value="{{isset($item) ? optional($item->bankData)->branch : ''}}">
            </div>
        </div>
    </div>

    <div class="form-group col-md-4">
        <label> {{ __('Account Type') }} {!! required() !!}</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select required class="form-control select2" name="branch_product_id" id="branch_product_id">
                <option value=""> {{ __('Select') }} </option>
                @foreach($products as $product)
                    <option value="{{$product->id}}"  {{isset($item) && $item->branch_product_id == $product->id ? 'selected' : ''}}> {{ $product->name }} </option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="form-group col-md-4">
        <label> {{ __('Type') }} {!! required() !!}</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select required class="form-control select2" name="type">
                <option value=""> {{ __('Select') }} </option>
                <option value="deposit_for" {{isset($item) && $item->type == 'deposit_for' ? 'selected' : ''}}> {{ __('deposit for') }} </option>
                <option value="savings_certificate" {{isset($item) && $item->type == 'savings_certificate' ? 'selected' : ''}}> {{ __('savings certificate') }} </option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Last renewal date')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                <input required type="text" name="Last_renewal_date"  class="form-control datepicker" value="{{isset($item) ? $item->Last_renewal_date : ''}}">
            </div>
        </div>
    </div>


    <div class="form-group col-md-4">
        <label> {{ __('Currency') }} {!! required() !!} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select required class="form-control select2" name="currency_id">
                <option value=""> {{ __('Select') }} </option>
                @foreach($currencies as $currency)
                    <option value="{{$currency->id}}" {{isset($item) && $item->currency_id == $currency->id ? 'selected' : ''}}> {{ $currency->name }} </option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('deposit number')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                <input required type="text" name="deposit_number"  class="form-control" value="{{isset($item) ? $item->deposit_number : ''}}">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Deposit opening date')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                <input required type="text" name="deposit_opening_date"  class="form-control datepicker" value="{{isset($item) ? $item->deposit_opening_date : ''}}">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Deposit term')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                <input required type="text" name="deposit_term"  class="form-control" value="{{isset($item) ? $item->deposit_term : ''}}">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Deposit expiry date')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                <input required type="text" name="deposit_expiry_date"  class="form-control datepicker" value="{{isset($item) ? $item->deposit_expiry_date : ''}}">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('deposit amount')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                <input required type="number" name="deposit_amount"  class="form-control" value="{{isset($item) ? $item->deposit_amount : ''}}">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Interest Rate')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-percent"></i></span>

                <input required type="number" name="interest_rate"  class="form-control" value="{{isset($item) ? $item->interest_rate : old('interest_rate')}}">
            </div>
        </div>
    </div>

    <div class="form-group col-md-4">
        <label> {{ __('Yield rate type') }} {!! required() !!}</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select required class="form-control select2" name="yield_rate_type">
                <option value=""> {{ __('Select') }} </option>
                <option value="fixed" {{isset($item) && $item->yield_rate_type == 'fixed' ? 'selected' : ''}}> {{ __('Fixed') }} </option>
                <option value="not_fixed" {{isset($item) && $item->yield_rate_type == 'not_fixed' ? 'selected' : ''}}> {{ __('not Fixed') }} </option>
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Periodicity of return disbursement')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file-code-o"></i></span>
                <input  required type="number" name="periodicity_return_disbursement"  class="form-control" value="{{isset($item) ? $item->periodicity_return_disbursement : ''}}">
            </div>
        </div>
    </div>


    <div class="form-group col-md-4">
        <label> {{ __('Add interest in an account') }}  </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select class="form-control select2" name="bank_account_child_id">
                <option value=""> {{ __('Select') }} </option>
                @foreach($bankAccounts as $bankAccount)
                    <option value="{{$bankAccount->id}}"  {{isset($item) && $item->bank_account_child_id == $bankAccount->id ? 'selected' : ''}}>
                        {{ optional($bankAccount->mainType)->name }}
                        @if ($bankAccount->subType)
                            <strong class="text-danger">[   {{ optional($bankAccount->subType)->name }}  ]</strong>
                        @endif </option>
                @endforeach
            </select>
        </div>
    </div>


    <div class="col-md-12" style="margin-bottom: 30px">
        <div class="switch primary col-md-3">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" id="switch-status" name="status" value="1" {{isset($item) && $item->status ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
            <label for="switch-status"> {{__('Account Status')}} - {{__('Active')}} / {{__('inActive')}}</label>
        </div>

        <div class="switch primary col-md-3">
            <input type="hidden" name="auto_renewal" value="0">
            <input type="checkbox" id="switch-check_books" name="auto_renewal" value="1" {{isset($item) && $item->auto_renewal ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
            <label for="switch-check_books">{{__('auto renewal')}} {{__('yes / no')}}</label>
        </div>

    </div>
</div>
