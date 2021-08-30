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
                                        onchange="changeBranch()" {{isset($stop_and_activate_assets) ? 'disabled':''}}
                                >
                                    <option value="">{{__('Select Branch')}}</option>

                                    @foreach($data['branches'] as $branch)
                                        <option value="{{$branch->id}}"
                                            {{isset($stop_and_activate_assets) && $stop_and_activate_assets->branch_id == $branch->id? 'selected':''}}
                                            {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                        >
                                            {{$branch->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{input_error($errors,'branch_id')}}

                            @if(isset($stop_and_activate_assets))
                                <input type="hidden" name="branch_id" value="{{$stop_and_activate_assets->branch_id}}">
                            @endif
                        </div>

                    </div>
                </div>
            @endif

            <div class="col-md-12">


                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label text-new1">{{__('Assets Groups')}}</label>

                        <div class="input-group" id="main_types">
                            <span class="input-group-addon fa fa-cubes"></span>
                            <select class="form-control js-example-basic-single" id="assetsGroups">
                                <option value="0">{{__('Select Assets Groups')}}</option>
                                @foreach($assetsGroups as $key => $type)
                                    <option value="{{$type->id}}"  >
                                        {{$type->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label text-new1">{{__('Assets')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon fa fa-cube"></span>
                            <select class="form-control js-example-basic-single" id="assetsOptions" name="asset_id">
                                <option value="">{{__('Select Assets')}}</option>
                                @foreach($assets as $asset)
                                    <option value="{{$asset->id}}" {{isset($stop_and_activate_assets) && $stop_and_activate_assets->asset_id == $asset->id? 'selected':''}}>
                                        {{$asset->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="text" name="date" class="form-control datepicker" id="date"
                                   value="{{old('date', isset($stop_and_activate_assets) ? $stop_and_activate_assets->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                        </div>
                        {{input_error($errors,'date')}}
                    </div>
                </div>
                <input type="hidden" name="status" value="{{old('status', isset($stop_and_activate_assets) ? $stop_and_activate_assets->status : $status)}}">



            </div>
        </div>



    </div>
</div>
