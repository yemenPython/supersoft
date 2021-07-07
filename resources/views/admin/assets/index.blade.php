@extends('admin.layouts.app')
@section('style')
    <style>
        .dataTables_length{
            float: left !important;
        }
    </style>
@endsection
@section('title')
    <title>{{ __('words.assets') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('words.assets') }}</li>
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
                    <div class="card-content js__card_content" style="padding:30px">
                        <form  onsubmit="filterFunction($(this));return false;">
                            <div class="list-inline margin-bottom-0 row">

                                    @if(authIsSuperAdmin())
                                        <div class="form-group col-md-12">
                                            <label> {{ __('Branches') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon fa fa-file"></span>
                                                {!! drawSelect2ByAjax('branch_id','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch'),request()->branch_id) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> {{ __('Assets Groups') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select class="form-control select2" id="asset_group_id"
                                                        name="asset_group_id">
                                                    <option value="0"> {{ __('Select Group') }} </option>
                                                    @foreach($assetsGroups as $assetGroup)
                                                        <option
                                                            {{ old('assetsGroups_id') == $assetGroup->id ? 'selected' : '' }}
                                                            value="{{ $assetGroup->id }}"
                                                            rate="{{ $assetGroup->annual_consumtion_rate }}"> {{ $assetGroup->name_ar }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> {{ __('Assets Types') }} </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                                <select class="form-control select2" name="asset_type_id"
                                                        id="asset_type_id">
                                                    <option value="0"> {{ __('Select Type') }} </option>
                                                    @foreach($assetsTypes as $assetType)
                                                        <option
                                                            {{ old('asset_type_id') == $assetType->id ? 'selected' : '' }}
                                                            value="{{ $assetType->id }}"> {{ $assetType->name_ar }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label> {{ __('Asset name') }} </label>
                                        <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-file-text"></i></span>

                                        <select class="form-control select2" id="name"
                                                name="name">
                                            <option value="0"> {{ __('Select Name') }} </option>
                                            @foreach($assets as $asset)
                                                <option
                                                    {{ old('asset_type_id') == $asset->id ? 'selected' : '' }}
                                                    value="{{ $asset->id }}"> {{ $asset->name }} </option>
                                            @endforeach
                                        </select>
{{--                                        {!! drawSelect2ByAjax('name','Asset','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Name'),request()->name) !!}--}}
                                    </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                            <label> {{ __('Assets employees') }} </label>
                                            <select class="form-control select2" id="employee_id"
                                                    name="employee_id">
                                                <option value="0"> {{ __('Select Employee Name') }} </option>
                                            @foreach($assetEmployees as $assetEmployee)
                                                <option
                                                    {{ old('employee_id') == $assetEmployee->id ? 'selected' : '' }}
                                                    value="{{ $assetEmployee->id }}"> {{ $assetEmployee->name_ar }} </option>
                                            @endforeach
                                            </select>
{{--                                            {!! drawSelect2ByAjax('employee_id','EmployeeData', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->employee) !!}--}}
                                        </div>





                                    <div class="form-group col-md-2">
                                        <label> {{ __('consumtion rate From') }}</label>
                                        <input type="text" class="form-control" name="annual_consumtion_rate1">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label> {{ __('consumtion rate To') }}</label>
                                        <input type="text" class="form-control" name="annual_consumtion_rate2">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label> {{ __('asset age From') }}</label>
                                        <input type="text" class="form-control" name="asset_age1">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label> {{ __('asset age To') }}</label>
                                        <input type="text" class="form-control" name="asset_age2">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label> {{ __('cost of purchase From') }}</label>
                                        <input type="text" class="form-control" name="purchase_cost1">
                                    </div>

                                        <div class="form-group col-md-2">
                                        <label> {{ __('cost of purchase To') }}</label>
                                        <input type="text" class="form-control" name="purchase_cost2">
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label> {{ __('purchase date From') }}</label>
                                        <input type="date" class="form-control" name="purchase_date1">
                                    </div>
                                        <div class="form-group col-md-2">
                                        <label> {{ __('purchase date To') }}</label>
                                        <input type="date" class="form-control" name="purchase_date2">
                                    </div>

                                        <div class="form-group col-md-2">
                                            <label> {{ __('work date From') }}</label>
                                            <input type="date" class="form-control" name="date_of_work1">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label> {{ __('work date To') }}</label>
                                            <input type="date" class="form-control" name="date_of_work2">
                                        </div>

                                        <div class="col-md-4">
                                        <div class="form-group">
                                            <label> {{ __('Asset Status') }} </label>
                                            <div class="input-group">
                                                <ul class="list-inline">
                                                    <li>
                                                        <div class="radio info">
                                                            <input type="radio" id="radio_status_1" name="asset_status"
                                                                   value="1">
                                                            <label for="radio_status_1">{{ __('continues') }}</label>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="radio info">
                                                            <input id="radio_status_2" type="radio" name="asset_status"
                                                                   value="2">
                                                            <label for="radio_status_2">{{ __('sell') }}</label>
                                                        </div>
                                                    </li>

                                                    <li>
                                                        <div class="radio info">
                                                            <input type="radio" id="radio_status_3" name="asset_status"
                                                                   value="3">
                                                            <label for="radio_status_3">{{ __('ignore') }}</label>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>




                            </div>

                            <button type="submit"
                                    class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                            <a href="{{route('admin:assets.index')}}"
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
                    <i class="fa fa-cubes"></i> {{ __('words.assets') }}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                                'route' => 'admin:assets.create',
                                'new' => __(''),
                            ])
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
                                <th>#</th>
                                <th scope="col"> {{ __('Branch') }} </th>
                                <th scope="col"> {{ __('Asset name') }} </th>
                                <th scope="col"> {{ __('Asset group') }} </th>
                                <th scope="col"> {{ __('Asset Status') }} </th>
                                <th scope="col"> {{ __('annual consumption rate') }} </th>
                                <th scope="col"> {{ __('asset age') }} </th>
                                <th scope="col">{!! __('Created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col"><div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}
                                </th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th scope="col"> {{ __('Branch') }} </th>
                                <th scope="col"> {{ __('Asset name') }} </th>
                                <th scope="col"> {{ __('Asset group') }} </th>
                                <th scope="col"> {{ __('Asset Status') }} </th>
                                <th scope="col"> {{ __('annual consumption rate') }} </th>
                                <th scope="col"> {{ __('asset age') }} </th>
                                <th scope="col">{!! __('Created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
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
{{--    <script src="https://code.jquery.com/jquery-3.5.1.js" type="application/javascript"></script>--}}
{{--    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js" type="application/javascript"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js" type="application/javascript"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js" type="application/javascript"></script>--}}
{{--    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js" type="application/javascript"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" type="application/javascript"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" type="application/javascript"></script>--}}
{{--    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="application/javascript"></script>--}}
    <script type="application/javascript">

        $(document).ready(function () {
            server_side_datatable('#datatable-with-btns');
            $(".select2").select2();
        });


        function printAsset() {
            var element_id = 'asset_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:assets.show') }}?asset_id=" + id,
                method: 'GET',
                success: function (data) {

                    $("#assetDatatoPrint").html(data.view)
                }
            });
        }

        $('#branch_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const branch_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsGroupsByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#asset_group_id').each(function () {
                        $(this).html(data.data)
                    });

                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        });
        $('#branch_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const branch_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsTypesByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#asset_type_id').each(function () {
                        $(this).html(data.data)
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        });
        $('#branch_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const branch_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getEmployeesByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#employee_id').each(function () {
                        $(this).html(data.data)
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        });
        $('#branch_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const branch_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#name').each(function () {
                        $(this).html(data.data)
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        });
        $('#asset_group_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const asset_group_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsByAssetsGroup')}}",
                data: {
                    asset_group_id: asset_group_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#name').each(function () {
                        $(this).html(data.data)
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        });
        $('#asset_type_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const asset_type_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsByAssetsType')}}",
                data: {
                    asset_type_id: asset_type_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#name').each(function () {
                        $(this).html(data.data)
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        });

        function filterFunction($this) {
                $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
                $datatable.ajax.url($url).load();
            $( ".js__card_minus" ).trigger( "click" );
        }

    </script>


@stop

@section('modals')

    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                <!-- <h4 class="modal-title" id="myModalLabel-1">{{__('Concession')}}</h4> -->
                </div>

                <div class="modal-body" id="assetDatatoPrint">


                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printAsset()" id="print_sales_invoice">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>
@endsection

