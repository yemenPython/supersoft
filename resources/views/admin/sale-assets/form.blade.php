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
                                        onchange="changeBranch()" {{isset($saleAsset) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($data['branches'] as $branch)
                                        <option value="{{$branch->id}}"
                                            {{isset($saleAsset) && $saleAsset->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                            @if(isset($saleAsset))
                                <input type="hidden" name="branch_id" value="{{$saleAsset->branch_id}}">
                            @endif
                        </div>

                    </div>
                </div>
            @endif

            <div class="col-md-12">

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputNameAr" class="control-label">{{__('Number')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                            <input type="text" name="number" id="number" class="form-control" readonly
                                   placeholder="{{__('Number')}}"
                                   value="{{old('number', isset($saleAsset)? $saleAsset->number :$number)}}">
                        </div>
                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date" class="form-control datepicker" id="date"
                                   value="{{old('date', isset($saleAsset) ? $saleAsset->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date')}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Time')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                            <input type="time" name="time" class="form-control" id="time"
                                   value="{{old('time',  isset($saleAsset) ? $saleAsset->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                        </div>
                        {{input_error($errors,'time')}}
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label> {{ __('Type') }} </label>
                        <div class="input-group">
                            <ul class="list-inline">
                                <li>
                                    <div class="radio info">
                                        <input type="radio" id="radio_status_sale" name="type"
                                               value="sale"
                                               @if(isset($saleAsset) && $saleAsset->type=='sale')
                                               checked
                                               @elseif(!isset($saleAsset))
                                               checked
                                            @endif
                                        >
                                        <label for="radio_status_sale">{{ __('Sale') }}</label>
                                    </div>
                                </li>

                                <li>
                                    <div class="radio info">
                                        <input id="radio_status_exclusion" type="radio" name="type"
                                               @if(isset($saleAsset) && $saleAsset->type=='exclusion')
                                               checked
                                               @endif
                                               value="exclusion">
                                        <label for="radio_status_exclusion">{{ __('exclusion') }}</label>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


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


            @include('admin.sale-assets.table_items')

        </div>


        <div class="row bottom-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


            @include('admin.sale-assets.financial_details')

        </div>

        <div class="col-md-12">
                    <div class="form-group">
                        <label> {{ __('Notes') }} </label>
                        <textarea class="form-control" name="note" id="note"
                                  placeholder="{{ __('Notes') }}">{{isset($saleAsset)? $saleAsset->note:old('notes') }}</textarea>
                    </div>
                    {{input_error($errors,'note')}}
                </div>


    </div>
</div>
