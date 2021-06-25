@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('words.bank-exchanges-edit') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">
                    <a href="{{route('admin:bank-exchanges.index')}}"> {{__('words.bank-exchanges')}}</a>
                </li>
                <li class="breadcrumb-item active"> {{__('words.bank-exchanges-edit')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial">
                    <i class="fa fa-money"></i> {{ __('words.bank-exchanges-edit') }}
                    <span class="controls hidden-sm hidden-xs pull-left">
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                            {{ __('Save') }}
                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                src="{{ asset('assets/images/f1.png') }}">
                        </button>
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                            {{ __('Reset') }}
                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                src="{{asset('assets/images/f2.png')}}">
                        </button>
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                            {{ __('Back') }}
                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                src="{{asset('assets/images/f3.png')}}">
                        </button>
                    </span>
                </h4>
                @php
                    if($model->destination_type == 'locker') {
                        $route = route('admin:bank-exchanges.update-to-locker' ,['id' => $model->id]);
                        $label = __('Locker To');
                        $select_one = __('Select Locker');
                        $validation_class = 'App\Http\Requests\MoneyPermissions\BankExchangeLockerRequest';
                    } else {
                        $route = route('admin:bank-exchanges.update' ,['id' => $model->id]);
                        $label = __('Account To');
                        $select_one = __('Select Account');
                        $validation_class = 'App\Http\Requests\MoneyPermissions\BankExchangeRequest';
                    }
                @endphp
                <div class="box-content">
                    <form method="post" action="{{ $route }}" class="form">
                        @csrf
                        <input type="hidden" name="_method" value="PUT"/>
                        <div class="row">
                            <div class="col-xs-12">
                                @if(authIsSuperAdmin())
                                    <div class="col-md-12">
                                        <div class="form-group has-feedback">
                                            <label class="control-label">
                                                {{ __('Select Branch') }}
                                            </label>
                                            <select name="branch_id" class="form-control  js-example-basic-single" id="branch_id">
                                                @foreach(\App\Models\Branch::all() as $branch)
                                                    <option value="{{ $branch->id }}"
                                                        {{ $model->branch_id == $branch->id ? 'selected' : '' }}>
                                                        {{ $branch->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="branch_id" value="{{ $model->branch_id }}"/>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.permission-number') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                            <input type="text" name="permission_number" value="{{ $model->permission_number }}"
                                                readonly class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.operation-date') }}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                                            <input name="operation_date" class="form-control" value="{{ $model->operation_date }}"
                                                   type="date" placeholder="{{ __('words.operation-date') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('the Amount') }}</label>
                                        <input type="text" name="amount" value="{{ $model->amount }}"
                                            class="form-control" placeholder="{{ __('the Amount') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.bank-from') }}</label>
                                        <select name="from_bank_id" class="form-control"
                                            onchange="update_from_bank_balance();check_banks()">
                                            <option value=""> {{ __('Select Bank Account') }} </option>
                                            @foreach($money_source as $source)
                                                <option data-balance="{{ $source->balance }}"
                                                    {{ $model->from_bank_id == $source->id ? 'selected' : '' }}
                                                    value="{{ $source->id }}"> {{ $source->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('words.available-amount') }}</label>
                                        <input type="hidden" name="from_bank_balance"
                                            value="{{ optional($model->fromBank)->balance }}"/>
                                        <input type="text" readonly id="from_bank_balance" class="form-control"
                                            value="{{ optional($model->fromBank)->balance }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ $label }}</label>
                                        <select name="to_bank_id" class="form-control" onchange="check_banks()">
                                            <option value=""> {{ $select_one }} </option>
                                            @foreach($money_destination as $source)
                                                <option {{ $model->to_bank_id == $source->id ? 'selected' : '' }}
                                                    value="{{ $source->id }}"> {{ $source->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label class="control-label">{{ __('words.money-receiver') }}</label>
                                        <select name="employee_id" class="form-control">
                                            <option value=""> {{ __('Select Employee') }} </option>
                                            @foreach($employees as $employee)
                                                <option {{ $model->employee_id == $employee->id ? 'selected' : '' }}
                                                    value="{{ $employee->id }}"> {{ $employee->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @include('admin.money-permissions.cost-centers' ,[
                                    'branch_id' => $model->branch_id,
                                    'selected_id' => $model->cost_center_id
                                ])
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

                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn">
                                <i class="ico ico-left fa fa-save"></i>
                                {{__('Save')}}
                            </button>

                            <button id="reset" type="button" class="btn hvr-rectangle-in resetAdd-wg-btn">
                                <i class="ico ico-left fa fa-trash"></i>
                                {{__('Reset')}}
                            </button>

                            <button id="back" type="button" class="btn hvr-rectangle-in closeAdd-wg-btn">
                                <i class="ico ico-left fa fa-close"></i>
                                {{__('Back')}}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-validation')
    {!! JsValidator::formRequest($validation_class, '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        const type = '{{ $model->destination_type }}'
        $(document).on('change' ,'#branch_id' ,function () {
            $.ajax({
                url: `{{ route('admin:bank-exchanges.banks-by-branch') }}?type=${type}&branch_id=${$(this).val()}`,
                method: 'GET',
                success: function (response) {
                    $('select[name="from_bank_id"]').html(response.from_code);
                    $('select[name="from_bank_id"]').select2()
                    $('select[name="to_bank_id"]').html(response.to_code);
                    $('select[name="to_bank_id"]').select2()
                },
                error: function (err) {
                    swal("{{ __('Warning') }}" ,err.responseJSON.message ,'warning')
                }
            })
            $.ajax({
                url: "{{ route('branch-cost-center') }}?branch_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('select[name="cost_center_id"]').html(data.options);
                    $('select[name="cost_center_id"]').select2()
                }
            })
        })

        function update_from_bank_balance() {
            var from_bank_option = $('select[name="from_bank_id"]').find('option:selected')
            if ( from_bank_option.val() ) {
                $("#from_bank_balance").val(from_bank_option.data('balance'))
                $('input[name="from_bank_balance"]').val(from_bank_option.data('balance'))
                $('input[name="amount"]').removeAttr('readonly')
            } else {
                $("#from_bank_balance").val(0)
                $('input[name="from_bank_balance"]').val(0)
                $('input[name="amount"]').val(0)
                $('input[name="amount"]').attr('readonly' ,'readonly')
            }
        }

        function check_banks() {
            const from_bank = $('select[name="from_bank_id"]').find('option:selected').val(),
                to_bank = $('select[name="to_bank_id"]').find('option:selected').val()
            if (from_bank && to_bank && from_bank == to_bank && type == 'bank') {
                swal("{{ __('Warning') }}" ,"{{ __('words.banks-most-be-not-equal') }}" ,'warning')
            }
        }
    </script>
@endsection
