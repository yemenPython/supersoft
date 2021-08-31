@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Commercial Register') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:commercial_register.index')}}"> {{__('Commercial Register')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Commercial Register')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>{{__('Create Commercial Register')}}
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

                    <form method="post" action="{{route('admin:commercial_register.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="row">

                            <div class="">
                                <div class="col-md-12">

                                    @if(authIsSuperAdmin())
                                        <div class="form-group has-feedback">
                                            <label for="inputPhone" class="control-label">{{__('Branch')}} {!! required() !!}</label>
                                            <div class="input-group">
                                                <span class="input-group-addon fa fa-file"></span>
                                                <select class="form-control js-example-basic-single" name="branch_id" id="branch_id"
                                                        onchange="changeBranch();"
                                                >
                                                    <option value="">{{__('Select Branches')}}</option>
                                                    @foreach($branches as $k => $v)
                                                        <option
                                                            value="{{$k}}" {{isset($branch) && $branch->id == $k? 'selected':''}}
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

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company name')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="name" class="form-control" id="inputNameEn"
                                                   disabled
                                                   placeholder="{{__('Company name')}}"
                                                   value="{{old('name', !empty($branch)? $branch->company_name:'')}}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company Address')}} {!! required() !!}</label>
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
                                        <label for="inputNameAr" class="control-label">{{__('Tax Card')}} {!! required() !!}</label>
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
                                        <label for="inputNameAr" class="control-label">{{__('Commercial Registry Office')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="commercial_registry_office" class="form-control" id="commercial_registry_office"
                                                   placeholder="{{__('Commercial Registry Office')}}"
                                                   value="{{old('commercial_registry_office', !empty($last_created)? $last_created->commercial_registry_office:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'commercial_registry_office')}}
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('The National Number Of The Company')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="national_number" class="form-control" id="national_number"
                                                   placeholder="{{__('The National Number Of The Company')}}"
                                                   value="{{old('national_number', !empty($last_created)? $last_created->national_number:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'national_number')}}
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Deposit Number')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="deposit_number" class="form-control" id="deposit_number"
                                                   placeholder="{{__('Deposit Number')}}"
                                                   value="{{old('deposit_number', !empty($last_created)? $last_created->deposit_number:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'deposit_number')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Deposit Date')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="deposit_date" class="form-control datepicker text-right" id="deposit_date"
                                                   placeholder="{{__('Deposit Date')}}"
                                                   value="{{old('deposit_date', !empty($last_created)? $last_created->deposit_date:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'deposit_date')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Valid Until')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="valid_until" class="form-control datepicker text-right" id="valid_until" autocomplete="off"
                                                   placeholder="{{__('Valid Until')}}"
                                                   value="{{old('valid_until', !empty($last_created)? $last_created->valid_until:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'valid_until')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Commercial feature')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="commercial_feature" class="form-control" id="commercial_feature" autocomplete="off"
                                                   placeholder="{{__('Commercial feature')}}"
                                                   value="{{old('commercial_feature', !empty($last_created)? $last_created->commercial_feature:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'commercial_feature')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company Type')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="company_type" class="form-control" id="company_type" autocomplete="off"
                                                   placeholder="{{__('Company Type')}}"
                                                   value="{{old('company_type', !empty($last_created)? $last_created->company_type:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'company_type')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('The purpose of establishing the company')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="purpose" class="form-control" id="purpose" autocomplete="off"
                                                   placeholder="{{__('The purpose of establishing the company')}}"
                                                   value="{{old('purpose', !empty($last_created)? $last_created->purpose:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'purpose')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Number Of Years')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="number" name="no_of_years" class="form-control" id="no_of_years" autocomplete="off"
                                                   placeholder="{{__('Number Of Years')}}"
                                                   value="{{old('no_of_years', !empty($last_created)? $last_created->no_of_years:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'no_of_years')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Start On')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="start_at" class="form-control datepicker text-right" id="start_at" autocomplete="off"
                                                   placeholder="{{__('Start On')}}"
                                                   value="{{old('start_at', !empty($last_created)? $last_created->start_at:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'start_at')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('End On')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="end_at" class="form-control datepicker text-right" id="end_at" autocomplete="off"
                                                   placeholder="{{__('End On')}}"
                                                   value="{{old('end_at', !empty($last_created)? $last_created->end_at:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'end_at')}}
                                </div>

                        </div>
                        </div>


                            @include('admin.buttons._save_buttons')
                  
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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\CommercialRegister\CommercialRegisterRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function changeBranch () {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:commercial_register.create')}}" + "?branch_id=" + branch_id ;
        }

    </script>

@endsection
