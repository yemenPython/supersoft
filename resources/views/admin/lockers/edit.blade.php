@extends('admin.layouts.app')

@section('title')
<title>{{ __('Super Car') }} - {{ __('Edit Locker') }} </title>
@endsection


@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin:lockers.index')}}">   {{__('Lockers')}}</a></li>
            <li class="breadcrumb-item">  {{__('Edit Locker')}} </li>
            </ol>
        </nav>


        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-folder-open-o"></i>  {{__('Edit Locker')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">

                    <form method="post" action="{{route('admin:lockers.update',$locker->id)}}" class="form"
                          enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        @include('admin.lockers.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js-validation')
        {!! JsValidator::formRequest('App\Http\Requests\Admin\Lockers\UpdateLockerRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
