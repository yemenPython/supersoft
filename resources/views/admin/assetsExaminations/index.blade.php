@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Assets Examinations') }} </title>
@endsection
<!-- Modal -->
<div class="modal fade" id="add-employee-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33">
    <div class="modal-dialog" role="document">
        <div class="modal-content wg-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel-1">{{__('Add new asset Examination')}}</h4>

            </div>
            <form id="newAssetEmployee-form" method="post" action="{{ route('admin:assetsExaminations.store') }}">
                <div class="modal-body">



                        <div class="row">
                            @csrf
                            <input type="hidden" value="{{$asset->id}}" name="asset_id">
                            <input type="hidden" value="" name="asset_examination_id" id="asset_examination_id">
                            <div class="form-group col-md-12">
                                <label>{{ __('details') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-file-o"></span>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>
                            </div>
                            <div class="">
                                <div class="form-group col-md-6">
                                    <label> {{ __('words.date-from') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                        <input name="start_date" id="start_date"
                                               class="form-control date js-example-basic-single" type="date"/>
                                    </div>
                                </div>


                                <div class="form-group col-md-6">
                                    <label> {{ __('words.date-to') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                        <input name="end_date" id="end_date"
                                               class="form-control date js-example-basic-single" type="date"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                            <label for="status" class="control-label">{{__('Status')}}</label>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="switch-1" name="status" value="1" CHECKED>
                                <label for="switch-1">{{__('Active')}}</label>
                            </div>
                        </div>
                        </div>





                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        {{__('save')}}
                    </button>
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">

                        {{__('Close')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:assets.index')}}"> {{__('words.assets')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('Assets Examinations') }}</li>
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
                        <form  onsubmit="filterFunction($(this));return false;">
                            <div class="list-inline margin-bottom-0 row">
                                <div class="form-group col-md-3">
                                    <label> {{ __('Name') }} </label>
                                    {!! drawSelect2ByAjax('name','AssetExamination','examination_details',__('Select Name'),request()->name) !!}
                                </div>

                                <div class="form-group col-md-3">
                                    <label> {{ __('words.date-from') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                        <input name="start_date" id="start_date"
                                               class="form-control date js-example-basic-single" type="date"/>
                                    </div>
                                </div>

                                <div class="form-group col-md-3">
                                    <label> {{ __('words.date-to') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                        <input name="end_date" id="end_date"
                                               class="form-control date js-example-basic-single" type="date"/>
                                    </div>
                                </div>


                                <div class="switch primary col-md-1">
                                    <input type="checkbox" id="switch-slam" name="active">
                                    <label for="switch-slam">{{__('Active')}}</label>
                                </div>
                                <div class="switch primary col-md-2">
                                    <input type="checkbox" id="switch-ali" name="inactive">
                                    <label for="switch-ali">{{__('inActive')}}</label>
                                </div>

                            </div>

                            @include('admin.btns.btn_search')

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
                    <i class="fa fa-cubes"></i> {{ __('Assets Examinations'). " " .$asset->name }}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            <a style=" margin-bottom: 12px; border-radius: 5px"
                               type="button"
                               data-toggle="modal" data-target="#add-employee-modal"
                               class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
                                {{__('Add new')}}
                                <i class="ico fa fa-plus"></i>

                            </a>
                        </li>
                        <li class="list-inline-item">

                            @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:assetsExaminations.delete_selected',
                                ])
                            @endcomponent


                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col"> {{ __('#') }} </th>
                                <th scope="col"> {{ __('status') }} </th>
                                <th scope="col"> {{ __('details') }} </th>
                                <th scope="col"> {{ __('start date') }} </th>
                                <th scope="col"> {{ __('end date') }} </th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col"> {{ __('#') }} </th>
                                <th scope="col"> {{ __('status') }} </th>
                                <th scope="col"> {{ __('details') }} </th>
                                <th scope="col"> {{ __('start date') }} </th>
                                <th scope="col"> {{ __('end date') }} </th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>


            </div>
        </div>
    </div>
    </div>
@stop

@section('js')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\AssetExaminationRequest')->selector('#newAssetEmployee-form'); !!}
    <script type="application/javascript">

        $('#add-employee-modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var examination_id = button.data('examination_id');
            $('#asset_examination_id').val(examination_id);
            var name = button.data('name');
            $('#name').val(name);
            var start_date = button.data('start_date');
            $('#start_date').val(start_date);
            var end_date = button.data('end_date');
            $('#end_date').val(end_date);
            var status = button.data('status');
            $('.status').val(status);

            var title = button.data('title');
            if (title === undefined) {
                $('#myModalLabel-1').text('{{__('Add new asset Examination')}}');
            }
            $('#myModalLabel-1').text(title);
        });

        $('#add-employee-modal').on('hide.bs.modal', function (event) {
            $("#empId").select2("val", '');
            $("#newAssetEmployee-form").get(0).reset();
            $(".error-help-block").each(function (index , element) {
                element.remove();
            })
            $("form#newAssetEmployee-form .form-group").each(function(){
                $(this).removeClass('has-error');
            });
        });

        server_side_datatable('#datatable-with-btns');
        function filterFunction($this) {
            $("#loaderSearch").show();
            $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
            $datatable.ajax.url($url).load();
            $(".js__card_minus").trigger("click");
            setTimeout( function () {
                $("#loaderSearch").hide();
            }, 1000)
        }
    </script>
@stop
