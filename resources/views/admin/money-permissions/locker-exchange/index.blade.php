@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('words.locker-exchanges')}} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('words.locker-exchanges')}}</li>
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
                    <i class="fa fa-money"></i> {{__('Lockers Transfer')}}
                </h4>
                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-button', [
                                'button_url' => 'admin:locker-exchanges.create',
                                'button_text' => __('words.create-locker-exchange-locker'),
                            ])
                        </li>
                        <li class="list-inline-item">
                            @include('admin.buttons.add-button', [
                                'button_url' => 'admin:locker-exchanges.create',
                                'button_args' => ['type' => 'bank'],
                                'button_text' => __('words.create-locker-exchange-bank'),
                            ])
                        </li>
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:locker-exchanges.delete_selected',
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
                                <th scope="col">#</th>
                                <th scope="col">{!! __('words.permission-number') !!}</th>
                                <th scope="col">{!! __('words.from') !!}</th>
                                <th scope="col">{!! __('words.to') !!}</th>
                                <th scope="col">{!! __('words.destination-type') !!}</th>
                                <th scope="col">{!! __('words.money-receiver') !!}</th>
                                <th scope="col">{!! __('the Amount') !!}</th>
                                <th scope="col">{!! __('words.operation-date') !!}</th>
                                <th scope="col">{!! __('words.permission-status') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
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
                                <th scope="col">#</th>
                                <th scope="col">{!! __('words.permission-number') !!}</th>
                                <th scope="col">{!! __('words.from') !!}</th>
                                <th scope="col">{!! __('words.to') !!}</th>
                                <th scope="col">{!! __('words.destination-type') !!}</th>
                                <th scope="col">{!! __('words.money-receiver') !!}</th>
                                <th scope="col">{!! __('the Amount') !!}</th>
                                <th scope="col">{!! __('words.operation-date') !!}</th>
                                <th scope="col">{!! __('words.permission-status') !!}</th>
                                <th scope="col">{!! __('created at') !!}</th>
                                <th scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($exchanges as $exchange)
                                <tr>
                                    <td class="text-center column-permission-number">{!! $exchange->permission_number !!}</td>
                                    <td class="text-center column-locker-from">{!! optional($exchange->fromLocker)->name !!}</td>
                                    <td class="text-center column-locker-to">
                                        @if($exchange->destination_type == 'bank')
                                            {!! optional($exchange->toBank)->name !!}
                                        @else
                                            {!! optional($exchange->toLocker)->name !!}
                                        @endif
                                    </td>
                                    <td class="text-center column-destination-type">{!! __('words.'.$exchange->destination_type) !!}</td>
                                    <td class="text-center column-money-receiver">{!! optional($exchange->employee)->name !!}</td>
                                    <td class="text-danger text-center column-amount">{!! $exchange->amount !!}</td>
                                    <td class="text-center column-operation-date">{!! $exchange->operation_date !!}</td>
                                    <td class="text-center column-status">
                                        {!! $exchange->render_status($exchange->status ,__('words.'.$exchange->status)) !!}
                                    </td>
                                    <td class="text-center column-created-at">
                                        {!! optional($exchange->created_at)->format('y-m-d h:i:s A') !!}
                                    </td>
                                    <td class="text-center column-updated-at">
                                        {!! optional($exchange->updated_at)->format('y-m-d h:i:s A') !!}
                                    </td>
                                    <td>
                                        @if($exchange->status == 'pending')
                                            @component('admin.buttons._edit_button',[
                                                'id' => $exchange->id,
                                                'route' => 'admin:locker-exchanges.edit',
                                            ])
                                            @endcomponent

                                            @component('admin.buttons._delete_button',[
                                                'id' => $exchange->id,
                                                'route' => 'admin:locker-exchanges.destroy',
                                                'tooltip' => __('Delete '.$exchange->permission_number),
                                            ])
                                            @endcomponent
                                        @endif
                                        <a class="btn btn-info"
                                           onclick="load_money_permission_model('{{ route('admin:locker-exchanges.show' ,['id' => $exchange->id]) }}')">
                                            <i class="fa fa-eye"></i> {{ __('Show') }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($exchange->status == 'pending')
                                            @component('admin.buttons._delete_selected',[
                                                'id' => $exchange->id,
                                                'route' => 'admin:locker-exchanges.delete_selected',
                                            ])
                                            @endcomponent
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
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
    <div class="modal fade wg-content" id="addNewLockerReceive" role="dialog">
        <div class="modal-dialog" style="width:800px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-center"> {{ __('Create Locker receive') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="box-loader">
                        <p>{{__('Loading')}}</p>
                        <div class="loader-31"></div>
                    </div>
                    <div id="responseData">

                    </div>
                    <div class="modal-footer">
                        <button type="submit" form="formCreateNewLockerReciver" class="btn hvr-rectangle-in saveAdd-wg-btn">
                            <i class="ico ico-left fa fa-save"></i>
                            {{__('Save')}}
                        </button>
                        <button  type="button" class="btn hvr-rectangle-in closeAdd-wg-btn" data-dismiss="modal">
                            <i class="ico ico-left fa fa-close"></i>
                            {{__('Close')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                }
            })
        }

        function printExchange(id) {
            var element_id = 'printThis' + id, page_title = document.title
            print_element(element_id, page_title)
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

        function loadDataWithModal(route) {
            event.preventDefault();
            $.ajax({
                url: route,
                type: 'get',
                success: function (response) {
                    $('#addNewLockerReceive').modal('show');
                    setTimeout( () => {
                        $('.box-loader').hide();
                        $('#responseData').html(response.data);
                        $('#empModalTOFire').select2();
                        $('#cost_center_id_modal').select2();
                    },3000)
                }
            });
        }

        $('#addNewLockerReceive').on('hidden.bs.modal', function () {
            $('.box-loader').show();
            $('#responseData').html('');
        })

        function getLibraryFiles(id) {
            $("#library_item_id").val(id);
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                type: 'post',
                url: '{{route('admin:locker-exchanges.library.get.files')}}',
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
                        url: '{{route('admin:locker-exchanges.library.file.delete')}}',
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
                url: "{{route('admin:locker-exchanges.upload_library')}}",
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
