@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Register Added Value') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:register_added_value.index')}}"> {{__('Register Added Value')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Register Added Value')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-user ico"></i>{{__('Edit Register Added Value')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"
                                style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"
                                    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>


                <div class="box-content">

                    <form method="post" action="{{route('admin:register_added_value.update',$register_added_value)}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class="row">

                            <div class="">
                                <div class="col-md-12">

                                    @if(authIsSuperAdmin())
                                        <div class="form-group has-feedback">
                                            <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon fa fa-file"></span>
                                                <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                                        onchange="changeBranch();">
                                                    <option value="">{{__('Select Branches')}}</option>
                                                    @foreach($branches as $k => $v)
                                                        <option
                                                            value="{{$k}}" {{isset($register_added_value) && $register_added_value->branch_id == $k? 'selected':''}}
                                                            {{request()->has('branch_id') && request()['branch_id'] == $k? 'selected':''}}
                                                        >
                                                            {{$v}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{input_error($errors,'branch_id')}}
                                        </div>
                                    @endif
                                </div>
                            </div>


                            <div class="">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company name')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="name" class="form-control" id="inputNameEn"
                                                   disabled
                                                   placeholder="{{__('Company name')}}"
                                                   value="{{old('name', !empty($branch)? $branch->company_name:'')}}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company Address')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                                                   disabled
                                                   placeholder="{{__('Company Address')}}"
                                                   value="{{old('address', !empty($branch)? $branch->address:'')}}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Tax Card')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="tax_card" class="form-control" id="inputNameEn"
                                                   disabled
                                                   placeholder="{{__('Tax Card')}}"
                                                   value="{{old('address', !empty($branch)? $branch->tax_card:'')}}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Area')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="area" class="form-control" id="area"
                                                   placeholder="{{__('Area')}}"
                                                   value="{{old('area', !empty($register_added_value)? $register_added_value->area:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'area')}}
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Errands')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="errands" class="form-control" id="errands"
                                                   placeholder="{{__('Errands')}}"
                                                   value="{{old('errands', !empty($register_added_value)? $register_added_value->errands:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'errands')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Date Of Registration')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="register_date" class="form-control datepicker" id="register_date" autocomplete="off"
                                                   placeholder="{{__('Date Of Registration')}}"
                                                   value="{{old('register_date', !empty($register_added_value)? $register_added_value->register_date:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'register_date')}}
                                </div>

                            </div>
                        </div>


                        <div class="form-group col-sm-12" >
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>

            </div>

            <!-- /.box-content -->
        </div>

        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\RegisterAddedValue\RegisterAddedValueRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function changeBranch () {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:register_added_value.edit',$register_added_value)}}" + "?branch_id=" + branch_id ;
        }

    </script>

@endsection
