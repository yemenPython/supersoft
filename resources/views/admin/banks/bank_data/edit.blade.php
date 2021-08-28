@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="#"> {{__('Managing bank accounts')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:banks.bank_data.index')}}"> {{__('Banks Data')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-user ico"></i>{{__('Edit Supplier')}}
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

                    <form method="post" action="{{route('admin:banks.bank_data.update',['bankData' =>  $item->id] )}}" class="form"
                          enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        @include('admin.banks.bank_data.form')
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- /.row small-spacing -->
@endsection

@section('modals')
    @include('admin.partial.maps.modal')
@endsection

@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\BankDataRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    @include('admin.partial.maps.js')
@endsection
