<div class="row">
    <div class="col-xs-12">
        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">
            @if(authIsSuperAdmin())
                <div class="col-md-12">
                    <div class="col-md-12">
                        <div class="form-group has-feedback">
                            <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                        onchange="changeBranch()"
                                    {{isset($item) ? 'disabled':''}}>
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach($branches as $branch)
                                        <option
                                            value="{{$branch->id}}" {{isset($item) && $item->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{input_error($errors,'branch_id')}}
                        </div>
                    </div>

                </div>
            @endif

            <div class="col-md-12">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                            <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}"
                                   disabled
                                   value="{{old('number', isset($item)? $item->number : $number)}}">
                            <input type="hidden" name="number"
                                   value="{{old('number', isset($item)? $item->number : $number)}}">
                        </div>
                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date" class="form-control datepicker" id="date"
                                   value="{{old('date', isset($item)? $item->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date')}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Time')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                            <input type="time" name="time" class="form-control" id="time"
                                   value="{{old('time', isset($item)? $item->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">
    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="inputStore" class="control-label text-new1">{{__('Bank Account Type')}}</label>
            <div class="input-group" id="main_types">
                <span class="input-group-addon fa fa-cubes"></span>
                <select class="form-control js-example-basic-single" id="main_type_bank_account_id">
                    {!! $mainTypes !!}
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-4" id="current_account_type_section" style="display: none">
        <div class="form-group">
            <label> {{ __('Current Account Type') }} </label>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                <select class="form-control select2" name="sub_type_bank_account_id" id="sub_type_bank_account_id">
                    <option value=""> {{ __('Select') }} </option>
                    @foreach($subTypes as $index=>$type)
                        <option value="{{$type->id}}" data-subType="{{$type->name}}"
                            {{isset($item) && $item->sub_type_bank_account_id == $type->id ? 'selected' : ''}}>
                            1. {{$index + 1}} {{ $type->name }} </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-group col-md-4">
        <label> {{ __('Accounts') }}  </label>
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
            <select class="form-control select2" id="bankAccountsData" onchange="getBankAccountById()">
                <option value=""> {{ __('Select') }} </option>
                @foreach($bankAccounts as $bankAccount)
                    <option value="{{$bankAccount->id}}"  {{isset($item) && $item->bank_account_child_id == $bankAccount->id ? 'selected' : ''}}>
                        {{ optional($bankAccount->mainType)->name }}
                        @if ($bankAccount->subType)
                            <strong class="text-danger">[   {{ optional($bankAccount->subType)->name }}  ]</strong>
                        @endif
                        @if ($bankAccount->bankData)
                            <strong class="text-danger">[   {{ optional($bankAccount->bankData)->name }}  ]</strong>
                        @endif
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    @include('admin.banks.opening_balance_accounts.table_items')
</div>

<div class="col-md-12">
    <div class="form-group">
        <label> {{ __('Notes') }} </label>
        <textarea class="form-control" name="notes" id="note"
                  placeholder="{{ __('Notes') }}">{{isset($item)? $item->notes : old('notes') }}</textarea>
    </div>
    {{input_error($errors,'note')}}
</div>

@section('modals')
    <div class="modal fade wg-content" id="showBankData" role="dialog">
        <div class="modal-dialog" style="width:800px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="box-loader">
                        <p>{{__('Loading')}}</p>
                        <div class="loader-31"></div>
                    </div>
                    <div id="showBankDataResponse">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
