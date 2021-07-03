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
                                    {{isset($assetReplacement) ? 'disabled':''}}>
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach($branches as $branch)
                                        <option
                                            value="{{$branch->id}}" {{isset($assetReplacement) && $assetReplacement->branch_id == $branch->id? 'selected':''}}
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
                                   value="{{old('number', isset($assetReplacement)? $assetReplacement->number : $number)}}">
                            <input type="hidden" name="number"
                                   value="{{old('number', isset($assetReplacement)? $assetReplacement->number : $number)}}">
                        </div>
                        {{input_error($errors,'number')}}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="date" class="control-label">{{__('Date')}}</label>
                        <div class="input-group">
                            <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                            <input type="date" name="date" class="form-control" id="date"
                                   value="{{old('date', isset($assetReplacement)? $assetReplacement->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
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
                                   value="{{old('time', isset($assetReplacement)? $assetReplacement->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
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
            <label for="inputStore" class="control-label">{{__('Assets Groups')}}</label>

            <div class="input-group" id="main_types">
                <span class="input-group-addon fa fa-cubes"></span>
                <select class="form-control js-example-basic-single" id="assetsGroups">
                    <option value="">{{__('Select Assets Groups')}}</option>
                    @foreach($assetsGroups as $key => $type)
                        <option value="{{$type->id}}">
                            {{$type->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="inputStore" class="control-label">{{__('Assets')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-cube"></span>
                <select class="form-control js-example-basic-single" id="assetsOptions">
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
    @include('admin.assets_replacements.table_items')
</div>


<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Before Replacement')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_before_replacement"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($assetReplacement) ? $assetReplacement->total_before_replacement : 0}}" class="form-control">
            <input id="total_before_replacement_hidden" type="hidden" name="total_before_replacement"
                   value="{{isset($assetReplacement) ? $assetReplacement->total_before_replacement : 0}}">
        </td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total After Replacement')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_after_replacement"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($assetReplacement) ? $assetReplacement->total_after_replacement : 0}}" class="form-control">
            <input id="total_after_replacement_hidden" type="hidden" name="total_after_replacement"
                   value="{{isset($assetReplacement) ? $assetReplacement->total_after_replacement : 0}}">
        </td>
        </tbody>
    </table>



</div>

