@php
    $currency = \App\Models\Currency::where('is_main_currency', 1)->first();
@endphp
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
                                    {{isset($lockerOpeningBalance) ? 'disabled':''}}>
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach($branches as $branch)
                                        <option
                                            value="{{$branch->id}}" {{isset($lockerOpeningBalance) && $lockerOpeningBalance->branch_id == $branch->id? 'selected':''}}
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
                                   value="{{old('number', isset($lockerOpeningBalance)? $lockerOpeningBalance->number : $number)}}">
                            <input type="hidden" name="number"
                                   value="{{old('number', isset($lockerOpeningBalance)? $lockerOpeningBalance->number : $number)}}">
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
                                   value="{{old('date', isset($lockerOpeningBalance)? $lockerOpeningBalance->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                                   value="{{old('time', isset($lockerOpeningBalance)? $lockerOpeningBalance->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>

<div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    <div class="col-md-6">
        <div class="form-group has-feedback">
            <label for="inputStore" class="control-label text-new1">{{__('Status')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-check"></span>
                <select class="form-control js-example-basic-single" name="status">
                    <option value="progress" {{isset($lockerOpeningBalance) && $lockerOpeningBalance->status == 'progress' ? 'selected' : ''}}>{{__('Progress')}}</option>
                    <option value="accepted" {{isset($lockerOpeningBalance) && $lockerOpeningBalance->status == 'accepted' ? 'selected' : ''}}>{{__('Accept')}}</option>
                    <option value="rejected" {{isset($lockerOpeningBalance) && $lockerOpeningBalance->status == 'rejected' ? 'selected' : ''}}>{{__('Reject')}}</option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group has-feedback">
            <label for="inputStore" class="control-label text-new1">{{__('Lockers')}}</label>
            <div class="input-group" id="main_types">
                <span class="input-group-addon fa fa-cubes"></span>
                <select class="form-control js-example-basic-single" id="selectLocker">
                    <option value="">{{__('Select')}}</option>
                    @foreach($lockers as $key => $locker)
                        <option value="{{$locker->id}}">
                            {{$locker->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @include('admin.locker_opening_balance.table_items')
</div>


<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">
    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{__('Total Current Balance')}}</th>
        <td style="background:#FFC5D7">
            <input type="text" readonly id="total_current_balance_items"
                   style="background:#FFC5D7;border:none;text-align:center !important;"
                   value="{{isset($lockerOpeningBalance) ? $lockerOpeningBalance->current_total : 0}}" class="form-control">
            <input id="total_current_balance_items_hidden" type="hidden" name="current_total"
                   value="{{isset($lockerOpeningBalance) ? $lockerOpeningBalance->current_total : 0}}">
        </td>
        @if ($setting->active_multi_currency && $currency)
            <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{$currency->name}} - {{$currency->symbol}}</th>
        @endif

        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Added Balance')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_added_balance_items"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($lockerOpeningBalance) ? $lockerOpeningBalance->added_total : 0}}" class="form-control">
            <input id="total_added_balance_items_hidden" type="hidden" name="added_total"
                   value="{{isset($lockerOpeningBalance) ? $lockerOpeningBalance->added_total : 0}}">
        </td>
        @if ($setting->active_multi_currency && $currency)
            <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{$currency->name}} - {{$currency->symbol}}</th>
        @endif
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#ffeb7f !important;color:black !important">{{__('Total')}}</th>
        <td style="background:#ffea7e">
            <input type="text" readonly id="total_balance_items"
                   style="background:#ffeb7d;border:none;text-align:center !important;"
                   value="{{isset($lockerOpeningBalance) ? $lockerOpeningBalance->total : 0}}" class="form-control">
            <input id="total_balance_items_items_hidden" type="hidden" name="total"
                   value="{{isset($lockerOpeningBalance) ? $lockerOpeningBalance->total : 0}}">
        </td>
        @if ($setting->active_multi_currency && $currency)
            <th style="width:30%;background:#ffeb7f !important;color:black !important">{{$currency->name}} - {{$currency->symbol}}</th>
        @endif
        </tbody>

    </table>



</div>
<div class="col-md-12">
    <div class="form-group">
        <label> {{ __('Notes') }} </label>
        <textarea class="form-control" name="notes" id="note"
                  placeholder="{{ __('Notes') }}">{{isset($lockerOpeningBalance)? $lockerOpeningBalance->notes : old('notes') }}</textarea>
    </div>
    {{input_error($errors,'note')}}
</div>

