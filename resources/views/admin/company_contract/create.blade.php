@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Company Contract') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:company_contract.index')}}"> {{__('Company Contract')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Company Contract')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-user ico"></i>{{__('Create Company Contract')}}
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

                    <form method="post" action="{{route('admin:company_contract.store')}}" class="form"
                          enctype="multipart/form-data" onsubmit="return validateSumShare();">
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

{{--                                <div class="col-md-4">--}}
{{--                                    <div class="form-group">--}}
{{--                                        <label for="inputNameAr" class="control-label">{{__('Company name')}}</label>--}}
{{--                                        <div class="input-group">--}}
{{--                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>--}}
{{--                                            <input type="text" name="name" class="form-control" id="inputNameEn"--}}
{{--                                                   disabled--}}
{{--                                                   placeholder="{{__('Company name')}}"--}}
{{--                                                   value="{{old('name', !empty($branch)? $branch->company_name:'')}}">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                </div>--}}
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
                                <div class="col-md-10 partners">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Partner')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="partners[]" class="form-control" id="partners" autocomplete="off"
                                                   placeholder="{{__('Partner')}}"
                                                   value="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label" style="visibility: hidden">{{__('add')}}</label>
                                        <div class="input-group">
                                            <a class="btn btn-primary add"
                                               onclick="onPlusPartnersClick()">{{__('Add Partner')}}</a>
                                        </div>

                                    </div>
                                </div>

                                @if($last_created->partners->isNotEmpty())
                                    @foreach($last_created->partners as $partner)
                                        <div class="form-group added_images">
                                            <label class=" form-label font-weight-bolder"
                                                   style="display:block">{{__('Partner')}}</label>
                                            <input type="text" class=" form-control form-control-rounded"
                                                   value="{{$partner->partner}}" name="partners[]" style="width: 80%;
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Contract Date')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="contract_date" class="form-control datepicker" id="contract_date"
                                                   placeholder="{{__('Contract Date')}}"
                                                   value="{{old('contract_date', !empty($last_created)? $last_created->contract_date:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'contract_date')}}
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Date Of Registration')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="register_date" class="form-control datepicker" id="register_date" autocomplete="off"
                                                   placeholder="{{__('Date Of Registration')}}"
                                                   value="{{old('register_date', !empty($last_created)? $last_created->register_date:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'register_date')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Commercial Feature')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="commercial_feature" class="form-control" id="commercial_feature"
                                                   placeholder="{{__('Commercial Feature')}}"
                                                   value="{{old('commercial_feature', !empty($last_created)? $last_created->commercial_feature:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'commercial_feature')}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company Purpose')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="company_purpose" class="form-control" id="company_purpose"
                                                   placeholder="{{__('Company Purpose')}}"
                                                   value="{{old('company_purpose', !empty($last_created)? $last_created->company_purpose:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'company_purpose')}}
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Share Capital')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="share_capital" class="form-control" id="share_capital"
                                                   placeholder="{{__('Share Capital')}}"
                                                   value="{{old('share_capital', !empty($last_created)? $last_created->share_capital:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'share_capital')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Duration Of Partnership')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="number" name="partnership_duration" class="form-control" id="partnership_duration"
                                                   placeholder="{{__('Duration Of Partnership')}}"
                                                   value="{{old('partnership_duration', !empty($last_created)? $last_created->partnership_duration:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'partnership_duration')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Start On')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="start_at" class="form-control datepicker" id="start_at"
                                                   placeholder="{{__('Start On')}}"
                                                   value="{{old('start_at', !empty($last_created)? $last_created->start_at:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'start_at')}}
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('End On')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="end_at" class="form-control datepicker" id="end_at"
                                                   placeholder="{{__('End On')}}"
                                                   value="{{old('end_at', !empty($last_created)? $last_created->end_at:'')}}">
                                        </div>

                                    </div>
                                    {{input_error($errors,'end_at')}}
                                </div>

                                <div class="col-md-10 company_share">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label">{{__('Company Share')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                            <input type="text" name="company_share[]" class="form-control shared" id="company_share" autocomplete="off"
                                                   placeholder="{{__('Company Share')}}"
                                                   onchange="validateSumShare();"
                                                   value="">
                                        </div>
                                    </div>
{{--                                    {{input_error($errors,'company_share')}}--}}
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="inputNameAr" class="control-label" style="visibility: hidden">{{__('add')}}</label>
                                        <div class="input-group">
                                            <a class="btn btn-info add"
                                               onclick="onPlusCompanyShareClick()">{{__('Add Company Share')}}</a>
                                        </div>

                                    </div>
                                </div>
                                @if(!empty($last_created) && $last_created->company_shares->isNotEmpty())
                                    @foreach($last_created->company_shares as $company_share)
                                        <div class="form-group added_images">
                                            <label class=" form-label font-weight-bolder"
                                                   style="display:block">{{__('company_share')}}</label>
                                            <input type="text" class=" form-control form-control-rounded shared"
                                                   value="{{$company_share->company_share}}"
                                                   name="company_share[]" style="width: 80%;
    display: inline-block;" onchange="validateSumShare();">
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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\CompanyContract\CompanyContractRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')


    <script type="application/javascript">

        function onPlusPartnersClick() {

            var template = `
                                    <div class="form-group added_images">
                                        <label class=" form-label font-weight-bolder" style="display:block">{{__('Partner')}}</label>
                                        <input type="text" class=" form-control form-control-rounded" name="partners[]" style="width: 80%;
    display: inline-block;">
    <a href="javascript:void(0);" onclick="$(this).closest('.added_images').remove();" class="btn btn-icon btn-danger px-3 py-2" style=" display: inline-block;">
                <i class="fa fa-trash"></i></a>
            <div class="invalid-feedback" id="emails-form-error"></div>

        </div>


    `;


            $(".partners").append(template);

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

        function changeBranch () {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:company_contract.create')}}" + "?branch_id=" + branch_id ;
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
