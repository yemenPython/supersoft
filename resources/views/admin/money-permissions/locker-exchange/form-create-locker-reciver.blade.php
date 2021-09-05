<form method="post" action="{{route('admin:locker-receives.store', ['fromAjax' => true])}}" class="formCreateNewLockerReciver" id="formCreateNewLockerReciver">
    @csrf
    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label class="control-label">{{ __('words.select-exchange-permission') }}</label>
                    <input type="hidden" name="locker_exchange_permission_id" value="{{ $exchange->id}}">
                    <input type="text" class="form-control" readonly value="{{ ($exchange instanceof \App\ModelsMoneyPermissions\BankExchangePermission ? __('words.bank') : __('words.locker')) . ' | '.$exchange->permission_number }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">{{ __('words.from') }}</label>
                    <input type="text" readonly class="form-control" value="{{optional($exchange->fromLocker)->name}}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">{{ __('words.to') }}</label>
                    <input type="text" readonly class="form-control"
                           value="{{ $exchange->destination_type == 'bank' ?  optional($exchange->toBank)->name : optional($exchange->toLocker)->name }}">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">{{ __('words.source-type') }}</label>
                    <input type="text" readonly class="form-control" value="{{__('words.'.$exchange->destination_type)}}">
                    <input type="hidden" name="source_type" value="{{$exchange->destination_type}}">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">{{ __('words.permission-number') }}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                        <input type="text" name="permission_number" value="{{ $permission_number }}"
                               readonly class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">{{ __('words.operation-date') }}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                        <input required name="operation_date" class="form-control"
                               type="date" placeholder="{{ __('words.operation-date') }}">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">{{ __('the Amount') }}</label>
                    <input type="text" readonly name="amount" class="form-control"
                           placeholder="{{ __('the Amount') }}" value="{{$exchange->amount}}">
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="col-md-4">
                <div class="form-group has-feedback">
                    <label class="control-label">{{ __('words.money-receiver') }}</label>
                    <select name="employee_id" class="form-control" id="empModalTOFire" required>
                        <option value=""> {{ __('Select Employee') }} </option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"> {{ $employee->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
            @php
                $branch_id = authIsSuperAdmin() ? NULL : auth()->user()->branch_id;
                $selected_id = NULL;
            @endphp
            @include('admin.money-permissions.cost-centers' ,['branch_id' => $branch_id ,'selected_id' => $selected_id])
        </div>
        <div class="col-md-12">
            <div class="col-md-8">
                <div class="form-group has-feedback">
                    <label class="control-label">{{__('words.permission-note')}}</label>
                    <textarea rows="4" name="note" class="form-control"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</form>
