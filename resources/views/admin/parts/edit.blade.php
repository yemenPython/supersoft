@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Parts') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">
                    <a href="{{route('admin:parts.index')}}"> {{__('Parts management')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Part')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Edit Part')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"
                                style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"
                                    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">

                    @include('admin.parts.form')

                </div>
                <!-- /.box-content -->
            </div>
            <!-- /.col-xs-12 -->
        </div>
    </div>

@endsection

@section('modals')

    @include('admin.parts.units.create')

@endsection

@section('js')

    <script src="{{asset('js/parts/index.js')}}"></script>

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function getSparTypesByBranch() {

            var branch_id = $("#branch_id").val();
            loadMainPartTypes(branch_id)
            loadSubPartTypes(branch_id, '')
            $.ajax({
                url: "{{ route('admin:part.type.by.branch')}}",
                method: 'GET',
                data: {branch_id: branch_id},
                success: function (data) {

                    $('.js-example-basic-single').select2();

                    $(".removeToNewData").remove();

                    // $.each(data.partTypes, function (key, modelName) {

                    //     if (!modelName.spare_part_id) {

                    //         var option = new Option(modelName, modelName);
                    //         option.text = modelName['type_' + data.lang];
                    //         option.value = modelName['id'];

                    //         $("#parts_types_options").append(option);

                    //         $('.data_by_branch option').addClass(function () {
                    //             return 'removeToNewData';
                    //         });
                    //     }
                    // });

                    // $.each(data.partTypes, function (key, modelName) {

                    //     if (modelName.spare_part_id) {

                    //         var option = new Option(modelName, modelName);
                    //         option.text = modelName['type_' + data.lang];
                    //         option.value = modelName['id'];

                    //         $("#sub_parts_types_options").append(option);

                    //         $('.data_by_branch option').addClass(function () {
                    //             return 'removeToNewData';
                    //         });
                    //     }
                    // });

                    $.each(data.supplier, function (key, modelName) {

                        var option = new Option(modelName, modelName);
                        option.text = modelName;
                        option.value = key;

                        $("#suppliers_options").append(option);

                        $('.data_by_branch option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });

                    $.each(data.parts, function (key, modelName) {

                        var option = new Option(modelName, modelName);
                        option.text = modelName;
                        option.value = key;

                        $("#parts_options").append(option);

                        $('.data_by_branch option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });

                    $.each(data.stores, function (key, modelName) {

                        var option = new Option(modelName, modelName);

                        option.text = modelName['name_' + data.lang];
                        option.value = modelName['id'];

                        $("#store_id").append(option);

                        $('.data_by_branch option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });

                    $.each(data.taxes, function (key, modelName) {

                        var option = new Option(modelName, modelName);

                        option.text = modelName['name_' + data.lang];
                        option.value = modelName['id'];

                        $("#tax_id").append(option);

                        $('.data_by_branch option').addClass(function () {
                            return 'removeToNewData';
                        });
                    });

                }
            });
        }

        function subPartsTypes() {

            let spare_parts_ids = $("#parts_types_options").val(), branch_id = $("#branch_id").val()

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let order = $('#parts_types_options').find(":selected").data('order');

            $.ajax({

                type: 'post',
                url: '{{route('admin:sub.parts.get')}}',
                data: {
                    _token: CSRF_TOKEN,
                    spare_parts_ids: spare_parts_ids,
                    order: order,
                    part_id: '{{$part->id}}'
                },

                success: function (data) {

                    $("#newSubPartsTypes").html(data.view);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        $(document).ready(function () {
            subPartsTypes()
        });

        function newPartPriceSegment() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let part_price_segments_count = $("#part_price_segments_count").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.new.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_price_segments_count: part_price_segments_count
                },

                success: function (data) {

                    $("#part_price_segments").append(data.view);
                    $("#part_price_segments_count").val(data.key)
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function deleteOldPartPriceSegment( key, partPriceSegmentId) {

            swal({

                title: "Delete Price Segment",
                text: "Are you sure want to delete this Segment ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({

                        type: 'post',

                        url: '{{route('admin:parts.delete.price.segments')}}',

                        data: {
                            _token: CSRF_TOKEN,
                            partPriceSegmentId: partPriceSegmentId
                        },
                        success: function (data) {

                            $("#price_segment_" + key).remove();
                            swal({text: data.message, icon: "success"})
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

        function removePartPriceSegment(key) {

            swal({

                title: "Delete Price Segment",
                text: "Are you sure want to delete this Segment ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {
                    $("#price_segment_" + key).remove();
                }
            });

        }

        function newSupplier() {
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let supplier_count = $("#supplier_count").val();
            let branchId = $("#branch_id").val();
            @if(authIsSuperAdmin())
            if (!is_numeric(branchId)) {
                swal({text: '{{__('please select the branch first')}}', icon: "warning"})
                return false;
            }
            @endif
            $.ajax({
                type: 'post',
                url: '{{route('admin:parts.new.supplier')}}',
                data: {
                    _token: CSRF_TOKEN,
                    supplier_count: supplier_count,
                    branchId: branchId,
                },
                success: function (data) {
                    $("#supplier_count").val(data.index);
                    $("#suppliers_ids" + data.index).select2()
                    $(".form_new_supplier").append(data.view);
                    $("#suppliers_ids" + data.index).select2()
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function deleteSupplier(index) {
            swal({
                title: "{{__('Delete')}}",
                text: "{{__('Are you sure want to Delete?')}}",
                type: "success",
                buttons: {
                    confirm: {
                        text: "{{__('Ok')}}",
                    },
                    cancel: {
                        text: "{{__('Cancel')}}",
                        visible: true,
                    }
                }
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $(".supplier-" + index).remove();
                }
            });
        }

        function getSupplierById(index) {
            let id = $('#suppliers_ids' + index).val();
            $.ajax({
                type: 'get',
                url: "{{ route('admin:parts.getBYId.supplier') }}?supplier_id=" + id,
                success: function (data) {
                    if (data.status) {
                        swal({text: "{{__('Please Select Valid Supplier')}}", icon: "error"})
                    }
                    $("#phone" + index).val(data.phone)
                    $("#address" + index).val(data.address)
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function updatePart () {

            var form_data = new FormData($("#part_form_data")[0]);

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.update.data')}}',

                data: form_data,

                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (data) {

                    // $('.js-example-basic-single').select2();

                    swal({text: data.message, icon: "success"});
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function storeUnit () {

            var form_data = new FormData($("#unit_form_data")[0]);

            $.ajax({

                type: 'post',
                url: '{{route('admin:part.units.store')}}',
                data: form_data,

                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (data) {

                    $('#part_prices_table').html(data.prices_index);

                    $('#units_count').val(data.unitsCount);

                    invoke_datatable($('#cities'));

                    $('.js-example-basic-single').select2();

                    swal({text: data.message, icon: "success"})

                    $('#part_new_unit').modal('hide');
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function defaultPrice (price) {

            $('#default_selling_price').val(price.selling_price);
            $('#default_purchase_price').val(price.purchase_price);
            $('#default_less_selling_price').val(price.less_selling_price);
            $('#default_service_selling_price').val(price.service_selling_price);
            $('#default_less_service_selling_price').val(price.less_service_selling_price);
        }

        function createUnit () {

            $('#save_action').attr('onClick','storeUnit()');

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let part_id = $('#unit_part_id').val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:part.units.create')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id:part_id
                },

                success: function (data) {

                    $('#part_ajax_unit_form').html(data.prices_form);

                    $('#default_Unit_title').text(data.defaultUnit);

                    if (data.price != null) {
                        defaultPrice(data.price);
                    }

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function editUnit (id) {

            $('#save_action').attr('onClick','updateUnit('+ id +')');

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',

                url: '{{route('admin:part.units.edit')}}',

                data: {
                    _token: CSRF_TOKEN,
                    id:id,
                },

                success: function (data) {

                    $('#part_ajax_unit_form').html(data.prices_form);

                    $('#default_Unit_title').text(data.defaultUnit);

                    $('#part_new_unit').modal('show');

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function updateUnit (id) {

            var form_data = new FormData($("#unit_form_data")[0]);

            form_data.append('id', id);

            $.ajax({

                type: 'post',

                url: '{{route('admin:part.units.update')}}',

                processData: false,
                contentType: false,

                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                data: form_data,

                success: function (data) {

                    $('#part_prices_table').html(data.prices_index);

                    $('#part_new_unit').modal('hide');

                    $('.js-example-basic-single').select2();

                    $('#default_Unit_title').text(data.defaultUnit);

                    if (data.defaultPrice != null) {
                        defaultPrice(data.defaultPrice);
                    }

                    swal({text: data.message, icon: "success"})
                },

                error: function (jqXhr, json, errorThrown) {
                    // $("#loader_save_goals").hide();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function deleteUnit (id, index) {

            swal({

                title: "Delete Unit",
                text: "Are you sure want to delete this Unit ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    let part_id =  $('#unit_part_id').val();;

                    $.ajax({

                        type: 'post',

                        url: '{{route('admin:part.units.destroy')}}',

                        data: {
                            _token: CSRF_TOKEN,
                            id:id,
                            part_id:part_id
                        },
                        success: function (data) {

                            $('#part_prices_table').html(data.prices_index);

                            swal({text: data.message, icon: "success"})

                            invoke_datatable($('#cities'))
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

        invoke_datatable($('#cities'))

    </script>



@endsection
