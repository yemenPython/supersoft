@extends('admin.layouts.app')
@section('title')
<title>{{ __('Create Asset Group') }} </title>
@endsection
@section('content')
        <div class="row small-spacing">
            <nav>
                <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                    <li class="breadcrumb-item"><a href="{{route('admin:assetsGroup.index')}}"> {{__('Assets Groups')}}</a></li>
                <li class="breadcrumb-item"> {{__('Create Asset Group')}}</li>
                </ol>
            </nav>
            <div class="col-xs-12">
                <div class=" card box-content-wg-new bordered-all primary">
                    <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-folder-o"></i>{{__('Create Asset Group')}}
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
                    <form  method="post" action="{{route('admin:assetsGroup.store')}}" class="form">
                        @csrf
                        @method('post')


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
                                                    value="{{ $branch->id }}"> {{ $branch->name }} </option>
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
                                        <input type="text" name="name_ar" class="form-control" id="inputNameAR" placeholder="{{__('Asset group in Arabic')}}">
                                    </div>
                                    {{input_error($errors,'name_ar')}}
                                </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="inputNameEN" class="control-label">{{__('Asset group in English')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                                            <input type="text" name="name_en" class="form-control" id="inputNameEN" placeholder="{{__('Asset group in English')}}">
                                        </div>
                                        {{input_error($errors,'name_en')}}
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">

                                <!-- <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="inputNameAR" class="control-label">{{__('total consumption')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                                            <input type="text" name="name_ar" class="form-control" id="inputNameAR" placeholder="{{__('Name in Arabic')}}">
                                        </div>
                                        {{input_error($errors,'name_ar')}}
                                    </div>
                                </div> -->

                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="annual_consumtion_rate" class="control-label">{{__('annual consumption rate')}} 
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                            <input type="text"  name="annual_consumtion_rate" value="0" class="form-control" id="annual_consumption_rate" placeholder="{{__('annual consumption rate')}}">
                                        </div>
                                        {{input_error($errors,'annual_consumtion_rate')}}
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
