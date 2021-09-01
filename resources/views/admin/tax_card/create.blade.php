@extends('admin.layouts.app')

@section('title')
    <title>{{__('Create Company Tax Card')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:tax_card.index')}}"> {{__('Company Tax Card')}} </a></li>
                <li class="breadcrumb-item active"> {{__('Create Company Tax Card')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial">
                <i class="fa fa-file-text-o"></i>  {{__('Create Company Tax Card')}}
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

                    <form method="post" action="{{route('admin:tax_card.store')}}" class="form"
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
                                        <span class="input-group-addon"><li class="fa fa-file-o"></li></span>
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
                                        <span class="input-group-addon"><li class="fa fa-location-arrow"></li></span>
                                            <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                                                   disabled
                                                   placeholder="{{__('Company Address')}}"
                                                   value="{{old('address', !empty($branch)? $branch->address:'')}}">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company Activity')}} {!! required() !!}</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-file-o"></li></span>
                                            <input type="text" name="activity" class="form-control" id="activity"
                                                   placeholder="{{__('Company Activity')}}"
                                                   value="{{old('activity', !empty($last_created)? $last_created->activity:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'activity')}}
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Registration number')}} {!! required() !!}</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-file-text-o"></li></span>
                                            <input type="text" name="registration_number" class="form-control" id="membership_no"
                                                   placeholder="{{__('Registration number')}}"
                                                   value="{{old('registration_number', !empty($last_created)? $last_created->registration_number:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'registration_number')}}
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Registration Date')}} {!! required() !!}</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                            <input type="text" name="registration_date" class="form-control datepicker text-right" id="registration_date"
                                                   placeholder="{{__('Registration Date')}}"
                                                   value="{{old('registration_date', !empty($last_created)? $last_created->registration_date:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'registration_date')}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('End date')}} {!! required() !!}</label>
                                        <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                            <input type="text" name="end_date" class="form-control datepicker text-right" id="end_date" autocomplete="off"
                                                   placeholder="{{__('End date')}}"
                                                   value="{{old('end_date', !empty($last_created)? $last_created->end_date:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'end_date')}}
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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\TaxCard\TaxCardRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function changeBranch () {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:tax_card.create')}}" + "?branch_id=" + branch_id ;
        }

    </script>

@endsection
