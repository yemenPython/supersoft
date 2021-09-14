<div class="">

    <div class="col-md-12 mb-5" style="margin-bottom: 20px">
        <hr>
        @if (isset($subTypeBank) && $subTypeBank->name == 'حسابات جارية مدينة' || isset($item) && optional($item->mainType)->name)
            <h4 class="text-center" >{{__('Debit current account data')}}</h4>
        @else
            <h4 class="text-center" >{{__('Credit Current Account Data')}}</h4>
        @endif
    </div>
    <div class="form-group col-md-4">
        <label> {{ __('Bank Name') }} {!! required() !!}</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select class="form-control select2" name="bank_data_id" id="bank_data_id" onchange="setBankData()">
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
            <label for="inputNameAr" class="control-label">{{__('Branch Bank')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                <input type="text" id="bankBranch" readonly class="form-control" value="{{isset($item) ? optional($item->bankData)->branch : ''}}">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Account Number')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                <input type="text" name="account_number"  class="form-control" value="{{isset($item) ? $item->account_number : ''}}">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Account Name')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                <input type="text" name="account_name"  class="form-control" value="{{isset($item) ? $item->account_name : ''}}">
            </div>
        </div>
    </div>

    <div class="form-group col-md-4">
        <label> {{ __('Account Type') }} {!! required() !!}</label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select class="form-control select2" name="branch_product_id" id="branch_product_id">
                <option value=""> {{ __('Select') }} </option>
                @foreach($products as $product)
                    <option value="{{$product->id}}"  {{isset($item) && $item->branch_product_id == $product->id ? 'selected' : ''}}> {{ $product->name }} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Account Open Date')}} {!! required() !!}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                <input type="date" name="account_open_date"  class="form-control" value="{{isset($item) ? $item->account_open_date : ''}}">
            </div>
        </div>
    </div>

    @if (isset($subTypeBank) && $subTypeBank->name == 'حسابات جارية مدينة' || isset($item) && optional($item->mainType)->name)
        <div class="col-md-4">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Expiry or due date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                    <input type="date" name="expiry_date"  class="form-control" value="{{isset($item) ? $item->expiry_date : ''}}">
                </div>
            </div>
        </div>
    @endif

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('IBAN')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                <input type="text" name="iban"  class="form-control" value="{{isset($item) ? $item->iban : ''}}">
            </div>
        </div>
    </div>

    <div class="form-group col-md-4">
        <label> {{ __('Currency') }} {!! required() !!} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select class="form-control select2" name="currency_id">
                <option value=""> {{ __('Select') }} </option>
                @foreach($currencies as $currency)
                    <option value="{{$currency->id}}" {{isset($item) && $item->currency_id == $currency->id ? 'selected' : ''}}> {{ $currency->name }} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Interest Rate')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                <input type="number" name="interest_rate"  class="form-control" value="{{isset($item) ? $item->interest_rate :  old('interest_rate')}}">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Customer Number')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" name="customer_number"  class="form-control" value="{{isset($item) ? $item->customer_number : ''}}">
            </div>
        </div>
    </div>

    @if (isset($subTypeBank) && $subTypeBank->name == 'حسابات جارية مدينة' || isset($item) && optional($item->mainType)->name)
    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Granted limit')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" name="granted_limit"  class="form-control" value="{{isset($item) ? $item->granted_limit : ''}}">
            </div>
        </div>
    </div>
    @endif

    <div class="col-md-12" style="margin-bottom: 30px">
        <div class="switch primary col-md-3">
            <input type="hidden" name="with_returning" value="0">
            <input type="checkbox" id="switch-with_returning" name="with_returning" value="1"
                {{isset($item) && $item->with_returning ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
            <label for="switch-with_returning">{{__('With Returning')}}  /  {{__('Without Returning')}}</label>
        </div>

        <div class="switch primary col-md-3">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" id="switch-status" name="status" value="1"
                {{isset($item) && $item->status ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
            <label for="switch-status"> {{__('Account Status')}} - {{__('Active')}} / {{__('inActive')}}</label>
        </div>

        <div class="switch primary col-md-3">
            <input type="hidden" name="check_books" value="0">
            <input type="checkbox" id="switch-check_books" name="check_books" value="1"
                {{isset($item) && $item->check_books ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
            <label for="switch-check_books">{{__('check books')}} {{__('yes / no')}}</label>
        </div>

        <div class="switch primary col-md-3">
            <input type="hidden" name="overdraft" value="0">
            <input type="checkbox" id="switch-overdraft" name="overdraft" value="1"
                {{isset($item) && $item->overdraft ? 'checked' : ''}} {{!isset($item) ? 'checked' : ''}}>
            <label for="switch-overdraft">{{__('Overdraft')}}</label>
        </div>
    </div>
</div>
