@extends('admin.layouts.app')
@section('title')
<title>{{ __('Edit Asset Group') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin:assetsGroup.index')}}"> {{__('Assets Groups')}}</a></li>
                <li class="breadcrumb-item"> {{__('Edit Asset Group')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-folder-o"></i>{{__('Edit Asset Group')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h1>
            <div class="box-content">
                <form method="post" action="{{route('admin:assetsGroup.update', ['id' => $assetGroup->id])}}" class="form">
                    @csrf
                    @method('put')

                    <div class="row">
                        <div class="col-md-12">
                        @if (authIsSuperAdmin())
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> {{ __('Branches') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                        <select class="form-control select2" name="branch_id">
                                            <option value=""> {{ __('Select Branch') }} </option>
                                            @foreach($branches as $branch)
                                                <option {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                                    value="{{ $branch->id }}" @if($branch->id == $assetGroup->branch_id) selected @endif> {{ $branch->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{input_error($errors,'branch_id')}}
                                </div>
                            </div>
                        @else
                            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
                        @endif
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="inputNameAR" class="control-label">{{__('Asset group in Arabic')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                                        <input type="text" name="name_ar" class="form-control" value="{{$assetGroup->name_ar}}" id="inputNameAR" placeholder="{{__('Asset group in Arabic')}}">
                                    </div>
                                    {{input_error($errors,'name_ar')}}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group has-feedback">
                                    <label for="inputNameEN" class="control-file-o">{{__('Asset group in English')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                                        <input type="text" name="name_en" class="form-control" id="inputNameEN" value="{{$assetGroup->name_en}}" placeholder="{{__('Asset group in English')}}">
                                    </div>
                                    {{input_error($errors,'name_en')}}
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('Type') }} </label>
                                            <div class="input-group">
                                                <ul class="list-inline">
                                                    <li>
                                                        <div class="radio info">
                                                            <input type="radio" id="radio_manual" name="consumption_type"
                                                                   value="manual" {{  $assetGroup->consumption_type=='manual' ? 'checked' :''}}>
                                                            <label for="radio_status_sale">{{ __('Manual') }}</label>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="radio info">
                                                            <input id="radio_automatic" type="radio" name="consumption_type"
                                                                   value="automatic" {{  $assetGroup->consumption_type=='automatic' ? 'checked' :''}}>
                                                            <label
                                                                for="radio_automatic">{{ __('Automatic') }}</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            {{input_error($errors,'consumption_type')}}
                                        </div>

                                    </div>
                                    <div class="col-md-6 type_manual" >
                                        <div class="form-group has-feedback">
                                            <label for="annual_consumtion_rate"
                                                   class="control-label">{{__('annual consumption rate')}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                                <input type="text" name="annual_consumtion_rate" value="0"
                                                       class="form-control" id="annual_consumption_rate"
                                                       placeholder="{{__('annual consumption rate')}}">
                                            </div>
                                            {{input_error($errors,'annual_consumtion_rate')}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="col-md-6 type_automatic" style="display: none">
                                        <div class="form-group has-feedback">
                                            <label for="age_years" class="control-label">{{__('Asset Age (Years)')}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"></span>
                                                <input type="number" name="age_years" step="1" class="form-control"
                                                       value="{{$assetGroup->age_years}}"
                                                       pattern="\d+"
                                                       id="age_years" placeholder="{{__('Asset Age (Years)')}}">
                                            </div>
                                            {{input_error($errors,'age_years')}}
                                        </div>
                                    </div>
                                    <div class="col-md-6 type_automatic" style="display: none">
                                        <div class="form-group has-feedback">
                                            <label for="age_months" class="control-label">{{__('Asset Age (Months)')}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"></span>
                                                <input type="number" name="age_months" step="1"  class="form-control"
                                                       value="{{$assetGroup->age_months}}"
                                                       pattern="\d+"
                                                       id="age_months" placeholder="{{__('Asset Age (Months)')}}">
                                            </div>
                                            {{input_error($errors,'age_months')}}
                                        </div>
                                    </div>
                                    <div class="col-md-6 type_automatic" style="display: none">
                                        <div class="form-group has-feedback">
                                            <label for="consumption_period"
                                                   class="control-label">{{__('Consumption Period (Months)')}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"></span>
                                                <input type="number" name="consumption_period" step="1" class="form-control"
                                                       id="consumption_period"
                                                       pattern="\d+"
                                                       value="{{$assetGroup->consumption_period}}"
                                                       placeholder="{{__('Consumption Period (Months)')}}">
                                            </div>
                                            {{input_error($errors,'consumption_period')}}
                                        </div>
                                    </div>
                                    <div class="col-md-6 type_automatic" style="display: none">
                                        <div class="form-group">
                                            <label> {{ __('Type') }} </label>
                                            <div class="input-group">
                                                <ul class="list-inline">
                                                    <li>
                                                        <div class="radio info">
                                                            <input type="radio" id="radio_status_sale" name="consumption_for"
                                                                   value="asset"
                                                                {{ isset($assetGroup) && $assetGroup->consumption_for=='asset' ? 'checked' :''}}
                                                                {{ isset($assetGroup) ? 'disabled' :''}}
                                                            >
                                                            <label for="radio_status_sale">{{ __('Assets') }}</label>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="radio info">
                                                            <input id="radio_status_exclusion" type="radio" name="consumption_for"
                                                                   value="expenses"
                                                                {{ isset($assetGroup) && $assetGroup->consumption_for=='expenses' ? 'checked' :''}}
                                                                {{ isset($assetGroup) ? 'disabled' :''}}
                                                            >
                                                            <label
                                                                for="radio_status_exclusion">{{ __('Expenses') }}</label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="radio info">
                                                            <input id="radio_status_all" type="radio" name="consumption_for"
                                                                   value='both'
                                                                {{ isset($assetGroup) && $assetGroup->consumption_for=='both' ? 'checked' :''}}
                                                                {{ isset($assetGroup) ? 'disabled' :''}}
                                                            >
                                                            <label
                                                                for="radio_status_all">{{ __('Both') }}</label>
                                                        </div>
                                                    </li>
                                                    @if(isset($assetGroup))
                                                        <input type="hidden" name="consumption_for"
                                                               value='{{$assetGroup->consumption_for}}'>
                                                    @endif
                                                </ul>
                                            </div>
                                            {{input_error($errors,'consumption_for')}}
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="inputNameAR" class="control-label">{{__('total group consumption')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon border3"><i class="fa fa-money"></i></span>
                                            <input disabled="disabled" value="{{$assetGroup->total_consumtion}}" type="text" name="name_ar" class="form-control border3" id="inputNameAR" placeholder="{{__('total consumption')}}">
                                        </div>
                                        {{input_error($errors,'name_ar')}}
                                    </div>
                                </div>



                        </div>
                    </div>
                  <div class="col-md-12">
                    <div class="form-group">
                        @include('admin.buttons._save_buttons')
                     </div>
                     </div>

                </form>
            </div>
            <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\AssetGroupRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
@section('js')
    <script>
        function checkType() {
            var value = $("input[name='consumption_type']:checked").val();
            if (value == 'manual') {
                $('.type_automatic').hide();
                $('.type_manual').show();
            } else if (value == 'automatic') {
                $('.type_manual').hide();
                $('.type_automatic').show();
            }
        }

        $(function () {
            if ('{{$assetGroup->consumption_type}}'=='automatic'){
                $('.type_manual').hide();
                $('.type_automatic').show();
            }
            $("input[type='radio'][name='consumption_type']").click(function () {

                checkType();
            });
        })
    </script>
@endsection
