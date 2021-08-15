@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Parts') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugin/iCheck/skins/square/blue.css')}}">
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
    <link href="{{ asset('css/datatable-print-styles.css') }}" rel='stylesheet'/>
@endsection
@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Parts management')}}</li>
            </ol>
        </nav>

        @if(filterSetting())
            @include('admin.parts.search')
        @endif


        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-gears"></i> {{__('Parts management')}}
                </h4>

                <div class="card-content js__card_content">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:parts.create',
                           'new' => '',
                          ])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                          'route' => 'admin:parts.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th class="text-center " scope="col">{!! __('#') !!}</th>
                                <th class="text-center" scope="col">{!! __('Name') !!}</th>
                                <th class="text-center" scope="col">{!! __('Type') !!}</th>
                                <th class="text-center" scope="col">{!! __('Quantity') !!}</th>
                                <th class="text-center" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center" scope="col">{!! __('Reviewable') !!}</th>
                                <th class="text-center" scope="col">{!! __('taxable') !!}</th>
                                <th class="text-center" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox"  id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="text-center" scope="col">{!! __('#') !!}</th>
                                <th class="text-center" scope="col">{!! __('Name') !!}</th>
                                <th class="text-center" scope="col">{!! __('Type') !!}</th>
                                <th class="text-center" scope="col">{!! __('Quantity') !!}</th>
                                <th class="text-center" scope="col">{!! __('Status') !!}</th>
                                <th class="text-center" scope="col">{!! __('Reviewable') !!}</th>
                                <th class="text-center" scope="col">{!! __('taxable') !!}</th>
                                <th class="text-center" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <input type="hidden" id="part_id">

            </div>
        </div>
    </div>

@endsection



@section('modals')

    @include('admin.parts.quantity_modal')

    @include('admin.parts.taxes_modal')

    @include('admin.partial.part_image')

    <div class="modal fade modal-bg-wg" id="boostrapModal-2" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Part Library')}}</h4>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <form action="{{route('admin:suppliers.upload.upload_library')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf

                            <div class="form-group col-md-3">
                                <label>{{__('Title_ar')}}</label>
                                <input type="text" name="title_ar"  class="form-control" id="library_title_ar">
                            </div>

                            <div class="form-group col-md-3">
                                <label>{{__('Title_en')}}</label>
                                <input type="text" name="title_en"  class="form-control" id="library_title_en">
                            </div>

                            <div class="form-group col-md-4">
                                <label>{{__('files')}}</label>
                                <input type="file" name="files[]" class="form-control" id="files" multiple>
                                <input type="hidden" name="part_id" value="" id="library_part_id">
                            </div>

                            <div class="form-group col-md-1">
                                <button type="button" class="btn btn-primary" onclick="uploadPartFiles()"
                                        style="margin-top: 28px;">{{__('save')}}</button>
                            </div>

                            <div class="form-group col-md-1" id="upload_loader" style="display: none;">
                                <img src="{{asset('default-images/loading.gif')}}" title="loader"
                                     style="width: 34px;height: 39px;margin-top: 27px;">
                            </div>
                        </form>
                    </div>

                    <hr>

                    <div id="part_files_area" class="row" style="text-align: center">


                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')

    <script type="application/javascript">
        $("#selectAll").click(function () {
            $(".to_checked").prop("checked", $(this).prop("checked"));
        });

        $(".to_checked").click(function () {
            if (!$(this).prop("checked")) {
                $("#selectAll").prop("checked", false);
            }
        });

        function getLibraryPartId(id) {

            $("#library_part_id").val(id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',
                url: '{{route('admin:parts.upload_library')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                beforeSend: function () {
                    // $("#loader_get_test_video").show();
                },

                success: function (data) {

                    $("#part_files_area").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function removeFile(id) {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            swal({

                title: "Delete File",
                text: "Are you sure want to delete this file ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $.ajax({

                        type: 'post',
                        url: '{{route('admin:parts.upload_library.file.delete')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                        },

                        beforeSend: function () {
                            // $("#loader_get_test_video").show();
                        },

                        success: function (data) {

                            $("#file_" + data.id).remove();

                            swal({text: 'file deleted successfully', icon: "success"});
                        },

                        error: function (jqXhr, json, errorThrown) {
                            // $("#loader_save_goals").hide();
                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });
                }
            });

        }

        function uploadPartFiles() {

            var form_data = new FormData();

            var part_id = $("#library_part_id").val();
            var title_ar = $("#library_title_ar").val();
            var title_en = $("#library_title_en").val();

            var totalfiles = document.getElementById('files').files.length;

            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }

            form_data.append("part_id", part_id);
            form_data.append("title_ar", title_ar);
            form_data.append("title_en", title_en);

            $.ajax({
                url: "{{route('admin:parts.upload.upload_library')}}",
                type: "post",

                headers: {
                    "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
                },
                data: form_data,
                dataType: 'json',
                contentType: false,
                processData: false,

                beforeSend: function () {
                    $('#upload_loader').show();
                },

                success: function (data) {

                    $('#upload_loader').hide();

                    swal("{{__('Success')}}", data.message, "success");

                    $("#part_files_area").prepend(data.view);

                    $("#files").val('');
                    $("#library_title_ar").val('');
                    $("#library_title_en").val('');

                    $("#no_files").remove();

                },
                error: function (jqXhr, json, errorThrown) {
                    $('#upload_loader').hide();
                    var errors = jqXhr.responseJSON;
                    swal("{{__('Sorry')}}", errors, "error");
                },
            });
        }

        function partTaxable(part_id) {
            if ($('#taxable-' + part_id).is(':checked')) {
                $('.part_taxable').prop('disabled', false);
            } else {
                $('.part_taxable').prop('disabled', true);
            }
        }

        function getPartImage(index) {
            let image_path = $('#part_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

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

        function showAlternativeParts(id) {
            var modal = $("#m-"+id).remodal();
            modal.open();
        }
    </script>
    @include('opening-balance.common-script')
@endsection
