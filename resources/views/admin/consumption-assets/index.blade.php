@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Consumption Assets') }} </title>
@endsection

@section('style')

@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Consumption Assets')}}</li>
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

                                <div class="form-group col-md-6">
                                    <label> {{ __('Asset name') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>

                                        <select class="form-control select2" id="asset_id"
                                                name="asset_id">
                                            <option value="0"> {{ __('Select Name') }} </option>
                                            @foreach($assets as $asset)
                                                <option
                                                    {{ old('asset_id') == $asset->id ? 'selected' : '' }}
                                                    value="{{ $asset->id }}"> {{ $asset->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                    <div class="form-group col-md-6">
                                        <label> {{ __('Number') }} </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>

                                            <select class="form-control select2" id="number"
                                                    name="number">
                                                <option value="0"> {{ __('Select') }} </option>
                                                @foreach($numbers as $number)
                                                    <option value="{{$number->number}}"> {{$number->number}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                    <label> {{ __('consumption amount From') }}</label>
                                    <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-money"></i></span>

                                    <input type="text" class="form-control" name="consumption_amount_from">
                                </div>
                                </div>

                                    <div class="form-group col-md-3">
                                        <label> {{ __('consumption amount To') }}</label>
                                        <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-money"></i></span>

                                        <input type="text" class="form-control" name="consumption_amount_to">
                                    </div>
                                    </div>

                                <div class="form-group col-md-6">
                                    <label> {{ __('date From') }}</label>
                                    <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                                    <input type="date" class="form-control" name="date_from">
                                </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label> {{ __('date To') }}</label>
                                    <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                                    <input type="date" class="form-control" name="date_to">
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

{{--                @include('admin.purchase-assets.parts.search')--}}

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-o"></i> {{__('Consumption Assets')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:consumption-assets.create',
                           'new' => '',
                          ])
                        </li>

                        <li class="list-inline-item">
                            <button style="margin-bottom: 12px; border-radius: 5px" type="button" class="btn btn-icon btn-icon-left btn-delete-wg waves-effect waves-light hvr-bounce-to-left" onclick="confirmDeleteSelected('{{route('admin:purchase-assets.deleteSelected')}}')">
                                <i class="ico fa fa-trash"></i>  {{__('Delete Selected')}}
                            </button>

                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                @if(authIsSuperAdmin())
                                <th class="text-center" scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th class="text-center column-invoice-type" scope="col">{!! __('Date') !!}</th>
                                <th class="text-center" scope="col">{!! __('Number') !!}</th>
                                <th class="text-center" scope="col">{!! __('Date From') !!}</th>
                                <th class="text-center" scope="col">{!! __('Date to') !!}</th>
                                <th>{{__('total consumption')}}</th>
                                <th class="text-center">{!! __('created at') !!}</th>
                                <th class="text-center">{!! __('Updated at') !!}</th>
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
                                <th>#</th>
                                @if(authIsSuperAdmin())
                                <th class="text-center" scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th class="text-center column-invoice-type" scope="col">{!! __('Date') !!}</th>

                                <th class="text-center"
                                    scope="col">{!! __('Number') !!}</th>
                                <th class="text-center" scope="col">{!! __('Date From') !!}</th>
                                <th class="text-center" scope="col">{!! __('Date to') !!}</th>
                                <th>{{__('total consumption')}}</th>
                                <th class="text-center">{!! __('created at') !!}</th>
                                <th class="text-center">{!! __('Updated at') !!}</th>
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
@stop


@section('js')
    <script type="application/javascript">

        $(document).ready(function () {
            server_side_datatable($('#datatable-with-btns'))
            $(".select2").select2()
        })
        function printAsset() {
            var element_id = 'asset_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:consumption-assets.show') }}?id=" + id,
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
                url: "{{ route('admin:assets.getAssetsByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#asset_id').each(function () {
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
                url: "{{ route('admin:consumption-assets.getNumbersByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#number').each(function () {
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
            let branch_id = $('#branch_id').find(":selected").val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsByAssetsGroup')}}",
                data: {
                    asset_group_id: asset_group_id,
                    branch_id:branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#asset_id').each(function () {
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
@section('modals')
    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Purchase Asset')}}</h4>
                </div>

                <div class="modal-body" id="assetDatatoPrint">
                </div>
                <div class="modal-footer" style="text-align:center">
                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printAsset()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">
                        <i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>

    {{--        @include($view_path . '.column-visible')--}}
@endsection
