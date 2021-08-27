@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Banks Data') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugin/iCheck/skins/square/blue.css')}}">
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="#"> {{__('Managing bank accounts')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Banks Data')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-bank"></i> {{__('Banks Data')}}
                    <span class="text-danger">[{{count($items->get())}}]</span>
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', ['route' => 'admin:banks.bank_data.create', 'new' => ''])
                        </li>
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:suppliers.deleteSelected'])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col"> {{ __('#') }} </th>
                                    <th>{{__('Bank Name')}}</th>
                                    <th>{{__('Branch Name')}}</th>
                                    <th>{{__('Branch Code')}}</th>
                                    <th>{{__('Swift Code')}}</th>
                                    <th scope="col"> {{ __('Start Date With Bank') }} </th>
                                    <th scope="col"> {{ __('status') }} </th>
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
                                    <th>{{__('Bank Name')}}</th>
                                    <th>{{__('Branch Name')}}</th>
                                    <th>{{__('Branch Code')}}</th>
                                    <th>{{__('Swift Code')}}</th>
                                    <th scope="col"> {{ __('Start Date With Bank') }} </th>
                                    <th scope="col"> {{ __('status') }} </th>
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

@endsection

@section('modals')
    @include('admin.partial.maps.show_location')
    @include('admin.partial.upload_library.form', ['url'=> route('admin:opening.balance.upload_library')])
    <div class="modal fade wg-content" id="showBankData" role="dialog">
        <div class="modal-dialog" style="width:800px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="box-loader">
                        <p>{{__('Loading')}}</p>
                        <div class="loader-31"></div>
                    </div>
                    <div id="showBankDataResponse">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('admin.partial.maps.show_location_js')
    <script>
        function getLibraryFiles(id) {
            $("#library_item_id").val(id);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                url: '{{route('admin:banks.bank_data.library.get.files')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
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
                        url: '{{route('admin:banks.bank_data.library.file.delete')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
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
            var title_ar = $("#library_title_ar").val();
            var title_en = $("#library_title_en").val();
            var totalfiles = document.getElementById('files').files.length;
            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }
            form_data.append("item_id", item_id);
            form_data.append("title_ar", title_ar);
            form_data.append("title_en", title_en);
            $.ajax({
                url: "{{route('admin:banks.bank_data.upload_library')}}",
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

        function loadDataWithModal(route, modal, target) {
            event.preventDefault();
            $.ajax({
                url: route,
                type: 'get',
                success: function (response) {
                    $('#showBankData').modal('show');
                    setTimeout( () => {
                        $('.box-loader').hide();
                        $('#showBankDataResponse').html(response.data);
                        modalDatatable('productsTable')
                    },1000)
                }
            });
        }

        $('#showBankData').on('hidden.bs.modal', function () {
            $('.box-loader').show();
            $('#showBankDataResponse').html('');
        })

        function checkAllProducts(event) {
            var $inputs = $('#productList').find('input');
           if (document.getElementById('selectAllProducts').checked) {
               $inputs.prop('checked', 'checked');
           } else {
               $inputs.prop('checked', '');
           }

        }
    </script>
@endsection
