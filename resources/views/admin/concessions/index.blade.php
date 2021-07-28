@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Concessions') }} </title>
@endsection

@section('style')
    <style>
        .wg-label {
            font-size: 10px !important;
            padding: 3px !important;
        }
    </style>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Concessions')}}</li>
            </ol>
        </nav>

        @include('admin.concessions.search_form')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-text-o"></i> {{__('Concessions')}}
                </h4>

                <div class="card-content js__card_content" style="width:100%;margin-top: 15px">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:concessions.create',  'new' => '',])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:concession.deleteSelected',])
                            @endcomponent
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_archive_selected',[
                          'route' => 'admin:services.archiveSelected',
                           ])
                            @endcomponent
                        </li>
                        <li class="list-inline-item">
                            @include('admin.buttons._archive', [
                       'route' => 'admin:concessions.archive',
                           'new' => '',
                          ])
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">{!! __('Date') !!}</th>
                                <th scope="col" class="text-center">{!! __('Concession number') !!}</th>
                                <th scope="col" class="text-center">{!! __('Total') !!}</th>
                                <th scope="col" class="text-center">{!! __('Type') !!}</th>
                                <th scope="col" class="text-center">{!! __('Item Number') !!}</th>
                                <th scope="col" class="text-center">{!! __('Concession Type') !!}</th>
                                <th scope="col" class="text-center">{!! __('Status') !!}</th>
                                <th scope="col" class="text-center">{!! __('Execution Status') !!}</th>
                                <th class="text-center" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col" class="text-center">{!! __('Options') !!}</th>
                                <th scope="col" class="text-center">{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col" class="text-center">{!! __('Date') !!}</th>
                                <th scope="col" class="text-center">{!! __('Concession number') !!}</th>
                                <th scope="col" class="text-center">{!! __('Total') !!}</th>
                                <th scope="col" class="text-center">{!! __('Type') !!}</th>
                                <th scope="col" class="text-center">{!! __('Item Number') !!}</th>
                                <th scope="col" class="text-center">{!! __('Concession Type') !!}</th>
                                <th scope="col" class="text-center">{!! __('Status') !!}</th>
                                <th scope="col" class="text-center">{!! __('Execution Status') !!}</th>
                                <th class="text-center" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col" class="text-center">{!! __('Options') !!}</th>
                                <th scope="col" class="text-center">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('admin.partial.execution_period_form', ['items'=> $concessions->get(), 'url'=> route('admin:concessions.execution.save'), 'title' => __('Concession Execution') ])
    @include('admin.partial.upload_library.form', ['url'=> route('admin:concession.upload_library')])

    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-print-padd">
                <div class="modal-header">
                <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()" id="print_sales_invoice">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body print-border" id="invoiceDatatoPrint" style="border:1px solid #3b3b3b;margin:0 20px;border-radius:5px">

                </div>
                <div class="modal-footer" style="text-align:center">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\ConcessionExecution\CreateRequest', '.form'); !!}

    <script type="application/javascript">

        function printDownPayment() {
            var element_id = 'concession_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:concessions.show') }}?concession_id=" + id,
                method: 'GET',
                success: function (data) {
                    $("#invoiceDatatoPrint").html(data.view)
                }
            });
        }

        function getLibraryFiles(id) {
            $("#library_item_id").val(id);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                url: '{{route('admin:concession.library.get.files')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },
                beforeSend: function () {
                },
                success: function (data) {
                    $("#files_area").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
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
                        url: '{{route('admin:concession.library.file.delete')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                        },
                        beforeSend: function () {
                        },
                        success: function (data) {
                            $("#file_" + data.id).remove();
                            swal({text: 'file deleted successfully', icon: "success"});
                        },
                        error: function (jqXhr, json, errorThrown) {
                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });
                }
            });
        }

        function uploadFiles() {
            var form_data = new FormData();
            var item_id = $("#library_item_id").val();
            var totalfiles = document.getElementById('files').files.length;
            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }
            form_data.append("item_id", item_id);
            $.ajax({
                url: "{{route('admin:concession.upload_library')}}",
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

                    $("#files_area").prepend(data.view);

                    $("#files").val('');

                    $("#no_files").remove();

                },
                error: function (jqXhr, json, errorThrown) {

                    $('#upload_loader').hide();
                    var errors = jqXhr.responseJSON;
                    swal("{{__('Sorry')}}", errors, "error");
                },
            });
        }

        function getConcessionItems() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let concession_type_id = $('#concession_type_id').find(":selected").val();

            $.ajax({

                type: 'post',
                url: '{{route('admin:concessions.get.items.index.search')}}',
                data: {
                    _token: CSRF_TOKEN,
                    concession_type_id: concession_type_id,
                },

                success: function (data) {

                    $("#concession_items").html(data.view);
                    $("#model_name").val(data.model);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function showSelectedTypes (type) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $('.remove_items').remove();

            $.ajax({

                type: 'post',
                url: '{{route('admin:concessions.get.types.index.search')}}',
                data: {
                    _token: CSRF_TOKEN,
                    type:type,
                },

                success: function (data) {

                    $("#concession_types").html(data.view);

                    $('.remove_concession_for_new').remove();

                    let option = new Option();
                    option.text = '{{__('Select')}}';
                    option.value = '';

                    $(".concessions_numbers").append(option);

                    $.each(data.concessions, function (key, modelName) {

                        var option = new Option(modelName, modelName);
                        option.text = modelName.number;
                        option.value = modelName.id;

                        $(".concessions_numbers").append(option);

                        $('.concessions_numbers option').addClass(function () {
                            return 'remove_concession_for_new';
                        });
                    });


                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
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
    </script>

@endsection
