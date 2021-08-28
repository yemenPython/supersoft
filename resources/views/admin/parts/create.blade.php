@extends('admin.layouts.app')


@section('title')
    <title>{{ __('Create Part') }} </title>
@endsection

@section('style')

@endsection


@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">
                    <a href="{{route('admin:parts.index')}}"> {{__('Parts management')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Part')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Create Part')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"
                                style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"
                                    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">

{{--                    <form method="post" action="{{route('admin:parts.store')}}" class="form" enctype="multipart/form-data">--}}
{{--                        @csrf--}}
{{--                        @method('post')--}}

                        @include('admin.parts.form')

{{--                        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">--}}
{{--                            @include('admin.parts.units.index')--}}
{{--                        </div>--}}



{{--                    </form>--}}
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>

    <!-- /.row small-spacing -->
@endsection

@section('modals')

    @include('admin.parts.units.create')

@endsection

@section('js-validation')

    @include('admin.partial.sweet_alert_messages')

    <script src="{{asset('js/parts/index.js')}}"></script>

    <script type="application/javascript">

        function changeBranch() {

            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:parts.create')}}" + "?branch_id=" + branch_id;
        }

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

        {{--function newUnit() {--}}

        {{--    var defaultUnit = $("#part_units_default option:selected").val();--}}

        {{--    if (defaultUnit == '') {--}}

        {{--        swal("{{__('sorry please select default unit')}}", {icon: "error",});--}}
        {{--        return false;--}}
        {{--    }--}}

        {{--    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');--}}

        {{--    let units_count = $("#units_count").val();--}}

        {{--    var defaultUnitValue = $("#part_units_default option:selected").val();--}}

        {{--    var selectedUnitIds = [defaultUnitValue];--}}

        {{--    for (var i = 1; i <= units_count; i++) {--}}

        {{--        if (i != 1) {--}}

        {{--            let unitVal = $("#unit_" + i + " option:selected").val();--}}

        {{--            if (unitVal) {--}}
        {{--                selectedUnitIds.push($("#unit_" + i + " option:selected").val());--}}
        {{--            }--}}
        {{--        }--}}
        {{--    }--}}

        {{--    $.ajax({--}}

        {{--        type: 'post',--}}

        {{--        url: '{{route('admin:part.units.new')}}',--}}

        {{--        data: {--}}
        {{--            _token: CSRF_TOKEN,--}}
        {{--            units_count: units_count,--}}
        {{--            selectedUnitIds: selectedUnitIds--}}
        {{--        },--}}

        {{--        success: function (data) {--}}

        {{--            $("#units_count").val(data.index);--}}
        {{--            $(".form_new_unit").append(data.view);--}}
        {{--            $('.js-example-basic-single').select2();--}}

        {{--            let selectedUnit = $("#part_units_default" + " option:selected").text();--}}
        {{--            $(".default_unit_title").text(selectedUnit);--}}

        {{--        },--}}

        {{--        error: function (jqXhr, json, errorThrown) {--}}
        {{--            // $("#loader_save_goals").hide();--}}
        {{--            var errors = jqXhr.responseJSON;--}}
        {{--            swal({text: errors, icon: "error"})--}}
        {{--        }--}}
        {{--    });--}}
        {{--}--}}

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
                    order: order
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

        function storePart () {

            var form_data = new FormData($("#part_form_data")[0]);

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.store')}}',

                data: form_data,

                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                success: function (data) {

                    // $('.js-example-basic-single').select2();

                    swal({text: data.message, icon: "success"});

                    $('#unit_part_id').val(data.part_id);
                    $('#units_count').val(data.units_count);

                    $('.close_form').attr('disabled', true);
                    $('.unit_tab_li').attr('href','#profile-justified');
                    $('.unit_tab_li').attr('tab','tab');
                    $('.unit_tab_li').attr('data-toggle','tab');

                    $('.unit_tab_li').click();
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
