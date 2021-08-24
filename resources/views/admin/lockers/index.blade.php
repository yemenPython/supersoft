@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Super Car') }} - {{ __('Lockers') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('Lockers')}}</li>
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
                    @include('admin.lockers.form_search')
                </div>
            </div>
        </div>
        @endif

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-folder-open-o"></i>  {{__('Lockers')}}
                 </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                            'route' => 'admin:lockers.create',
                           'new' => '',
                          ])
                        </li>
                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                             'route' => 'admin:lockers.deleteSelected',
                              ])
                                @endcomponent
                            </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                               <th scope="col">{!! __('Branch') !!}</th>
                                <th scope="col">{!! __('Locker name') !!}</th>
                                <th scope="col">{!! __('Locker balance') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Branch') !!}</th>
                                <th scope="col">{!! __('Locker name') !!}</th>
                                <th scope="col">{!! __('Locker balance') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
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
@endsection

@section('modals')
    @include('admin.partial.upload_library.form', ['url'=> route('admin:opening.balance.upload_library')])
@endsection

@section('js')
    <script type="application/javascript">
        server_side_datatable('#datatable-with-btns');

        function filterFunction($this) {
            $("#loaderSearch").show();
            $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
            $datatable.ajax.url($url).load();
            $(".js__card_minus").trigger("click");
            setTimeout(function(){
                $("#loaderSearch").hide();
            }, 1000)
        }

        function getLibraryFiles(id) {
            $("#library_item_id").val(id);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                url: '{{route('admin:lockers.library.get.files')}}',
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
                        url: '{{route('admin:lockers.library.file.delete')}}',
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
                url: "{{route('admin:lockers.upload_library')}}",
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
