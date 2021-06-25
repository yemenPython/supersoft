@extends('admin.layouts.app')
@section('title')
<title>{{ __('Super Car') }} - {{ __('Edit Car Model') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:carModels.index')}}"> {{__('Cars Models')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Car Model')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-check-square-o"></i>
                  {{__('Edit Car Model')}}
                  <span class="controls hidden-sm hidden-xs pull-left">
                  <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

            <div class="box-content">
                <form method="post" action="{{route('admin:carModels.update', ['id' => $carModel->id])}}" class="form">
                    @csrf
                    @method('put')


                    <div class="row">

                    <div class="col-md-12">

                        <div class="col-md-6">
                        <div class="form-group">
                        <label for="inputNameAR" class="control-label">{{__('Name In arabic')}}</label>
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                            <input type="text" name="name_ar" class="form-control" value="{{$carModel->name_ar}}" id="inputNameAR" placeholder="{{__('Name In arabic')}}">
                        </div>
                        {{input_error($errors,'name_ar')}}
                    </div>
                        </div>

                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                        <label for="inputNameEN" class="control-label">{{__('Name In English')}}</label>
                        <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                            <input type="text" name="name_en" class="form-control" id="inputNameEN" value="{{$carModel->name_en}}" placeholder="{{__('Name In English')}}">
                        </div>
                        {{input_error($errors,'name_en')}}
                    </div>
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="col-md-6">
                        <div class="form-group has-feedback">
                        <label for="inputSymbolAR" class="control-label">{{__('Select Company')}}</label>
                        <div class="input-group">
                                <span class="input-group-addon fa fa-globe"></span>
                        <select name="company_id" class="form-control js-example-basic-single">
                            <option value="">{{__('Select Company')}}</option>
                            @foreach(\App\Models\Company::all() as $company)
                                <option value="{{$company->id}}" {{$carModel->company_id == $company->id ? 'selected' : ''}}>{{$company->name}}</option>
                            @endforeach
                        </select>
                        {{input_error($errors,'company_id')}}
                    </div>
                        </div>
                        </div>

                        <div class="col-md-6">

                        </div>

                    </div>

                  </div>





                    <div class="form-group col-sm-12">
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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\CarModels\CarModelsRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
