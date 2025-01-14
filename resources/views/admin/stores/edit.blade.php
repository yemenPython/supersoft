@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Edit store') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:stores.index')}}"> {{__('Stores')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit store')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-home"></i>
                    {{__('Edit store')}}
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
                </h4>


                <div class="box-content">
                    <form method="post" action="{{route('admin:stores.update', $store->id)}}" class="form">
                        @csrf
                        @method('put')

                        <div class="row">
<div class="col-xs-12">

<div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                                @if(authIsSuperAdmin())
                                    <div class="col-xs-12">
                                        <div class="form-group has-feedback">
                                            <label for="inputSymbolAR"
                                                   class="control-label">{{__('Select Branch')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon fa fa-file"></span>
                                                <select name="branch_id" class="form-control js-example-basic-single" id="branchId">
                                                    @foreach(\App\Models\Branch::all() as $branch)
                                                        <option
                                                            value="{{$branch->id}}" {{$store->branch_id == $branch->id ? 'selected' : ''}}>{{$branch->name}}</option>
                                                    @endforeach
                                                </select>
                                                {{input_error($errors,'branch_id')}}
                                            </div>
                                        </div>
                                    </div>
                                @endif


                            <div class="">


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAR" class="control-label">{{__('Name in Arabic')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_ar" class="form-control" id="inputNameAR"
                                                   value="{{$store->name_ar}}"
                                                   placeholder="{{__('Name in Arabic')}}">
                                        </div>
                                        {{input_error($errors,'name_ar')}}
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="inputNameEN" class="control-label">{{__('Name in English')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="name_en" class="form-control" id="inputNameEN"
                                                   value="{{$store->name_en}}"
                                                   placeholder="{{__('Name in English')}}">
                                        </div>
                                        {{input_error($errors,'name_en')}}
                                    </div>

                                </div>
                                </div>

                                <div class="">

                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="creator_phone" class="control-label">{{__('Store Phone')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="store_phone" class="form-control" id="store_phone"
                                                   value="{{$store->store_phone}}"
                                                   placeholder="{{__('Store Phone')}}">
                                        </div>
                                        {{input_error($errors,'store_phone')}}
                                    </div>
                                </div>

                                <div class="col-md-6">

                                    <div class="form-group has-feedback">
                                        <label for="store_address" class="control-label">{{__('Store Address')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-phone"></li></span>
                                            <input type="text" name="store_address" class="form-control"
                                                   id="store_address"
                                                   value="{{$store->store_address}}"
                                                   placeholder="{{__('Store Address')}}">
                                        </div>
                                        {{input_error($errors,'Store')}}
                                    </div>
                                </div>

                                </div>


                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label for="note" class="control-label">{{__('Notes')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <textarea name="note" class="form-control" id="note" rows="3">
                                        {{$store->note}}
                                   </textarea>
                                        </div>
                                        {{input_error($errors,'note')}}
                                    </div>

                                </div>

                                </div>
                                </div>
                                </div>
                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Store\UpdateStoreRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
