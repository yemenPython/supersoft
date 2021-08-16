@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Supplier') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:egyptian_federation.index')}}"> {{__('Egyptian Federation')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Egyptian Federation')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-user ico"></i>{{__('Create Egyptian Federation')}}
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

                    <form method="post" action="{{route('admin:egyptian_federation.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')
                        <div class="row">

                            <div class="">
                                <div class="col-md-12">

                                    @if(authIsSuperAdmin())
                                        <div class="form-group has-feedback">
                                            <label for="inputPhone" class="control-label">{{__('Branch')}}</label>
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
                                <div class="col-md-6">
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Membership No')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="membership_no" class="form-control" id="membership_no"
                                                   placeholder="{{__('Membership No')}}"
                                                   value="{{old('membership_no', !empty($last_created)? $last_created->membership_no:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'membership_no')}}
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company Type')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="company_type" class="form-control" id="membership_no"
                                                   placeholder="{{__('Company Type')}}"
                                                   value="{{old('type', !empty($last_created)? $last_created->company_type:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'company_type')}}
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Date of register in the union')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="date_of_register" class="form-control datepicker" id="date_of_register"
                                                   placeholder="{{__('Date of register in the union')}}"
                                                   value="{{old('date_of_register', !empty($last_created)? $last_created->date_of_register:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'date_of_register')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Subscription payment receipt')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="payment_receipt" class="form-control" id="payment_receipt"
                                                   placeholder="{{__('Subscription payment receipt')}}"
                                                   value="{{old('payment_receipt', !empty($last_created)? $last_created->payment_receipt:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'payment_receipt')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('End date')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="end_date" class="form-control datepicker" id="end_date" autocomplete="off"
                                                   placeholder="{{__('End date')}}"
                                                   value="{{old('end_date', !empty($last_created)? $last_created->end_date:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'end_date')}}
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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\EgyptianFederationofConstruction\EgyptianFederationofConstructionRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function changeBranch () {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:egyptian_federation.create')}}" + "?branch_id=" + branch_id ;
        }

    </script>

@endsection
