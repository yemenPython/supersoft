@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Egyptian Federation') }} </title>
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
                <li class="breadcrumb-item active"> {{__('Egyptian Federation')}}</li>
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
                    <div class="card-content js__card_content">
                        <form id="filtration-form" action="{{route('admin:egyptian_federation.index')}}" method="get">
                            <input type="hidden" name="rows" value="{{ isset($_GET['rows']) ? $_GET['rows'] : '' }}"/>
                            <input type="hidden" name="key" value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}"/>
                            <input type="hidden" name="sort_method"
                                   value="{{ isset($_GET['sort_method']) ? $_GET['sort_method'] : '' }}"/>
                            <input type="hidden" name="sort_by"
                                   value="{{ isset($_GET['sort_by']) ? $_GET['sort_by'] : '' }}"/>
                            <input type="hidden" name="invoker"/>

                            <div class="list-inline margin-bottom-0 row">

                                @if(authIsSuperAdmin())
                                    <div class="form-group col-md-12">
                                        <label> {{ __('Branch') }} </label>
                                        <select name="branch_id" class="form-control js-example-basic-single">
                                            <option value="">{{__('Select Branch')}}</option>
                                            @foreach($branches as $k=>$v)
                                                <option value="{{$k}}">{{$v}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                    <div class="form-group col-md-6">
                                        <label> {{ __('End Date From') }} </label>
                                        <div class="input-group">

<span class="input-group-addon fa fa-calendar"></span>
                                        <input type="text" name="end_date_from" class="form-control datepicker text-right" placeholder="{{__('End Date From')}}">
                                    </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label> {{ __('End Date To') }} </label>
                                        <div class="input-group">

<span class="input-group-addon fa fa-calendar"></span>
                                        <input type="text" name="end_date_to" class="form-control datepicker text-right" placeholder="{{__('End Date To')}}">
                                    </div>
                                    </div>

                            </div>

                            <button type="submit"
                                    class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                            <a href="{{route('admin:egyptian_federation.index')}}"
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
                    <i class="fa fa-file-text-o"></i> {{__('Egyptian Federation')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                       'route' => 'admin:egyptian_federation.create',
                           'new' => '',
                          ])

                        </li>

                        <li class="list-inline-item">

                            @component('admin.buttons._confirm_delete_selected',[
                          'route' => 'admin:egyptian_federation.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <div class="clearfix"></div>
                        <table id="currencies" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th class="text-center column-id" scope="col">#</th>
                                <th class="text-center column-branch-name" scope="col">{!! __('Company name') !!}</th>
                                <th class="text-center column-Membership-No" scope="col">{!! __('Membership No') !!}</th>
                                <th class="text-center column-company-type" scope="col">{!! __('Company Type') !!}</th>
                                <th class="text-center column-funds-for" scope="col">{!! __('Subscription payment receipt') !!}</th>
                                <th class="text-center column-register-date" scope="col">{!! __('Date of register in the union') !!}</th> 
                                <th class="text-center column-funds-on" scope="col">{!! __('End date') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{{__('Options')}}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{{__('Select')}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $one)
                                <tr>
                                    <td class="text-center column-id">{{$loop->iteration}}</td>
                                    <td class="text-center column-branch-name">{!! optional($one->branch)->name !!}</td>
                                    <td class="text-center column-Membership-No">{!! $one->membership_no !!}</td>
                                    <td class="text-center  column-funds-for">{{ $one->company_type }}</td>   
                                    <td class="text-center  column-company-type">{{ $one->payment_receipt }}</td>
                                    <td class="text-center column-register-date">
                                    <span class="label light-primary wg-label">    
                                    {{ $one->date_of_register }}
                                    </span>
                                    </td>
                                    <td class="text-center column-funds-on">
                                    <span class="label light-danger wg-label">
                                        {{ $one->end_date }}
                                    </span>
                                    </td>
                                    <td class="column-created-at">{!! $one->created_at->format('y-m-d h:i:s A') !!}</td>
                                    <td class="column-updated-at">{!! $one->updated_at->format('y-m-d h:i:s A') !!}</td>

                                    <td>
                                        <div class="btn-group margin-top-10">

                                            <button type="button" class="btn btn-options dropdown-toggle"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ico fa fa-bars"></i>
                                                {{__('Options')}} <span class="caret"></span>

                                            </button>
                                            <ul class="dropdown-menu dropdown-wg">
{{--                                                <li>--}}

{{--                                                    @component('admin.buttons._show_button',[--}}
{{--                                                                   'id' => $one->id,--}}
{{--                                                                   'route'=>'admin:egyptian_federation.show'--}}
{{--                                                                    ])--}}
{{--                                                    @endcomponent--}}
{{--                                                </li>--}}
                                                <li class="btn-style-drop">
                                                    @component('admin.buttons._edit_button',[
                                                                'id' => $one->id,
                                                                'route'=>'admin:egyptian_federation.edit'
                                                                 ])
                                                    @endcomponent
                                                </li>
                                                <li class="btn-style-drop">
                                                    @component('admin.buttons._delete_button',[
                                                                'id'=>$one->id,
                                                                'route' => 'admin:egyptian_federation.destroy',
                                                                'tooltip' => __('Delete '.$one['name']),
                                                                 ])
                                                    @endcomponent
                                                </li>
                                                <li class="btn-style-drop">
                                                    <a data-toggle="modal" data-target="#boostrapModal-2"
                                                       onclick="getLibrarySupplierId('{{$one->id}}')"
                                                       title="Supplier Library" class="btn btn-warning">
                                                        <i class="fa fa-file-archive-o text-primary"> </i> {{__('Library')}}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                'id' => $one->id,
                                                'route' => 'admin:egyptian_federation.deleteSelected',
                                                 ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-center column-id" scope="col">#</th>
                                <th class="text-center column-branch-name" scope="col">{!! __('Company name') !!}</th>
                                <th class="text-center column-Membership-No" scope="col">{!! __('Membership No') !!}</th>
                                <th class="text-center column-company-type" scope="col">{!! __('Company Type') !!}</th>
                                <th class="text-center column-funds-for" scope="col">{!! __('Subscription payment receipt') !!}</th>
                                <th class="text-center column-register-date" scope="col">{!! __('Date of register in the union') !!}</th>
                                <th class="text-center column-funds-on" scope="col">{!! __('End date') !!}</th>
                                <th class="text-center column-created-at" scope="col">{!! __('created at') !!}</th>
                                <th class="text-center column-updated-at" scope="col">{!! __('Updated at') !!}</th>
                                <th scope="col">{{__('Options')}}</th>
                                <th scope="col">{{__('Select')}}</th>
                            </tr>
                            </tfoot>

                        </table>
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('accounting-module-modal-area')
@endsection

@section('modals')

    <div class="modal fade modal-bg-wg" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">
                    <i class="fa fa-file-archive-o"> </i>    
                    {{__('Egyptian Federation Library')}}
                    
                    </h4>
                </div>
                
                <div class="modal-body">

                    <div class="row">
                        <form action="{{route('admin:egyptian_federation.upload.upload_library')}}" method="post"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group col-md-4">
                                <label>{{__('Title_ar')}} {!! required() !!}</label>
                                <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-archive-o"></li></span>
                                <input type="text" name="title_ar" class="form-control" id="library_title_ar">
                            </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>{{__('Title_en')}}</label>
                                <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-file-archive-o"></li></span>
                                <input type="text" name="title_en" class="form-control" id="library_title_en">
                            </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label>{{__('files')}} {!! required() !!}</label>
                                <input type="file" name="files[]" class="form-control" id="files" multiple>
                                <input type="hidden" name="supplier_id" value="" id="library_supplier_id">
                            </div>

                            <div class="form-group col-md-1" id="upload_loader" style="display: none;">
                                <img src="{{asset('default-images/loading.gif')}}" title="loader"
                                     style="width: 34px;height: 39px;margin-top: 27px;">
                            </div>
                        </form>
                    </div>

                    <hr>

                    <div id="supplier_files_area" class="row" style="text-align: center">


                    </div>
                </div>

                <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="uploadSupplierFiles()">
                <li class="fa fa-save"></li> {{__('save')}}</button>

                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                    <li class="fa fa-close"></li>   {{__('Close')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script type="application/javascript">
        invoke_datatable($('#currencies'))
        $("#selectAll").click(function () {

            $(".to_checked").prop("checked", $(this).prop("checked"));
        });

        $(".to_checked").click(function () {
            if (!$(this).prop("checked")) {
                $("#selectAll").prop("checked", false);
            }
        });

        function getLibrarySupplierId(id) {

            $("#library_supplier_id").val(id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',
                url: '{{route('admin:egyptian_federation.upload_library')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                beforeSend: function () {
                    // $("#loader_get_test_video").show();
                },

                success: function (data) {

                    $("#supplier_files_area").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function getSupplierFiles(id) {

            $.ajax({

                type: 'post',
                url: '{{route('admin:egyptian_federation.upload_library')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                beforeSend: function () {
                    // $("#loader_get_test_video").show();
                },

                success: function (data) {

                    $("#supplier_files_area").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    alert(errors);
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
                        url: '{{route('admin:egyptian_federation.upload_library.file.delete')}}',
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

        function uploadSupplierFiles() {

            var form_data = new FormData();

            var egyptian_federation = $("#library_supplier_id").val();
            var title_ar = $("#library_title_ar").val();
            var title_en = $("#library_title_en").val();

            var totalfiles = document.getElementById('files').files.length;

            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }

            form_data.append("egyptian_federation", egyptian_federation);
            form_data.append("title_ar", title_ar);
            form_data.append("title_en", title_en);
            $.ajax({
                url: "{{route('admin:egyptian_federation.upload.upload_library')}}",
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

                    $("#supplier_files_area").prepend(data.view);

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

    </script>

    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>

@endsection
