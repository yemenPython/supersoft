@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Security Approval') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:security_approval.index')}}"> {{__('Security Approval')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Security Approval')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-user ico"></i>{{__('Edit Security Approval')}}
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

                    <form method="post" action="{{route('admin:security_approval.update',$security_approval)}}" class="form"
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
                                                        onchange="changeBranch();"
                                                >
                                                    <option value="">{{__('Select Branches')}}</option>
                                                    @foreach($branches as $k => $v)
                                                        <option
                                                            value="{{$k}}" {{isset($security_approval) && $security_approval->branch_id == $k? 'selected':''}}
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Phone 1')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="phone1" class="form-control" id="inputNameEn"
                                                   disabled
                                                   placeholder="{{__('Phone 1')}}"
                                                   value="{{old('phone1', !empty($branch)? $branch->phone1:'')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Phone 2')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="phone2" class="form-control" id="inputNameEn"
                                                   disabled
                                                   placeholder="{{__('Phone 2')}}"
                                                   value="{{old('phone2', !empty($branch)? $branch->phone2:'')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-10 partners">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Phone')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="phones[]" class="form-control" id="phones" autocomplete="off"
                                                   placeholder="{{__('Phone')}}"
                                                   value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label" style="visibility: hidden">{{__('add')}}</label>
                                        <div class="input-group">
                                            <a class="btn btn-primary add"
                                               onclick="onPlusPhonesClick()">{{__('Add phones')}}</a>
                                        </div>

                                    </div>
                                </div>
                                @if(!empty($security_approval) && $security_approval->phones->isNotEmpty())
                                    @foreach($security_approval->phones as $phone)
                                        <div class="form-group added_images">
                                            <label class=" form-label font-weight-bolder"
                                                   style="display:block">{{__('Phone')}}</label>
                                            <input type="text" class=" form-control form-control-rounded"
                                                   value="{{$phone->phone}}" name="phones[]" style="width: 80%;
    display: inline-block;">
                                            <a href="javascript:void(0);"
                                               onclick="$(this).closest('.added_images').remove();"
                                               class="btn btn-icon btn-danger px-3 py-2"
                                               style=" display: inline-block;">
                                                <i class="fa fa-trash"></i></a>
                                            <div class="invalid-feedback" id="emails-form-error"></div>

                                        </div>
                                    @endforeach
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Registration Number')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="register_no" class="form-control" id="register_no"
                                                   placeholder="{{__('Registration Number')}}"
                                                   value="{{old('register_no', !empty($security_approval)? $security_approval->register_no:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'register_no')}}
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Approval expiration date')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="expiration_date" class="form-control datepicker" id="expiration_date" autocomplete="off"
                                                   placeholder="{{__('Approval expiration date')}}"
                                                   value="{{old('expiration_date', !empty($security_approval)? $security_approval->expiration_date:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'expiration_date')}}
                                </div>

                                <div class="col-md-10 representatives">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Representative')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <select type="text" name="representatives[]" class="form-control representatives_select" style="width: 80% !important;display: initial !important;"
                                                    autocomplete="off">
                                                <option value="0">{{__('Select Employee')}}</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label" style="visibility: hidden">{{__('add')}}</label>
                                        <div class="input-group">
                                            <a class="btn btn-primary add"
                                               onclick="onPlusRepresentativesClick()">{{__('Add Representatives')}}</a>
                                        </div>

                                    </div>
                                </div>
                                @if(!empty($security_approval) && $security_approval->representatives->isNotEmpty())
                                    @foreach($security_approval->representatives as $representative)
                                        <div class="form-group added_images">
                                            <label class=" form-label font-weight-bolder"
                                                   style="display:block">{{__('Representative')}}</label>
                                            <select type="text" name="representatives[]" class="form-control representatives_select" style="width: 80% !important;display: initial !important;"
                                                    autocomplete="off">
                                                <option value="0">{{__('Select Employee')}}</option>
                                                @foreach($employees as $employee)
                                                    <option value="{{$employee->id}}" {{$employee->id ==$representative->employee_id? 'selected':''}}>{{$employee->name}}</option>
                                                @endforeach
                                            </select>
                                            <a href="javascript:void(0);"
                                               onclick="$(this).closest('.added_images').remove();"
                                               class="btn btn-icon btn-danger px-3 py-2"
                                               style=" display: inline-block;">
                                                <i class="fa fa-trash"></i></a>
                                            <div class="invalid-feedback" id="emails-form-error"></div>

                                        </div>
                                    @endforeach
                                @endif

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Commercial Feature')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="commercial_feature" class="form-control" id="commercial_feature"
                                                   placeholder="{{__('Commercial Feature')}}"
                                                   value="{{old('commercial_feature', !empty($security_approval)? $security_approval->commercial_feature:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'commercial_feature')}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company Type')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="company_type" class="form-control" id="company_type"
                                                   placeholder="{{__('Company Type')}}"
                                                   value="{{old('company_type', !empty($security_approval)? $security_approval->company_type:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'company_type')}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('The Company Field Of Activity')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="company_field" class="form-control" id="company_field"
                                                   placeholder="{{__('The Company Field Of Activity')}}"
                                                   value="{{old('company_field', !empty($security_approval)? $security_approval->company_field:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'company_field')}}
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Fax')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="fax" class="form-control" id="fax"
                                                   placeholder="{{__('Fax')}}"
                                                   value="{{old('fax', !empty($security_approval)? $security_approval->fax:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'fax')}}
                                </div>

                                <div class="col-md-10 owners">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Owner')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="owners[]" class="form-control" id="owners" autocomplete="off"
                                                   placeholder="{{__('Owner')}}"
                                                   value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label" style="visibility: hidden">{{__('add')}}</label>
                                        <div class="input-group">
                                            <a class="btn btn-primary add"
                                               onclick="onPlusOwnersClick()">{{__('Add Owners')}}</a>
                                        </div>

                                    </div>
                                </div>
                                @if(!empty($security_approval) && $security_approval->owners->isNotEmpty())
                                    @foreach($security_approval->owners as $owner)
                                        <div class="form-group added_images">
                                            <label class=" form-label font-weight-bolder"
                                                   style="display:block">{{__('Owner')}}</label>
                                            <input type="text" class=" form-control form-control-rounded"
                                                   value="{{$owner->owner}}" name="owners[]" style="width: 80%;
    display: inline-block;">
                                            <a href="javascript:void(0);"
                                               onclick="$(this).closest('.added_images').remove();"
                                               class="btn btn-icon btn-danger px-3 py-2"
                                               style=" display: inline-block;">
                                                <i class="fa fa-trash"></i></a>
                                            <div class="invalid-feedback" id="emails-form-error"></div>

                                        </div>
                                    @endforeach
                                @endif


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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\SecurityApproval\SecurityApprovalRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function onPlusPhonesClick() {

            var template = `
                                    <div class="form-group added_images">
                                        <label class=" form-label font-weight-bolder" style="display:block">{{__('Phone')}}</label>
                                        <input type="text" class=" form-control form-control-rounded" name="phones[]" style="width: 80%;
    display: inline-block;">
    <a href="javascript:void(0);" onclick="$(this).closest('.added_images').remove();" class="btn btn-icon btn-danger px-3 py-2" style=" display: inline-block;">
                <i class="fa fa-trash"></i></a>
            <div class="invalid-feedback" id="emails-form-error"></div>

        </div>


    `;


            $(".partners").append(template);

        }
        function onPlusRepresentativesClick() {

            var template = `
                                    <div class="form-group added_images">
                                        <label class=" form-label font-weight-bolder" style="display:block">{{__('Representative')}}</label>
            <select type="text" name="representatives[]" class="form-control representatives_select"  style="width: 80% !important;display: initial !important;"
                                                    autocomplete="off">
                                                <option value="0">{{__('Select Employee')}}</option>
                                                @foreach($employees as $employee)
            <option value="{{$employee->id}}">{{$employee->name}}</option>
                                                @endforeach
            </select>
<a href="javascript:void(0);" onclick="$(this).closest('.added_images').remove();" class="btn btn-icon btn-danger px-3 py-2" style=" display: inline-block;">
<i class="fa fa-trash"></i></a>
<div class="invalid-feedback" id="emails-form-error"></div>

</div>


`;


            $(".representatives").append(template);
$('.representatives_select').select2();
        }
        function onPlusCompanyShareClick() {

            var template = `
                                    <div class="form-group added_images">
                                        <label class=" form-label font-weight-bolder" style="display:block">{{__('company_share')}}</label>
                                        <input type="text" class=" form-control form-control-rounded shared" name="company_share[]" style="width: 80%;
    display: inline-block;"  onchange="validateSumShare();">
    <a href="javascript:void(0);" onclick="$(this).closest('.added_images').remove();" class="btn btn-icon btn-danger px-3 py-2" style=" display: inline-block;">
                <i class="fa fa-trash"></i></a>
            <div class="invalid-feedback" id="emails-form-error"></div>

        </div>


    `;


            $(".company_share").append(template);

        }
        function onPlusOwnersClick() {

            var template = `
                                    <div class="form-group added_images">
                                        <label class=" form-label font-weight-bolder" style="display:block">{{__('Owners')}}</label>
                                        <input type="text" class=" form-control form-control-rounded shared" name="owners[]" style="width: 80%;
    display: inline-block;"  onchange="validateSumShare();">
    <a href="javascript:void(0);" onclick="$(this).closest('.added_images').remove();" class="btn btn-icon btn-danger px-3 py-2" style=" display: inline-block;">
                <i class="fa fa-trash"></i></a>
            <div class="invalid-feedback" id="emails-form-error"></div>

        </div>


    `;


            $(".owners").append(template);

        }

        function changeBranch () {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:security_approval.edit',$security_approval)}}" + "?branch_id=" + branch_id ;
        }
        function validateSumShare(){
            let total = '';
            $(".shared").each(function () {
                var value = $($(this)).val();
                total = +total + +value;
            });
            if (total > 100) {
                swal({text: '{{__('sorry, Company Share Can Not be greater than 100%')}}', icon: "error"});
                return false;
            }
        }

    </script>

@endsection
