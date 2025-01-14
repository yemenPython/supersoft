<div class="row">
    <div class="">

        @if (authIsSuperAdmin())
            <div class="col-md-12">
                <div class="form-group">
                    <label> {{ __('Branches') }} </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                        <select class="form-control select2" name="branch_id" id="branch_id">
                            <option value=""> {{ __('Select Branch') }} </option>
                            @foreach(\App\Models\Branch::all() as $branch)
                                <option
                                    {{ (old('branch_id') == $branch->id) || (isset($item) && $item->branch_id == $branch->id) ? 'selected' : '' }}
                                    value="{{ $branch->id }}"> {{ $branch->name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
        @endif

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Name in Arabic')}}  {!! required() !!}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file"></li></span>
                    <input type="text" name="name_ar" class="form-control"
                           value="{{old('name_ar', isset($item)? $item->name_ar:'')}}">
             </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Name in English')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file"></li></span>
                    <input type="text" name="name_en" class="form-control"
                           value="{{old('name_en', isset($item)? $item->name_en:'')}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('ShortName Ar')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file"></li></span>
                    <input type="text" name="short_name_ar" class="form-control"
                           value="{{old('short_name_ar', isset($item)? $item->short_name_ar:'')}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('ShortName En')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-file"></li></span>
                    <input type="text" name="short_name_en" class="form-control"
                           value="{{old('short_name_en', isset($item)? $item->short_name_en:'')}}">
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="col-md-3">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Phone')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                    <input type="text" name="phone" class="form-control" id="phone"
                           value="{{old('phone', isset($item)? $item->phone:'')}}">
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <div class="col-md-3">
            <div class="form-group">
                <label for="address" class="control-label">{{__('Branch')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-home"></li></span>
                    <input type="text" name="branch" class="form-control"
                           value="{{old('branch', isset($item)? $item->branch:'')}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="address" class="control-label">{{__('Branch Code')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-code"></li></span>
                    <input type="text" name="code" class="form-control"
                           value="{{old('code', isset($item)? $item->code:'')}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="address" class="control-label">{{__('Swift Branch Code')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-code"></li></span>
                    <input type="text" name="swift_code" class="form-control"
                           value="{{old('swift_code', isset($item)? $item->swift_code:'')}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="address" class="control-label">{{__('Bank Website')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-home"></li></span>
                    <input type="url" name="website" class="form-control"
                           value="{{old('website', isset($item)? $item->website:'')}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="address" class="control-label">{{__('Bank Url')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-home"></li></span>
                    <input type="url" name="url" class="form-control"
                           value="{{old('url', isset($item)? $item->url : '')}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="address" class="control-label">{{__('Start Date With Bank')}} {!! required() !!}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-birthday-cake"></li></span>
                    <input type="date" name="date" class="form-control"
                           value="{{old('date', isset($item)? $item->date : now())}}">
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group">
                <label for="address" class="control-label">{{__('Address')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-home"></li></span>
                    <input type="text" name="address" class="form-control"
                           value="{{old('address', isset($item)? $item->address:'')}}">
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <label for="address" class="control-label" style="visibility: hidden;">{{__('Location')}}</label><br>
            <a data-toggle="modal" data-target="#boostrapModal-2" title="Cars info" class="btn btn-primary"
               style="margin-top:1px;cursor:pointer;font-size:12px;padding:3px 15px">
                <i class="fa fa-plus"> </i> {{__('Location')}}
            </a>
        </div>
    </div>

    <input type="hidden" name="lat" class="form-control" id="lat" value="{{old('lat', isset($item)? $item->lat:'')}}">
    <input type="hidden" name="long" class="form-control" id="lng" value="{{old('long', isset($item)? $item->long:'')}}">


    <div class="form-group col-md-3">
        <div class="form-group has-feedback">
            <label for="country" class="control-label">{{__('Select Country')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-globe"></span>
                <select name="country_id" class="form-control   js-example-basic-single"
                        id="country">
                    <option value="">{{__('Select')}}</option>
                    @foreach(\App\Models\Country::all() as $country)
                        <option value="{{$country->id}}"
                            {{($country->id === old('country_id'))  ||
                               (isset($item) && $item->country_id == $country->id) ? 'selected' : ''}}>
                            {{$country->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-group col-md-3">
        <div class="form-group has-feedback">
            <label for="city" class="control-label">{{__('Select City')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-globe"></span>
                <select name="city_id" class="form-control  js-example-basic-single" id="city">
                    <option value="">{{__('Select')}}</option>
                    @foreach(\App\Models\City::all() as $city)
                        <option value="{{$city->id}}"
                            {{($city->id === old('city_id'))  ||
                               (isset($item) && $item->city_id == $city->id) ? 'selected' : ''}}>
                            {{$city->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-group col-md-3">
        <div class="form-group has-feedback">
            <label for="area" class="control-label">{{__('Select Area')}}</label>
            <div class="input-group">
                <span class="input-group-addon fa fa-globe"></span>
                <select name="area_id" class="form-control  js-example-basic-single select2" id="area">
                    <option value="">{{__('Select')}}</option>
                    @foreach(\App\Models\Area::all() as $area)
                        <option value="{{$area->id}}"
                            {{($area->id === old('area_id'))  ||
                               (isset($item) && $item->area_id == $area->id) ? 'selected' : ''}}>
                            {{$area->name}}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

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

<div class="form-group row">
    <div class="col-md-12">

    </div>
</div>


<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>

@section('js-validation')
   <script>
       $("#country").change(function () {
           $.ajax({
               url: "{{ route('admin:country.cities') }}?country_id=" + $(this).val(),
               method: 'GET',
               success: function (data) {
                   $('#city').html(data.cities);
               }
           });
       });

       $("#city").change(function () {
           $.ajax({
               url: "{{ route('admin:city.areas') }}?city_id=" + $(this).val(),
               method: 'GET',
               success: function (data) {
                   $('#area').html(data.html);
               }
           });
       });
   </script>
@endsection
