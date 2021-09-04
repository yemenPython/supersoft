@extends('admin.layouts.app')

@section('title')
    <title> {{ __('Create Concessions') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('admin:concessions.index')}}"> {{__('Concessions')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Concessions')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

            <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Create Concessions')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">
                    <form method="post" action="{{route('admin:concessions.store')}}" id="concession_form"
                          class="form" enctype="multipart/form-data" >
                        @csrf
                        @method('post')

                        @include('admin.concessions.form')

                        <div class="form-group col-sm-12">

                            <button id="btnsave" type="button" class="btn hvr-rectangle-in saveAdd-wg-btn" onclick="store()">
                                <i class="ico ico-left fa fa-save"></i>
                                {{__('Save')}}
                            </button>

                            <button id="reset"  type="button" class="btn hvr-rectangle-in resetAdd-wg-btn">
                                <i class="ico ico-left fa fa-trash"></i>
                                {{__('Reset')}}
                            </button>

                            <button id="back" type="button" class="btn hvr-rectangle-in closeAdd-wg-btn">
                                <i class="ico ico-left fa fa-close"></i>
                                {{__('Back')}}
                            </button>
                        </div>

                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>
@endsection

@section('modals')

    <div class="modal fade" id="part_store_quantity" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content wg-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="myModalLabel-1">
                    <i class="fa fa-cubes"></i>
                    {{__('Part Quantity')}}</h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 margin-bottom-20" id="part_quantity">

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        {{__('Close')}}
                    </button>

                </div>
            </div>
        </div>
    </div>

    @include('admin.partial.part_image')
@endsection

@section('js-validation')

{{--    {!! JsValidator::formRequest('App\Http\Requests\Admin\Concession\CreateRequest', '.form'); !!}--}}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:concessions.create')}}" + "?branch_id=" + branch_id;
        }

        function showSelectedTypes (type) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();

            $('.remove_items').remove();

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.get.types')}}',

                data: {
                    _token: CSRF_TOKEN,
                    type:type,
                    branch_id:branch_id
                },

                success: function (data) {

                    $("#concession_types").html(data.view);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getDataByBranch () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();;

            $('.remove_items').remove();

            let type = 'add';

            if ($('#add').is(':checked')) {

                type = 'add';

            }else {

                type = 'withdrawal';
            }

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.data.by.branch')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id,
                    type:type
                },

                success: function (data) {

                    $("#concession_types").html(data.view);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getConcessionItems () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let concession_type_id = $('#concession_type_id').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.get.items')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id,
                    concession_type_id:concession_type_id
                },

                success: function (data) {

                    $("#model").val(data.model);

                    $("#concession_items").html(data.view);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getConcessionPartsData () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let concession_item_id = $("#concession_item_id").val();
            let model = $("#model").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.parts.data')}}',

                data: {
                    _token: CSRF_TOKEN,
                    concession_item_id:concession_item_id,
                    model:model
                },

                success: function (data) {

                    $("#parts_data").html(data.view);

                    $("#item_quantity").val(data.total_quantity);
                    $("#total_price").val(data.total_price);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getPartImage (index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

        function showPartQuantity (part_id) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.part.stores.quantity')}}',

                data: {
                    _token: CSRF_TOKEN, part_id:part_id
                },

                success: function (data) {

                    $("#part_quantity").html(data.view);
                    invoke_datatable_quotations($('#sale_supply_table'));
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function invoke_datatable_quotations(selector, load_at_end_selector, last_child_allowed) {
            var selector_id = selector.attr("id")
            var page_title = $("title").text()
            $("#" + selector_id).DataTable({
                "language": {
                    "url": "{{app()->isLocale('ar')  ? "//cdn.datatables.net/plug-ins/1.10.20/i18n/Arabic.json" :
                                             "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"}}",
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'colvis',
                        text: '<i class="fa fa-list"></i> {{__('Columns visibility')}}',
                    },
                ],
            });
        }

        function store () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',

                url: '{{route('admin:concessions.store')}}',

                data: $('#concession_form').serialize() + '&_token=' + CSRF_TOKEN,

                success: function (data) {

                    swal({text: data.message, icon: "success"});

                    setTimeout(function(){
                        window.location.href = data.link;
                    }, 2000);
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

    </script>

@endsection
