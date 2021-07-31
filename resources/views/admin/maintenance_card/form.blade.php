<div class="row">

    @if(authIsSuperAdmin())
        <div class="col-md-12">
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                    <div class="input-group">

                        <span class="input-group-addon fa fa-file"></span>

                        <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                onchange="changeBranch()" {{isset($maintenanceCard) ? 'disabled':''}}
                        >
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($data['branches'] as $branch)
                                <option value="{{$branch->id}}"
                                    {{isset($maintenanceCard) && $maintenanceCard->branch_id == $branch->id? 'selected':''}}
                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                >
                                    {{$branch->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{input_error($errors,'branch_id')}}

                    @if(isset($maintenanceCard))
                        <input type="hidden" name="branch_id" value="{{$maintenanceCard->branch_id}}">
                    @endif
                </div>

            </div>
        </div>
    @endif

    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Assets Groups')}}</label>
                <div class="input-group">

                    <span class="input-group-addon fa fa-file"></span>

                    <select class="form-control js-example-basic-single" name="asset_group_id" id="asset_group_id"
                            onchange="getAssets()"
                    >
                        <option value="">{{__('Select Group')}}</option>

                        @foreach($data['asset_groups'] as $group)
                            <option value="{{$group->id}}">
                                {{$group->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'asset_group_id')}}
            </div>

        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Assets')}}</label>
                <div class="input-group">

                    <span class="input-group-addon fa fa-file"></span>

                    <select class="form-control js-example-basic-single" name="asset_id" id="asset_id">
                        <option value="">{{__('Select Asset')}}</option>

                        @foreach($data['assets'] as $asset)
                            <option value="{{$asset->id}}" {{isset($maintenanceCard) && $maintenanceCard->asset_id == $asset->id ? 'selected' : '' }}>
                                {{$asset->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'asset_id')}}
            </div>

        </div>

        <div class="col-md-3">
            <label style="display:block">{{__('Type')}}</label>

            <div class="col-md-6" style="padding:0">

                <div class="radio primary ">

                    <input type="radio" name="type" value="internal" id="internal" onclick="showSupplier()"
                        {{ !isset($salesInvoice) ? 'checked':'' }}
                        {{isset($maintenanceCard) && $maintenanceCard->type == 'internal' ? 'checked':''}} >
                    <label for="internal">{{__('Internal')}}</label>
                </div>
            </div>

            <div class="col-md-6" style="padding:0">

                <div class="radio primary ">

                    <input type="radio" name="type" id="external" value="external" onclick="showSupplier()"
                        {{isset($maintenanceCard) && $maintenanceCard->type == 'external' ? 'checked':''}} >
                    <label for="external">{{__('External')}}</label>
                </div>
            </div>
        </div>

        <div class="col-md-3" style=" {{!isset($maintenanceCard) || (isset($maintenanceCard) && $maintenanceCard->type != 'external') ? 'display: none;':''}}" id="suppliers_div">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Suppliers')}}</label>
                <div class="input-group">

                    <span class="input-group-addon fa fa-file"></span>

                    <select class="form-control js-example-basic-single" name="supplier_id" id="supplier_id">
                        <option value="">{{__('Select Supplier')}}</option>

                        @foreach($data['suppliers'] as $supplier)
                            <option value="{{$supplier->id}}" {{isset($maintenanceCard) && $maintenanceCard->supplier_id == $supplier->id ? 'selected':''}}>
                                {{$supplier->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'supplier_id')}}
            </div>
        </div>
    </div>


    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Receive status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-1" name="receive_status"
                        {{!isset($maintenanceCard)?'checked':''}}
                        {{isset($maintenanceCard) && $maintenanceCard->receive_status? 'checked':''}}
                    >
                    <label for="switch-1">{{__('Receive Status')}}</label>
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label for="date" class="control-label">{{__('Receive Date')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                <input type="date" name="receive_date" class="form-control"
                       value="{{isset($maintenanceCard)? $maintenanceCard->receive_date: now()->format('Y-m-d')}}">
            </div>
            {{input_error($errors,'receive_date')}}
        </div>

        <div class="form-group col-md-4">
            <div class="form-group">
                <label for="type_en" class="control-label">{{__('Receive Time')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="time" name="receive_time" class="form-control"
                           value="{{isset($maintenanceCard)? $maintenanceCard->receive_time : now()->format('h:i')}}"
                    >
                </div>
                {{input_error($errors,'receive_time')}}
            </div>
        </div>
    </div>

    <div class="col-md-12">

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Delivery Status')}}</label>
                <div class="switch primary">
                    <input type="checkbox" id="switch-2" name="delivery_status"
                        {{!isset($maintenanceCard)?'checked':''}}
                        {{isset($maintenanceCard) && $maintenanceCard->delivery_status? 'checked':''}}
                    >
                    <label for="switch-2">{{__('Delivery Status')}}</label>
                </div>
            </div>
        </div>

        <div class="form-group col-md-4">
            <label for="date" class="control-label">{{__('Delivery Date')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                <input type="date" name="delivery_date" class="form-control"
                       value="{{ isset($maintenanceCard) ? $maintenanceCard->delivery_date : now()->format('Y-m-d')}}">
            </div>
            {{input_error($errors,'delivery_date')}}
        </div>

        <div class="form-group col-md-4">
            <div class="form-group">
                <label for="type_en" class="control-label">{{__('Delivery Time')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="time" name="delivery_time" class="form-control"
                           value="{{ isset($maintenanceCard)? $maintenanceCard->delivery_time : now()->format('h:i')}}"
                    >
                </div>
                {{input_error($errors,'delivery_car_time')}}
            </div>
        </div>


        <div class="form-group col-md-12">
            <div class="form-group">
                <label for="car status" class="control-label">{{__('Notice')}}</label>
                <textarea id="editor1" name="note" cols="5" rows="5"
                >{{old('note', isset($maintenanceCard) ? $maintenanceCard->note:'')}}</textarea>
            </div>
        </div>

    </div>

    <div class="col-md-12">

        <div class="col-md-9">
            <div class="form-group">
                @include('admin.buttons._save_buttons')

            </div>
        </div>

    </div>
</div>


