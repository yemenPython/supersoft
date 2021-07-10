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
                                onchange="changeBranch()" {{isset($purchaseAsset) ? 'disabled':''}}
                        >
                            <option value="">{{__('Select Branch')}}</option>

                            @foreach($data['branches'] as $branch)
                                <option value="{{$branch->id}}"
                                    {{isset($purchaseAsset) && $purchaseAsset->branch_id == $branch->id? 'selected':''}}
                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                >
                                    {{$branch->name}}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{input_error($errors,'branch_id')}}

                    @if(isset($purchaseAsset))
                        <input type="hidden" name="branch_id" value="{{$purchaseAsset->branch_id}}">
                    @endif
                </div>

            </div>
        </div>
    @endif

    <div class="col-md-12">

        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Invoice Number')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                    <input type="text" name="invoice_number" id="invoice_number" class="form-control" placeholder="{{__('Invoice Number')}}"
                           value="{{old('invoice_number', isset($purchaseAsset)? $purchaseAsset->invoice_number :'')}}">
                </div>
                {{input_error($errors,'invoice_number')}}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Date')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                    <input type="date" name="date" class="form-control" id="date"
                           value="{{old('date', isset($purchaseAsset) ? $purchaseAsset->date : \Carbon\Carbon::now()->format('Y-m-d'))}}">
                </div>
                {{input_error($errors,'date')}}
            </div>
        </div>
        </div>

        <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label for="date" class="control-label">{{__('Time')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-clock-o"></li></span>
                    <input type="time" name="time" class="form-control" id="time"
                           value="{{old('time',  isset($purchaseAsset) ? $purchaseAsset->time : \Carbon\Carbon::now()->format('H:i:s'))}}">
                </div>
                {{input_error($errors,'time')}}
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Suppliers')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-user"></span>

                    <select class="form-control js-example-basic-single" name="supplier_id" id="supplier_id"
                            onchange="selectSupplier()">
                        <option value="">{{__('Select')}}</option>

                        @foreach($data['suppliers'] as $supplier)
                            <option value="{{$supplier->id}}"
                                    data-discount="{{$supplier->group_discount}}"
                                    data-discount-type="{{$supplier->group_discount_type}}"
                                {{isset($purchaseAsset) && $purchaseAsset->supplier_id == $supplier->id? 'selected':''}}>
                                {{$supplier->name}}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{input_error($errors,'supplier_id')}}
            </div>
        </div>
        </div>


        </div>

        <div class="row center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


            <div class="col-md-6">
                <div class="form-group has-feedback">
                    <label for="inputStore" class="control-label">{{__('Assets Groups')}}</label>

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
                    <label for="inputStore" class="control-label">{{__('Assets')}}</label>
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







    @include('admin.purchase-assets.table_items')

    </div>


    <div class="row buttom-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


    @include('admin.purchase-assets.financial_details')

</div>

        
</div>

<div class="col-md-12">
            <div class="form-group">
                <label> {{ __('Notes') }} </label>
                <textarea class="form-control" name="note" id="note"
                          placeholder="{{ __('Notes') }}">{{ old('notes') }}</textarea>
            </div>
            {{input_error($errors,'note')}}
        </div>

</div>
