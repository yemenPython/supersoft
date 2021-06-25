@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Assets Employees') }} </title>
@endsection

@section('content')
<div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:assets.index')}}"> {{__('words.assets')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('add employee') }}</li>
            </ol>
        </nav>

       
  
        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
            <h4 class="box-title with-control">
                <i class="fa fa-plus"></i>{{__('add new Employee')}}
            </h4>
            <!-- /.box-title -->
            <div class="card-content js__card_content">
                <form id="newAssetEmployee-form" method="post" action="{{ route('admin:assetsEmployees.store') }}">

                    <div class="row">
                    @csrf
                   
                    <input type="hidden" value="{{$asset->id}}" name="asset_id" >
                    <input type="hidden" value="" name="asset_employee_id" id="asset_employee_id" >
                    <div class="col-md-12">
                    <div class="form-group col-md-4">
                        <label>{{ __('name') }} </label>
                        <div class="input-group">
                        <span class="input-group-addon fa fa-barcode"></span>
                        <input type="text" name="name" id="name" class="form-control">
                    </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label> {{ __('phone') }} </label>
                        <div class="input-group">
                        <span class="input-group-addon fa fa-barcode"></span>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>
                    </div>

                    <div class="form-group col-md-4">
                        <label> {{ __('words.date-from') }} </label>
                        <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                        <input name="start_date" id="start_date"
                               class="form-control date js-example-basic-single" type="date"/>
                    </div>
                    </div>
                    </div>


                    <div class="form-group col-md-4">
                        <label> {{ __('words.date-to') }} </label>
                        <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                        <input name="end_date" id="end_date" class="form-control date js-example-basic-single" type="date"/>
                    </div>
                    </div>


                    </div>



                    <button class="btn sr4-wg-btn waves-effect waves-light hvr-rectangle-out">
                        <i class=" fa fa-search "></i>
                        {{__('save')}}
                    </button>
                    <a href="{{route('admin:stores-transfers.index')}}"
                       class="btn bc-wg-btn waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                    </a>


                </form>

            </div>
        </div>
    </div>



    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-cubes"></i>   {{ $asset->name. " " .__('employee') }}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                
                        <li class="list-inline-item">
               
                                @component('admin.buttons._confirm_delete_selected',[
                                    'route' => 'admin:assets.delete_selected',
                                    ])
                                @endcomponent
                 


                        </li>
            </ul>
            <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="datatable-with-btns" class="table table-striped table-bordered display" style="width:100%">
                    <thead>
                        <tr>
                            <th scope="col"> {{ __('name') }} </th>
                            <th scope="col"> {{ __('phone') }} </th>
                            <th scope="col"> {{ __('start dte') }} </th>
                            <th scope="col"> {{ __('end date') }} </th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">
                                <div class="checkbox danger">
                                    <input type="checkbox"  id="select-all">
                                    <label for="select-all"></label>
                                </div>{!! __('Select') !!}
                            </th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th scope="col"> {{ __('name') }} </th>
                            <th scope="col"> {{ __('phone') }} </th>
                            <th scope="col"> {{ __('start date') }} </th>
                            <th scope="col"> {{ __('end date') }} </th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                    </tfoot>
                    <tbody>
                    @if($assetsEmployees)
                        @foreach($assetsEmployees as $assetEmployee)
                            <tr>
                                <td> {{ $assetEmployee->employee_name }} </td>
                                <td> {{ $assetEmployee->phone }} </td>
                                <td> {{ $assetEmployee->start_date }} </td>
                                <td> {{ $assetEmployee->end_date }} </td>
                                <td>
                                <div class="btn-group margin-top-10">
                                        
                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>
                                     
                                    </button> 
                                        <ul class="dropdown-menu">
                                            <li>
                                            
                                            <button class="btn btn-info editEmployeeBtn" 
                                            emp-id="{{ $assetEmployee->id }}"
                                            emp-name="{{ $assetEmployee->employee_name }}"
                                            emp-phone="{{ $assetEmployee->phone }}"
                                            emp-startDate="{{ $assetEmployee->start_date }}"
                                            emp-endDate="{{ $assetEmployee->end_date }}"
                                            
                                             >Edit</button>
                
                                            </li>
                                            <li>
                                                
                                            @component('admin.buttons._delete_button',[
                                            'id'=> $assetEmployee->id,
                                            'route' => 'admin:assetsEmployees.destroy',
                                             ])
                                @endcomponent
                
                                            </li>
                                           
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    @component('admin.buttons._delete_selected',[
                                        'id' =>  $assetEmployee->id,
                                        'route' => 'admin:assetsEmployees.deleteSelected',
                                    ])
                                    @endcomponent
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>
</div>
</div>
@stop

@section('js')
{!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\AssetEmployeeRequest'); !!}
    <script type="application/javascript">
        $(document).ready(function () {
            invoke_datatable($('#datatable-with-btns'))
            $(".select2").select2();

            $(".editEmployeeBtn").on('click' , function(){
                $("#name").val($(this).attr('emp-name'));
                $("#phone").val($(this).attr('emp-phone'));
                $("#start_date").val($(this).attr('emp-startDate'));
                $("#end_date").val($(this).attr('emp-endDate'));
                $("#asset_employee_id").val($(this).attr('emp-id'));
                
            })
        })

        
    </script>
@stop
