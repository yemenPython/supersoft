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
                                        onchange="changeBranch()" {{isset($consumptionAsset) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($data['branches'] as $branch)
                                        <option value="{{$branch->id}}"
                                            {{isset($consumptionAsset) && $consumptionAsset->branch_id == $branch->id? 'selected':''}}
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
            @else
                <input type="hidden" name="branch_id" value="{{auth()->user()->branch_id}}" id="branch_id_hidden">
            @endif
            @if(isset($consumptionAsset))
                <input type="hidden" name="branch_id" value="{{$consumptionAsset->branch_id}}">
            @endif
            <div class="col-md-12">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                            <input type="text" name="number" id="number" class="form-control" readonly
                                   placeholder="{{__('Number')}}"
                                   value="{{old('number', isset($consumptionAsset)? $consumptionAsset->number :$number)}}">
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
                                   value="{{old('date', isset($consumptionAsset) ? $consumptionAsset->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                                   value="{{old('time',  isset($consumptionAsset) ? $consumptionAsset->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>

            </div>
        </div>

        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


            <div class="col-md-4">
                <div class="form-group">
                    <label for="date" class="control-label text-new1">{{__('Date From')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                        <input type="text" name="date_from" class="form-control datepicker" id="date_from"
                               value="{{old('date_from', isset($consumptionAsset) ? $consumptionAsset->date_from : '')}}"
                        >
                    </div>
                    {{input_error($errors,'date_from')}}
                </div>
            </div>


            <div class="col-md-4">
                <div class="form-group">
                    <label for="date" class="control-label text-new1">{{__('Date to')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                        <input type="text" name="date_to" class="form-control datepicker" id="date_to"
                               value="{{old('date_to', isset($consumptionAsset) ? $consumptionAsset->date_to :'')}}">
                    </div>
                    {{input_error($errors,'date_to')}}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label> {{ __('Type') }} </label>
                    <div class="input-group">
                        <ul class="list-inline">
                            <li>
                                <div class="radio info">
                                    <input type="radio" id="radio_status_sale" name="type"
                                           value="asset"
                                        {{ isset($consumptionAsset) && $consumptionAsset->type=='asset' ? 'checked' :''}}
                                        {{ isset($consumptionAsset) ? 'disabled' :''}}
                                    >
                                    <label for="radio_status_sale">{{ __('Assets') }}</label>
                                </div>
                            </li>

                            <li>
                                <div class="radio info">
                                    <input id="radio_status_exclusion" type="radio" name="type"
                                           value="expenses"
                                        {{ isset($consumptionAsset) && $consumptionAsset->type=='expenses' ? 'checked' :''}}
                                        {{ isset($consumptionAsset) ? 'disabled' :''}}
                                    >
                                    <label
                                        for="radio_status_exclusion">{{ __('Expenses') }}</label>
                                </div>
                            </li>
                            <li>
                                <div class="radio info">
                                    <input id="radio_status_all" type="radio" name="type"
                                           value='both'
                                        {{ isset($consumptionAsset) && $consumptionAsset->type=='both' ? 'checked' :''}}
                                        {{ isset($consumptionAsset) ? 'disabled' :''}}
                                    >
                                    <label
                                        for="radio_status_all">{{ __('Both') }}</label>
                                </div>
                            </li>
                            @if(isset($consumptionAsset))
                                <input type="hidden" name="type"
                                       value='{{$consumptionAsset->type}}'>
                            @endif
                        </ul>
                    </div>
                    {{input_error($errors,'type')}}
                </div>

            </div>


            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label text-new1">{{__('Assets Groups')}}</label>

                    <div class="input-group" id="main_types">
                        <span class="input-group-addon fa fa-cubes"></span>
                        <select class="form-control js-example-basic-single" id="assetsGroups">
                            <option value="0">{{__('Select Assets Groups')}}</option>
                            @foreach($assetsGroups as $key => $type)
                                <option value="{{$type->id}}">
                                    {{$type->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label text-new1">{{__('Assets')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon fa fa-cube"></span>
                        <select class="form-control js-example-basic-single" id="assetsOptions" name="asset_id">
                            <option value="">{{__('Select Assets')}}</option>
                            @foreach($assets as $asset)
                                <option value="{{$asset->id}}">
                                    {{$asset->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            @include('admin.consumption-assets.table_items')

        </div>


        <div class="bottom-data-wg"
             style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">


            @include('admin.consumption-assets.financial_details')

        </div>

        <div class="col-md-12">
            <br>
            <div class="form-group">
                <label> {{ __('Notes') }} </label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file"></li></span>
                    <textarea class="form-control" name="note" id="note"
                              placeholder="{{ __('Notes') }}">{{isset($consumptionAsset)? $consumptionAsset->note:old('notes') }}</textarea>
                </div>
                {{input_error($errors,'note')}}
            </div>
        </div>

    </div>
</div>
