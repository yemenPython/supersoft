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
                                    {{isset($settlement) ? 'disabled':''}}>
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($branches as $branch)
                                        <option
                                            value="{{$branch->id}}" {{isset($settlement) && $settlement->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach

                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                        </div>

                        @if(isset($settlement))
                            <input type="hidden" name="branch_id" value="{{$settlement->branch_id}}">
                        @endif

                    </div>

                </div>
            @endif

            <div class="col-md-12">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                            <input type="text" name="number" class="form-control" placeholder="{{__('Number')}}"
                                   disabled
                                   value="{{old('number', isset($settlement)? $settlement->special_number : $number)}}">
                        </div>
                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="control-label" style="display:block"> {{__('settlement type')}}</label>

                    <ul class="list-inline">
                        <li>
                            <div class="radio primary">
                                <input type="radio" name="type" value="positive" id="positive"
                                    {{ !isset($settlement) ? 'checked':'' }}
                                    {{isset($settlement) && $settlement->type == 'positive' ? 'checked':''}} >
                                <label for="positive">{{__('Positive')}}</label>
                            </div>
                        </li>
                        <li>
                            <div class="radio primary">
                                <input type="radio" name="type" id="negative" value="negative"
                                    {{isset($settlement) && $settlement->type == 'negative' ? 'checked':''}} >
                                <label for="negative">{{__('Negative')}}</label>
                            </div>

                </div>
            </div>

            <div class="col-md-12">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date" class="form-control datepicker" id="date"
                                   value="{{old('date', isset($settlement)? $settlement->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date')}}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Time')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                            <input type="time" name="time" class="form-control" id="time"
                                   value="{{old('time', isset($settlement)? $settlement->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>


            </div>

            </li>
            </ul>


        </div>
    </div>
</div>

<div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="inputStore" class="control-label text-new1">{{__('Main Types')}}</label>

            <div class="input-group" id="main_types">

                <span class="input-group-addon fa fa-cubes"></span>

                <select class="form-control js-example-basic-single" id="main_types_select" onchange="dataByMainType()">

                    <option value="">{{__('Select Type')}}</option>

                    @foreach($mainTypes as $key => $type)
                        <option value="{{$type->id}}" data-order="{{$key+1}}">
                            {{$key+1}} . {{$type->type}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="inputStore" class="control-label text-new1">{{__('Sub Types')}}</label>

            <div class="input-group" id="sub_types">

                <span class="input-group-addon fa fa-cube"></span>

                <select class="form-control js-example-basic-single" id="sub_types_select" onchange="dataBySubType()">

                    <option value="">{{__('Select Type')}}</option>

                    @foreach($subTypes as $id=>$type)
                        <option value="{{$id}}">
                            {{$type}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="inputStore" class="control-label text-new1">{{__('Parts')}}</label>

            <div class="input-group" id="parts">

                <span class="input-group-addon fa fa-gear"></span>

                <select class="form-control js-example-basic-single" id="parts_select" onchange="selectPart()">

                    <option value="">{{__('Select Part')}}</option>

                    @foreach($parts as $part)
                        <option value="{{$part->id}}">
                            {{$part->Prices->first() ? $part->Prices->first()->barcode . ' - ' : ''}} {{$part->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>


    @include('admin.settlements.table_items')

</div>

<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{__('Total quantity')}}</label>
        <td style="background:#FFC5D7">

            <input type="text" disabled id="total_quantity"
                   style="background:#FFC5D7; border:none;text-align:center !important;" class="form-control"
                   value="{{isset($settlement) ? $totalQuantity : 0}}">
        </td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</label>
        <td style="background:#F9EFB7">
            <input type="text" disabled id="total_price"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($settlement) ? $settlement->total : 0}}" class="form-control">
        </td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</label>
        <td style="background:#D2F4F6">
                <textarea name="description" style="background:#D2F4F6;border:none;" class="form-control" rows="4"
                          cols="150"
                >{{old('description', isset($settlement)? $settlement->description :'')}}</textarea>

            {{input_error($errors,'description')}}
        </td>
        </tbody>
    </table>

</div>


<div class="col-md-12" >

    <div class="bottom-data-wg" style="display:inline-block;width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:25px 15px 3px">

        <div class="col-md-12" style="color: white; margin-bottom: 60px; margin-right: -15px;">
            <div class="ribbon ribbon-r bg-secondary show-ribbon" style="background: rgb(86, 133, 204) !important;">
                <p class="mb-0">{{__('Employees')}}</p>
            </div>
        </div>

        <div id="employees_data">

            @if(isset($settlement))

                @foreach($settlement->employees as $employeeIndex=> $settlementEmployee)

                    @php
                        $employeeIndex +=1;
                    @endphp

                    @include('admin.settlements.employees.ajax_employee_percent')

                @endforeach
            @endif

        </div>

        <div class="col-md-12">
            <div class="form-group">
                <button type="button" class="btn btn-primary fa fa-plus" onclick="newEmployee()"></button>
            </div>
        </div>

    </div>
</div>

<input type="hidden" id="employees_count" value="{{isset($settlement) ? $settlement->employees->count() : 0 }}">


