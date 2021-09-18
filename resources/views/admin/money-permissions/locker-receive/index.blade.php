@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('words.locker-receives')}} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('words.locker-receives')}}</li>
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
                    </h4>
                    <div class="card-content js__card_content">
                        {!! $search_form !!}
                    </div>
                </div>
            </div>
        @endif

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-money"></i>   {{__('Lockers Transfer')}}
                </h4>
                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                                'route' => 'admin:locker-receives.create',
                                'new' => '',
                            ])
                        </li>
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:locker-receives.delete_selected',
                            ])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <div class="clearfix"></div>
                        <table id="datatable-with-btns" class="table table-bordered" style="width:100%;margin-top:15px">
                            <thead>
                            <tr>
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">{!! __('words.permission-number') !!}</th>
                                <th class="text-center " scope="col">{!! __('words.exchange-number') !!}</th>
                                <th class="text-center " scope="col">{!! __('words.source-type') !!}</th>
                                <th class="text-center " scope="col">{!! __('words.money-receiver') !!}</th>
                                <th class="text-center " scope="col">{!! __('the Amount') !!}</th>
                                <th class="text-center " scope="col">{!! __('words.operation-date') !!}</th>
                                <th class="text-center " scope="col">{!! __('words.permission-status') !!}</th>
                                <th class="text-center " scope="col">{!! __('created at') !!}</th>
                                <th class="text-center " scope="col">{!! __('Updated at') !!}</th>
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
                                <th class="text-center" scope="col">#</th>
                                <th class="text-center" scope="col">{!! __('words.permission-number') !!}</th>
                                <th class="text-center" scope="col">{!! __('words.exchange-number') !!}</th>
                                <th class="text-center" scope="col">{!! __('words.source-type') !!}</th>
                                <th class="text-center" scope="col">{!! __('words.money-receiver') !!}</th>
                                <th class="text-center" scope="col">{!! __('the Amount') !!}</th>
                                <th class="text-center" scope="col">{!! __('words.operation-date') !!}</th>
                                <th class="text-center" scope="col">{!! __('words.permission-status') !!}</th>
                                <th class="text-center" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center" scope="col">{!! __('Updated at') !!}</th>
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
@endsection

@section('accounting-module-modal-area')
    @include('admin.partial.upload_library.form', ['url'=> route('admin:opening.balance.upload_library')])
    @include('admin.money-permissions.print-modal')
@endsection

@section('js')
    <script type="application/javascript">
        function load_money_permission_model(data_url) {
            var modal = $('[data-remodal-id=money-permission-modal]').remodal();
            $("[data-remodal-id=money-permission-modal]").find('div.remodal-content').text("{{ __('words.data-loading') }}")
            modal.open()

            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: data_url,
                success: function (response) {
                    $("[data-remodal-id=money-permission-modal]").find('div.remodal-content').html(response.code)
                },
                error: function (err) {
                    console.log(err)
                    // alert(err.responseJSON.message)
                }
            })
        }

        function printExchange(id) {
            var element_id = 'printThis' + id ,page_title = document.title
            print_element(element_id ,page_title)
            return true
        }

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

        function getLibraryFiles(id) {
            $("#library_item_id").val(id);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                url: '{{route('admin:locker-receives.library.get.files')}}',
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
                        url: '{{route('admin:locker-receives.library.file.delete')}}',
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
                url: "{{route('admin:locker-receives.upload_library')}}",
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
