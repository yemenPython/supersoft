@extends('admin.layouts.app')
@section('title')
<title>{{ __('Create Services Type') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin:services-types.index')}}"> {{__('Services Types')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Services Type')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
                <div class=" card box-content-wg-new bordered-all primary">
                    <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-gear"></i>{{__('Create Services Type')}}
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
                </h1>
                <div class="box-content">
                    <form method="post" action="{{route('admin:services-types.store')}}" class="form">
                        @csrf
                        @method('post')

                        @include('admin.services-types.form')

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
    {!! JsValidator::formRequest('App\Http\Requests\Admin\ServicesTypes\CreateRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection