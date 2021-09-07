@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Settlement') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('admin:settlements.index')}}"> {{__('Settlements')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Settlement')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-cube"></i>
                    {{__('Edit Settlement')}}
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
                    <form method="post" action="{{route('admin:settlements.update', $settlement->id)}}" id="settlement_form"
                          class="form" enctype="multipart/form-data">
                        @csrf

                        @include('admin.settlements.form')

                        <div class="form-group col-sm-12">
                            <button id="btnsave" type="button" class="btn hvr-rectangle-in saveAdd-wg-btn" onclick="checkStockQuantity()">
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

    <!-- /.row small-spacing -->
@endsection

@section('modals')
    @include('admin.partial.part_image')
@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Settlements\CreateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function dataByBranch () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.branch')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id
                },

                success: function (data) {

                    $("#main_types").html(data.main_types);
                    $("#sub_types").html(data.sub_types);
                    $("#parts").html(data.parts);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function dataByMainType () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let main_type_id = $('#main_types_select').find(":selected").val();
            let order = $('#main_types_select').find(":selected").data('order');

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.main.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id,
                    main_type_id:main_type_id,
                    order:order,
                },

                success: function (data) {

                    $("#sub_types").html(data.sub_types);
                    $("#parts").html(data.parts);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function dataBySubType () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let sub_type_id = $('#sub_types_select').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.sub.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id,
                    sub_type_id:sub_type_id
                },

                success: function (data) {

                    $("#parts").html(data.parts);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function selectPart () {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let part_id = $('#parts_select').find(":selected").val();

            let index = $('#items_count').val();

            let branch_id = $('#branch_id').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:settlements.select.part')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id:part_id,
                    index:index,
                    branch_id:branch_id
                },

                success: function (data) {

                    $("#parts_data").append(data.parts);

                    $("#items_count").val(data.index);

                    $('.js-example-basic-single').select2();

                    executeAllItems();

                    reorderItems();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function priceSegments (index) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let price_id = $('#prices_part_' + index).val();

            let damage_price = $('#prices_part_' + index).find(":selected").data('damaged-price');

            let barcode = $('#prices_part_' + index).find(":selected").data('barcode');
            let supplier_barcode = $('#prices_part_' + index).find(":selected").data('supplier-barcode');

            $.ajax({

                type: 'post',

                url: '{{route('admin:settlements.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    price_id:price_id,
                    index:index
                },

                success: function (data) {

                    $("#price_segments_part_" + index).html(data.view);
                    $("#price_" + index).val(damage_price);

                    $("#barcode_" + index).text(barcode);
                    $("#supplier_barcode_" + index).text(supplier_barcode);

                    let unit_quantity = $('#prices_part_' + index).find(":selected").data('quantity');
                    $('#unit_quantity_' + index).val(unit_quantity);

                    calculateItem(index);
                    defaultUnitQuantity(index);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function removeItem (index) {

            swal({

                title: "Delete Item",
                text: "Are you sure want to delete this item ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $('.tr_part_' + index).remove();
                    calculateTotal();
                    reorderItems();
                }
            });

        }

        function calculateItem (index) {

            let quantity = $('#quantity_' + index).val();

            let price = $('#price_' + index).val();

            let total = quantity * price;

            $("#total_" + index).val(total.toFixed(2));

            calculateTotal();
        }

        function calculateTotal () {

            let items_count =  $("#items_count").val();

            let total_price = 0;
            let total_quantity = 0;

            for (let i = 0; i<=items_count; i++) {

                if ($("#total_" + i).val()) {

                    total_price = parseFloat($('#total_' + i).val()) + parseFloat(total_price);
                    total_quantity = parseFloat($('#quantity_' + i).val()) + parseFloat(total_quantity);
                }
            }

            $("#total_price").val(total_price.toFixed(2));
            $("#total_quantity").val(total_quantity.toFixed(2));
        }

        function getPurchasePrice(index) {

            let price_segment = $('#price_segments_part_' + index).find(":selected").val();

            let purchasePrice = $('#price_segments_part_' + index).find(":selected").data('purchase_price');

            if (price_segment.length == 0) {

                $("#price_" + index).val($('#prices_part_' + index).find(":selected").data('damaged-price'));
                return true;
            }

            $("#price_" + index).val(purchasePrice);
        }

        function partQuantityInStore (index) {

            let partQuantityInStore = $('#store_part_' + index).find(":selected").data('quantity');
            $("#max_quantity_part_" + index).val(partQuantityInStore);
        }

        function checkPartQuantity (index) {

            let settlementTypeType = '';

            if ($("#positive").is(':checked')) {

                settlementTypeType = 'positive';
            }else {

                settlementTypeType = 'negative';
            }

            if (settlementTypeType == 'positive') {

                return false;
            }

            let max_quantity = $("#max_quantity_part_" + index).val();

            let unit_quantity = $('#unit_quantity_' + index).val();

            let quantity = parseFloat($('#quantity_' + index).val()) * parseFloat(unit_quantity);

            if (quantity > max_quantity) {

                swal({

                    title: "{{__('Max Quantity')}}",
                    text: "{{__('Quantity is more than available')}}" ,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,

                }).then((willDelete) => {

                    $('#quantity_' + index).val(0);
                    calculateItem(index);
                });

            }
        }

        function reorderItems() {

            let items_count = $('#items_count').val();

            let index = 1;

            for (let i = 1; i <= items_count; i++) {

                if ($('#price_' + i).length) {
                    $('#item_number_' + i).text(index);

                }else {
                    continue;
                }

                index++;
            }
        }

        function checkBranchValidation() {

            let branch_id = $('#branch_id').find(":selected").val();

            let isSuperAdmin = '{{authIsSuperAdmin()}}';

            if (!isSuperAdmin) {
                return true;
            }

            if (branch_id) {
                return true;
            }

            return false;
        }

        function defaultUnitQuantity (index) {

            let unit_quantity = $('#prices_part_' + index).find(":selected").data('quantity');
            $('#unit_quantity_' + index).text(unit_quantity);
        }

        function getPartImage (index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

        function quantityValidation (index, message) {

            let quantity = $('#quantity_' + index).val();

            if (quantity <= 0) {

                swal({text: message, icon: "warning"});

                $('#quantity_' + index).val(1);

                calculateItem(index);
            }
        }

        function priceValidation (index, message) {

            let price = $('#price_' + index).val();

            if (price < 0) {

                swal({text: message, icon: "warning"});

                $('#price_' + index).val(0);

                calculateItem(index);
            }
        }

        function executeAllItems () {

            let items_count = $('#items_count').val();

            for (let i = 1; i <= items_count; i++) {

                if ($('#price_' + i).length) {
                    calculateItem(i);
                }
            }

            calculateTotal();
        }

        function newEmployee() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let index = $('#employees_count').val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:settlements.new.employee')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id: branch_id,
                    index: index,
                },

                success: function (data) {

                    $("#employees_data").append(data.view);
                    $("#employees_count").val(data.index);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function removeEmployee(index) {

            swal({

                title: "Remove Employee",
                text: "Are you sure want to remove this employee ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $('#employee_' + index).remove();
                }
            });
        }

        function removeOldEmployee(id, index) {

            swal({

                title: "Remove Employee",
                text: "Are you sure want to remove this employee ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({

                        type: 'post',

                        url: '{{route('admin:settlements.destroy.employee')}}',

                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
                            settlement_id: '{{$settlement->id}}',
                        },

                        success: function (data) {

                            $('#employee_' + index).remove();
                        },

                        error: function (jqXhr, json, errorThrown) {
                            var errors = jqXhr.responseJSON;
                            swal({text: errors, icon: "error"})
                        }
                    });
                }
            });
        }

        function checkStockQuantity () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',

                url: '{{route('admin:settlements.check.stock')}}',

                data: $('#settlement_form').serialize() + '&_token=' + CSRF_TOKEN,

                success: function (data) {
                    $("#settlement_form").submit();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

    </script>

@endsection
