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
                <li class="breadcrumb-item"> {{__('branch products')}}</li>
                <li class="breadcrumb-item active"> {{__('Edit')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-check-square-o"></i>
                    {{__('Edit')}}
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
                    <form method="post" action="{{route('admin:banks.branch_product.update', ['id' => $item->id])}}" class="form">
                        @csrf
                        @method('put')

                        <div class="row">

                            <div class="col-md-12">

                                @if (authIsSuperAdmin())
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('Branches') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select class="form-control select2" name="branch_id" id="branch_id">
                                                    <option value=""> {{ __('Select Branch') }} </option>
                                                    @foreach(\App\Models\Branch::all() as $branch)
                                                        <option {{ (old('branch_id') == $branch->id) || (isset($item) && $item->branch_id == $branch->id) ? 'selected' : '' }}
                                                                value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
                                @endif


                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputNameAR" class="control-label">{{__('Name In arabic')}} {!! required() !!}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="name_ar" class="form-control"
                                                   value="{{$item->name_ar}}" id="inputNameAR"
                                                   placeholder="{{__('Name In arabic')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputNameEN" class="control-label">{{__('Name In English')}}</label>
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span>
                                            <input type="text" name="name_en" class="form-control" id="inputNameEN"
                                                   value="{{$item->name_en}}" placeholder="{{__('Name In English')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\BranchProductRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
@endsection
