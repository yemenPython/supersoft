@extends('admin.layouts.app')
@section('title')
    <title>{{__('Maintenance Types')}} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Maintenance Types')}} </li>
            </ol>
        </nav>

        @if(filterSetting())
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card top-search">
                <h4 class="box-title with-control">
                    <i class="fa fa-search"></i>{{__('Search filters')}}
                    <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                    <!-- /.controls -->
                </h4>
                <!-- /.box-title -->
                <div class="card-content js__card_content">

                    <form action="{{route('admin:maintenance-detection-types.index')}}" method="get">
                        <div class="list-inline margin-bottom-0 row">

                        @if(authIsSuperAdmin())
                                <div class="form-group col-md-12">
                                    <label> {{ __('Branch') }} </label>
                                    <select name="branch_id" class="form-control js-example-basic-single">
                                        <option value="">{{__('Select Branch')}}</option>
                                        @foreach($branches as $k=>$v)
                                            <option value="{{$k}}">{{$v}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                            <div class="form-group col-md-6">
                                <label> {{ __('Maintenance types') }} </label>
                                <select name="name" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Name')}}</option>
                                    @foreach($maintenance_types as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="switch primary col-md-6">
                            <ul class="list-inline">
                              <li>
                              <input type="checkbox" id="switch-2" name="active">
                                <label for="switch-2">{{__('Active')}}</label>
                              </li>
                              <li>
                           
                              <input type="checkbox" id="switch-3" name="inactive">
                                <label for="switch-3">{{__('inActive')}}</label>   
                              </li>                              
                            </ul>

                            </div>

                        </div>
                        <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:maintenance-detection-types.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

                    </form>
                </div>
                <!-- /.card-content -->
            </div>
            <!-- /.box-content -->
        </div>
        @endif
        
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-bars"></i>  {{__('Maintenance Types')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:maintenance-detection-types.create',
                           'new' => '',
                          ])
                        </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                             'route' => 'admin:maintenance.detection.types.deleteSelected',
                              ])
                                @endcomponent
                            </li>
                
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="currencies" class="table table-bordered table-hover wg-table-print" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Maintenance types') !!}</th>
                                <!-- <th scope="col">{!! __('Branch') !!}</th> -->
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Maintenance types') !!}</th>
                                <!-- <th scope="col">{!! __('Branch') !!}</th> -->
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($types as $index=>$type)
                                <tr>
                                    <td>{!! $index +1 !!}</td>
                                    <td>{!! $type->name !!}</td>
                                    <!-- <td>{!! optional($type->branch)->name !!}</td> -->

                                    <td>
                                    @if($type->status)
                                            <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                        @else
                                            <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                        @endif  

                                        </td>


                                    <td>{!! $type->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td>

                                        <!-- @component('admin.buttons._show_button',[
                                                   'id' => $type->id,
                                                   'route'=>'admin:maintenance-detection-types.show'
                                                    ])
                                        @endcomponent -->

                                        
                                    <div class="btn-group margin-top-10">
                                            <button type="button" class="btn btn-options dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ico fa fa-bars"></i>
                                                {{__('Options')}} <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-wg">
                                                <li>

                                        @component('admin.buttons._edit_button',[
                                                    'id' => $type->id,
                                                    'route'=>'admin:maintenance-detection-types.edit'
                                                     ])
                                        @endcomponent
                                        </li>
                                <li class="btn-style-drop">
                                        @component('admin.buttons._delete_button',[
                                                    'id'=>$type->id,
                                                    'route' => 'admin:maintenance-detection-types.destroy',
                                                    'tooltip' => __('Delete '.$type['name']),
                                                     ])
                                        @endcomponent
                                        </li>
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                      'id' => $type->id,
                                                                          'route' => 'admin:maintenance.detection.types.deleteSelected',

                                                       ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        </div>
        @endsection
        @section('js')
            <script type="application/javascript">
                invoke_datatable($('#currencies'))
            </script>
@endsection
