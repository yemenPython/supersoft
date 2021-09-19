@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Accounts') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugin/iCheck/skins/square/blue.css')}}">
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0 !important;padding:0">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="#"> {{__('Managing bank accounts')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Accounts')}}</li>
            </ol>
        </nav>

         @include('admin.banks.banks_accounts.search_form')
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-bank"></i> {{__('Accounts')}}
                    <span class="text-danger">[{{count($items->get())}}]</span>
                </h4>

                <div class="card-content new-columns-wg js__card_content" style="">
                    <div class="row">
                       <div class="col-md-12">
                            <div class="row-content">
                            Full width Row
                            </div>
                       </div>
                    </div>
                    <div class="row">
                       <div class="col-md-4">
                       <div class="row-content">
                       One col
                            </div>
                       </div>
                       <div class="col-md-4">
                       <div class="row-content">
                       One col
                            </div>
                       </div>
                       <div class="col-md-4">
                       <div class="row-content">
                       One col
                            </div>
                       </div>
                    </div>
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', ['route' => 'admin:banks.banks_accounts.create', 'new' => ''])
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
                                    @if (authIsSuperAdmin())
                                        <th>{{__('Branch')}}</th>
                                    @endif
                                    <th scope="col">{!! __('Type Bank Account') !!}</th>
                                    <th>{{__('Bank Name')}}</th>
                                    <th>{{__('Balance')}}</th>
                                    <th scope="col">{!! __('created at') !!}</th>
                                    <th scope="col">{!! __('Updated at') !!}</th>
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
                                    @if (authIsSuperAdmin())
                                        <th>{{__('Branch')}}</th>
                                    @endif
                                    <th scope="col">{!! __('Type Bank Account') !!}</th>
                                    <th>{{__('Bank Name')}}</th>
                                    <th>{{__('Balance')}}</th>
                                    <th scope="col">{!! __('created at') !!}</th>
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

@endsection

@section('modals')

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
    <script>
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


        $("#main_type_bank_account_id").change(function () {
            let main_bank_account_type = $(this).find(':selected').attr('data-mainType')
            if (!main_bank_account_type) {
                $("#sectionSwitchForCurrentAccounts").show();
                $("#sectionSwitchCertAccounts").show();
                $("#sectionSelectCertAccounts").show();
                $("#datesForCert").show();
                return false;
            }
            if (main_bank_account_type == 'حسابات جارية') {
                $("#sectionSwitchForCurrentAccounts").show();
                $("#sectionSwitchCertAccounts").hide();
                $("#sectionSelectCertAccounts").hide();
                $("#datesForCert").hide();
            } else {
                $("#sectionSwitchForCurrentAccounts").hide();
                $("#sectionSwitchCertAccounts").show();
                $("#sectionSelectCertAccounts").show();
                $("#datesForCert").show();
            }
        });


        function getLibraryFiles(id) {
            $("#library_item_id").val(id);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                url: '{{route('admin:banks.banks_accounts.library.get.files')}}',
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
                        url: '{{route('admin:banks.banks_accounts.library.file.delete')}}',
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
                url: "{{route('admin:banks.banks_accounts.upload_library')}}",
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
    </script>
@endsection
