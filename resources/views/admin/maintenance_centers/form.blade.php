<div class="row">
    <div class="col-md-12">
        @if(authIsSuperAdmin())
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                <div class="input-group">
                    <span class="input-group-addon fa fa-file"></span>
                    <select class="form-control js-example-basic-single" name="branch_id" id="branch_id">
                        <option value="">{{__('Select Branches')}}</option>
                        @foreach($branches as $k => $v)
                            <option
                                value="{{$k}}" {{isset($item) && $item->branch_id == $k? 'selected':''}}
                                {{request()->has('branch_id') && request()['branch_id'] == $k? 'selected':''}}>
                                {{$v}}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{input_error($errors,'branch_id')}}
            </div>
        @endif
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Name in Arabic')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-user"></li></span>
                <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                       placeholder="{{__('Name in Arabic')}}"
                       value="{{old('name_ar', isset($item)? $item->name_ar:'')}}">
            </div>
            {{input_error($errors,'name_ar')}}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="inputName" class="control-label">{{__('Name In English')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-user"></li></span>
                <input type="text" name="name_en" class="form-control" id="inputNameEn"
                       placeholder="{{__('Name in English')}}"
                       value="{{old('name_en', isset($item)? $item->name_en:'')}}">
            </div>
            {{input_error($errors,'name_en')}}
        </div>
    </div>
</div>

<div class="">
    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="country" class="control-label">{{__('Select Country')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-globe"></span>
                <select name="country_id" class="form-control   js-example-basic-single" id="country">
                    <option value="">{{__('Select Country')}}</option>
                    @foreach(\App\Models\Country::all() as $country)
                        <option
                            value="{{$country->id}}" {{isset($item) && $item->country_id == $country->id? 'selected':''}}>
                            {{$country->name}}
                        </option>
                    @endforeach
                </select>
                {{input_error($errors,'country_id')}}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="city" class="control-label">{{__('Select City')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-globe"></span>
                <select name="city_id" class="form-control  js-example-basic-single" id="city">
                    @foreach(\App\Models\City::all() as $city)
                        <option
                            value="{{$city->id}}" {{isset($item) && $item->city_id == $city->id? 'selected':''}}>
                            {{$city->name}}
                        </option>
                    @endforeach
                </select>
                {{input_error($errors,'city_id')}}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group has-feedback">
            <label for="area" class="control-label">{{__('Select Area')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-globe"></span>
                <select name="area_id" class="form-control  js-example-basic-single select2" id="area">
                    @foreach(\App\Models\Area::all() as $area)
                        <option
                            value="{{$area->id}}" {{isset($item) && $item->area_id == $area->id? 'selected':''}}>
                            {{$area->name}}
                        </option>
                    @endforeach
                </select>
                {{input_error($errors,'area_id')}}
            </div>
        </div>
    </div>
</div>

<div class="">
    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Email')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-envelope"></li></span>
                <input type="email" name="email" class="form-control" id="inputNameEn"
                       placeholder="{{__('Email')}}"
                       value="{{old('email', isset($item)? $item->email:'')}}">
            </div>
            {{input_error($errors,'email')}}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Phone 1')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                <input type="text" name="phone_1" class="form-control" id="phone_1"
                       placeholder="{{__('Phone 1')}}"
                       value="{{old('phone_1', isset($item)? $item->phone_1:'')}}">
            </div>
            {{input_error($errors,'phone_1')}}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="inputNameAr" class="control-label">{{__('Phone 2')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                <input type="text" name="phone_2" class="form-control" id="phone_2"
                       placeholder="{{__('Phone 2')}}"
                       value="{{old('phone_2', isset($item)? $item->phone_2:'')}}">
            </div>
            {{input_error($errors,'phone_2')}}
        </div>
    </div>
</div>


<div class="">
    <div class="form-group  col-md-4">
        <label for="inputNameAr" class="control-label">{{__('Fax Number')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-envelope"></li></span>
            <input type="text" name="fax" class="form-control" id="phone_2"
                   placeholder="{{__('Fax')}}" value="{{old('fax', isset($item)? $item->fax:'')}}">
        </div>
        {{input_error($errors,'fax')}}
    </div>

    <div class="form-group col-md-4">
        <label for="inputNameAr" class="control-label">{{__('Commercial Number')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
            <input type="text" name="commercial_number" class="form-control" id="commercial_number"
                   placeholder="{{__('Commercial Number')}}"
                   value="{{old('commercial_number', isset($item)? $item->commercial_number:'')}}">
        </div>
        {{input_error($errors,'commercial_number')}}
    </div>
</div>

<div class="">
    <div class="col-md-4">
        <div class="form-group  ">
            <label for="address" class="control-label">{{__('Address')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
                <input type="text" name="address" class="form-control" id="address"
                       placeholder="{{__('Address')}}"
                       value="{{old('address', isset($item)? $item->address:'')}}">
            </div>
            {{input_error($errors,'address')}}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group  ">
            <label for="address" class="control-label">{{__('Tax Number')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-cart-arrow-down"></li></span>
                <input type="text" name="tax_number" class="form-control" id="tax_number"
                       placeholder="{{__('Tax Number')}}"
                       value="{{old('tax_number', isset($item)? $item->tax_number:'')}}">
            </div>
            {{input_error($errors,'tax_number')}}
        </div>
    </div>
    <div class="col-md-4">
        <label for="address" class="control-label" style="visibility: hidden;">
            {{__('Location')}}
        </label><br>
        <a data-toggle="modal" data-target="#boostrapModal-2" title="Cars info"
           class="btn btn-primary"
           style="margin-top:1px;cursor:pointer;font-size:12px;padding:3px 15px">
            <i class="fa fa-plus"> </i> {{__('Location')}}
        </a>
    </div>
</div>

<div class="">
    <div class="col-md-4">
        <div class="form-group  ">
            <label for="address" class="control-label">{{__('Lat')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-map"></li></span>
                <input type="text" name="lat" class="form-control" id="lat"
                       placeholder="{{__('Lat')}}" value="{{old('lat', isset($item)? $item->lat:'')}}">
            </div>
            {{input_error($errors,'lat')}}
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group  ">
            <label for="address" class="control-label">{{__('Long')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-map"></li></span>
                <input type="text" name="long" class="form-control" id="lng"
                       placeholder="{{__('Long')}}" value="{{old('long', isset($item)? $item->long:'')}}">
            </div>
            {{input_error($errors,'long')}}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group  ">
            <label for="address" class="control-label">{{__('Company Code')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-code"></li></span>
                <input type="text" name="company_code" class="form-control" id="company_code"
                       placeholder="{{__('Company Code')}}"
                       value="{{old('company_code', isset($item)? $item->company_code: '')}}">
            </div>
            {{input_error($errors,'company_code')}}
        </div>
    </div>
</div>


<div class="form-group row">
    <div class="col-md-4">
        <div class="form-group  ">
            <label for="address" class="control-label">{{__('Identity Number')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-usd"></li></span>
                <input type="text" name="identity_number" class="form-control"
                       id="identity_number"
                       placeholder="{{__('Identity Number')}}"
                       value="{{old('identity_number', isset($item)? $item->identity_number :'')}}">
            </div>
            {{input_error($errors,'identity_number')}}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group  ">
            <label for="tax_file_number" class="control-label">{{__('Tax File Number')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-location-arrow"></li></span>
                <input type="text" name="tax_file_number" class="form-control"
                       id="tax_file_number"
                       placeholder="{{__('Tax File Number')}}"
                       value="{{old('tax_file_number', isset($item)? $item->tax_file_number :'')}}">
            </div>
            {{input_error($errors,'tax_file_number')}}
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group  ">
            <label for="commercial_record_area" class="control-label">{{__('Commercial Record Area')}}</label>
            <div class="input-group">
                <span class="input-group-addon"><li class="fa fa-location-arrow"></li></span>
                <input type="text" name="commercial_record_area" class="form-control"
                       id="commercial_record_area"
                       placeholder="{{__('Commercial Record Area')}}"
                       value="{{old('commercial_record_area', isset($item)? $item->commercial_record_area :'')}}">
            </div>
            {{input_error($errors,'commercial_record_area')}}
        </div>
    </div>

    <div class="">
        <div class="form-group col-md-12">
            <label for="inputDescription" class="control-label">{{__('Description')}}</label>
            <div class="input-group">
        <textarea name="description" class="form-control" rows="4" cols="150"
        >{{old('description', isset($item)? $item->description :'')}}</textarea>
            </div>
            {{input_error($errors,'description')}}
        </div>
    </div>

    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                <div class="switch primary">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" id="switch-1" name="status" {{!isset($item)?'checked':''}}
                    {{isset($item) && $item->status? 'checked':''}}
                    value="1">
                    <label for="switch-1">{{__('Active')}}</label>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>
