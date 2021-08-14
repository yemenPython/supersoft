@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Assets Maintenance') }} </title>
@endsection

<!-- Modal -->
<div class="modal fade" id="add-employee-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33">
    <div class="modal-dialog" role="document">
        <div class="modal-content wg-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel-1">{{__('Assets Maintenance')}}</h4>
            </div>

            <form id="newAssetEmployee-form" method="post"
                  action="{{ route('admin:assets_maintenance.store', $asset->id) }}">
                <div class="modal-body">
                    <div class="row">
                        @csrf
                        <input type="hidden" value="" name="asset_maintenance_id" id="asset_maintenance_id">
                        <div class="form-group col-md-6">
                            <label> {{ __('Name in Arabic') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <input type="text" name="name_ar" id="name_ar" class="form-control">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Name in English') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <input type="text" name="name_en" id="name_en" class="form-control">
                            </div>
                        </div>


                        <div class="form-group col-md-6">
                            <label> {{ __('Maintenance Types') }} <span class="text-danger">*</span></label>
                            {!! drawSelect2ByAjax('maintenance_detection_type_id','MaintenanceDetectionType', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('Select'),request()->maintenance_detection_type_id) !!}
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Maintenance Detection') }} <span class="text-danger">*</span></label>
                            {!! drawSelect2ByAjax('maintenance_detection_id','MaintenanceDetection', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('Select'),request()->maintenance_detection_id) !!}
                        </div>

                        <div class="form-group col-md-6">
                            <label for="inputPhone" class="control-label">{{__('Maintenance Type')}} <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <select class="form-control js-example-basic-single" name="maintenance_type" id="maintenance_type">
                                    <option value="">{{__('Select')}}</option>
                                    <option value="km">{{__('KM')}}</option>
                                    <option value="hour">{{__('Hour')}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Number Of KM or Hour') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-sort-numeric-desc"></span>
                                <input type="number" name="number_of_km_h" id="number_of_km_h" class="form-control">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Maintenance Period (by month)') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-sort-numeric-desc"></span>
                                <input type="number" name="period" id="period" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="control-label">{{__('Status')}}</label>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="switch-1" name="status" value="1" checked>
                                <label for="switch-1">{{__('Active')}}</label>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer" style="">

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
                <li class="breadcrumb-item"><a href="{{route('admin:assets.index')}}"> {{__('Assets')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('Assets Maintenance') }}</li>
            </ol>
        </nav>
{{--        @include('admin.assets.assets_maintenance.search')--}}

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-cubes"></i> [{{count($items)}}] {{ __('Assets Maintenance')." : " .$asset->name }}
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
                                'route' => 'admin:assets.delete_selected',
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
                                <th scope="col"> {{ __('Name') }} </th>
                                <th scope="col"> {{ __('Maintenance Types') }} </th>
                                <th scope="col"> {{ __('Maintenance Detection') }} </th>
                                <th scope="col"> {{ __('Number Of KM or Hour') }} </th>
                                <th scope="col"> {{ __('Maintenance Period (by month)') }} </th>
                                <th scope="col"> {{ __('Status') }} </th>
                                <th scope="col"> {{ __('Created At') }} </th>
                                <th scope="col"> {{ __('Updated At') }} </th>
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
                                <th scope="col"> {{ __('Name') }} </th>
                                <th scope="col"> {{ __('Maintenance Types') }} </th>
                                <th scope="col"> {{ __('Maintenance Detection') }} </th>
                                <th scope="col"> {{ __('Number Of KM or Hour') }} </th>
                                <th scope="col"> {{ __('Maintenance Period (by month)') }} </th>
                                <th scope="col"> {{ __('Status') }} </th>
                                <th scope="col"> {{ __('Created At') }} </th>
                                <th scope="col"> {{ __('Updated At') }} </th>
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
    {!! JsValidator::formRequest('App\Http\Requests\AssetMaintenanceRequest')->selector('#newAssetEmployee-form'); !!}
    <script type="application/javascript">
        $(document).ready(function () {

            $('#add-employee-modal').on('show.bs.modal', function (event) {
                // $('#maintenance_detection_type_id, #maintenance_detection_id').select2({
                //     dropdownParent: $('#add-employee-modal')
                // });
                var button = $(event.relatedTarget);
                var asset_maintenance_id = button.data('asset_maintenance_id');
                $('#asset_maintenance_id').val(asset_maintenance_id);

                var asset_id = button.data('asset_id');
                $('#asset_id').val(asset_id);

                var name_ar = button.data('name_ar');
                $('#name_ar').val(name_ar);

                var name_en = button.data('name_en');
                $('#name_en').val(name_en);

                var maintenance_detection_type_id = button.data('maintenance_detection_type_id');
                $('#maintenance_detection_type_id').val(maintenance_detection_type_id);

                var maintenance_detection_id = button.data('maintenance_detection_id');
                $('#maintenance_detection_id').val(maintenance_detection_id);

                var maintenance_type = button.data('maintenance_type');
                $('#maintenance_type').val(maintenance_type);

                var number_of_km_h = button.data('number_of_km_h');
                $('#number_of_km_h').val(number_of_km_h);

                var period = button.data('period');
                $('#period').val(period);

                var status = button.data('status');
                $('.status').val(status);
                if (maintenance_detection_type_id && maintenance_detection_type_id != '') {
                    $('#maintenance_detection_type_id').val(maintenance_detection_type_id).trigger('change');
                } else {
                    $('#maintenance_detection_type_id').val(0).trigger('change');
                    $("#maintenance_detection_type_id").select2("val", '');
                }

                if (maintenance_type && maintenance_type != '') {
                    $('#maintenance_type').val(maintenance_type).trigger('change');
                } else {
                    $('#maintenance_type').val(0).trigger('change');
                    $("#maintenance_type").select2("val", '');
                }

                var title = button.data('title');
                if (title === undefined) {
                    $('#myModalLabel-1').text('{{__('Add new asset maintenance')}}');
                }
                $('#myModalLabel-1').text(title);
            });

            $('#add-employee-modal').on('hide.bs.modal', function (event) {
                $("#empId").select2("val", '');
                $("#newAssetEmployee-form").get(0).reset();
                $(".error-help-block").each(function (index, element) {
                    element.remove();
                })
                $("form#newAssetEmployee-form .form-group").each(function () {
                    $(this).removeClass('has-error');
                });
            });
        });

        server_side_datatable('#datatable-with-btns');

        function filterFunction($this) {
            $("#loaderSearch").show();
            $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
            $datatable.ajax.url($url).load();
            $(".js__card_minus").trigger("click");
            setTimeout(function () {
                $("#loaderSearch").hide();
            }, 1000)
        }
    </script>
@stop
