<div class="">

    <div class="col-md-12 mb-5" style="margin-bottom: 20px">
        <hr>
        <h4 class="text-center" >{{__('Credit Current Account Data')}}</h4>
    </div>
    <div class="form-group col-md-4">
        <label> {{ __('Bank Name') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select class="form-control select2" name="bank_data_id" id="bank_data_id" onchange="setBankData()">
                <option value=""> {{ __('Select') }} </option>
                @foreach($banksData as $bank)
                    <option value="{{$bank->id}}" data-bankBranch="{{$bank->branch}}"> {{ $bank->name }} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Branch Bank')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                <input type="text" id="bankBranch" readonly class="form-control" value="">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Account Number')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                <input type="text" name="account_number"  class="form-control" value="">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Account Name')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file"></i></span>
                <input type="text" name="account_name"  class="form-control" value="">
            </div>
        </div>
    </div>

    <div class="form-group col-md-4">
        <label> {{ __('Account Type') }} </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select class="form-control select2" name="branch_product_id" id="branch_product_id">
                <option value=""> {{ __('Select') }} </option>
                @foreach($products as $product)
                    <option value="{{$product->id}}"> {{ $product->name }} </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Account Open Date')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                <input type="date" name="account_open_date"  class="form-control" value="">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('IBAN')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                <input type="text" name="iban"  class="form-control" value="">
            </div>
        </div>
    </div>

    <div class="form-group col-md-4">
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

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Interest Rate')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                <input type="number" name="interest_rate"  class="form-control" value="">
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Customer Number')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" name="customer_number"  class="form-control" value="">
            </div>
        </div>
    </div>

    <div class="col-md-12" style="margin-bottom: 30px">
        <div class="switch primary col-md-3">
            <input type="hidden" name="with_returning" value="0">
            <input type="checkbox" id="switch-with_returning" name="with_returning" value="1">
            <label for="switch-with_returning">{{__('With Returning')}}  /  {{__('Without Returning')}}</label>
        </div>

        <div class="switch primary col-md-3">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" id="switch-status" name="status" value="1">
            <label for="switch-status"> {{__('Account Status')}} - {{__('Active')}} / {{__('inActive')}}</label>
        </div>

        <div class="switch primary col-md-3">
            <input type="hidden" name="check_books" value="0">
            <input type="checkbox" id="switch-check_books" name="check_books" value="1">
            <label for="switch-check_books">{{__('check books')}} {{__('yes / no')}}</label>
        </div>

        <div class="switch primary col-md-3">
            <input type="hidden" name="overdraft" value="0">
            <input type="checkbox" id="switch-overdraft" name="overdraft" value="1">
            <label for="switch-overdraft">{{__('Overdraft')}}</label>
        </div>
    </div>
</div>
