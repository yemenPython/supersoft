
@extends('admin.layouts.app')
@section('title')
    <title>{{__('Show Maintenance Types')}}</title>
@endsection
@section('content')

<div class="row small-spacing">

<nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:maintenance-detection-types.index')}}"> {{__('Maintenance Types')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Show Maintenance Types')}} </li>
            </ol>
        </nav>

    <div class="row">
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                    <h1 class="box-title bg-info" style="text-align: initial"><i class="fa fa-info-circle ico"></i>{{__('Show Maintenance Types')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h1>
                <div class="box-content">


                <div class="card-content wg-for-dt">
                        <div class="row">

                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>

                                        <tr>
                                        <th scope="row">{{__('Maintenance Types Name Ar')}}</th>
                                        <td>{{$maintenanceDetectionType->name_ar}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Branch')}}</th>
                                        <td>{{optional($maintenanceDetectionType->branch)->name}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Description')}}</th>
                                        <td>{{$maintenanceDetectionType->description}}</td>
                                        </tr> 

 

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>

                                       <tr>
                                        <th scope="row">{{__('Maintenance Types Name En')}}</th>
                                        <td>{{$maintenanceDetectionType->name_en}}</td>
                                        </tr> 

                                        <tr>
                                        <th scope="row">{{__('Status')}}</th>
                                        <td>{{$maintenanceDetectionType->active}}</td>
                                        </tr> 


                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- /.col-md-6 -->
                        </div>
                        <!-- /.row -->
                    </div>  


 
            <!-- /.box-content card -->
            <a href="{{route('admin:maintenance-detection-types.index')}}"
                                    class="btn btn-danger waves-effect waves-light"><i class=" fa fa-reply"></i> {{__('Back')}} 
                                    </a>
        </div>


    </div>
</div>
@endsection
