@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Stores Transfers') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a href="{{route('admin:stores-transfers.index')}}"> {{__('Stores Transfers')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Stores Transfers')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}
            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Create Stores Transfers')}}
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
                    <form method="post" action="{{route('admin:stores-transfers.store')}}" class="form" id="store_transfer_form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.stores_transfer.form')

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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\StoresTransfersRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script type="application/javascript">

        function changeBranch () {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:stores-transfers.create')}}" + "?branch_id=" + branch_id ;
        }

        function dataByMainType () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let main_type_id = $('#main_types_select').find(":selected").val();
            let store_id = $('#store_from_select').find(":selected").val();
            let order = $('#main_types_select').find(":selected").data('order');

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.main.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id,
                    main_type_id:main_type_id,
                    store_id:store_id,
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
            let store_id = $('#store_from_select').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.sub.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id,
                    sub_type_id:sub_type_id,
                    store_id:store_id
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

            let store_id = $('#store_from_select').find(":selected").val();

            let index = $('#items_count').val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:stores.transfers.select.part')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id:part_id,
                    store_id:store_id,
                    index:index,
                },

                success: function (data) {

                    $("#parts_data").append(data.parts);

                    $("#items_count").val(data.index);
                    $("#max_quantity_part_" + data.index).val(data.max_quantity);

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

            let part_price_id = $('#prices_part_' + index).find(":selected").val();
            let barcode = $('#prices_part_' + index).find(":selected").data('barcode');
            let supplier_barcode = $('#prices_part_' + index).find(":selected").data('supplier-barcode');

            $.ajax({

                type: 'post',

                url: '{{route('admin:stores.transfers.get.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_price_id:part_price_id,
                    index:index
                },

                success: function (data) {

                    $("#price_segments_part_" + index).html(data.segments);

                    $("#barcode_" + index).text(barcode);
                    $("#supplier_barcode_" + index).text(supplier_barcode);

                    let unit_quantity = $('#prices_part_' + index).find(":selected").data('quantity');
                    $('#unit_quantity_' + index).val(unit_quantity);

                    let purchase_price = $('#prices_part_' + index).find(":selected").data('purchase-price');
                    $('#price_' + index).val(purchase_price);

                    calculateItem(index);

                    defaultUnitQuantity(index)

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

        function load_my_parts () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let store_id = $('#store_from_select').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:get-store-parts')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id:branch_id,
                    store_id:store_id
                },

                success: function (data) {

                    $("#parts").html(data.parts);

                    $("#parts_data").empty();

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getPurchasePrice (id, index) {

            let purchasePrice = $('#' + id + index).find(":selected").data('purchase-price');

            let price_segment = $('#' + id + index).find(":selected").val();

            if (price_segment.length == 0) {

                $("#price_" + index).val($('#prices_part_' + index).find(":selected").data('purchase-price'));
                return true;
            }

            $("#price_" + index).val(purchasePrice);
        }

        function checkPartQuantity (index) {

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

        function executeAllItems () {

            let items_count = $('#items_count').val();

            for (let i = 1; i <= items_count; i++) {

                if ($('#price_' + i).length) {
                    calculateItem(i);
                }
            }

            calculateTotal();
        }

        function checkStockQuantity () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',

                url: '{{route('admin:stores.transfers.check.stock')}}',

                data: $('#store_transfer_form').serialize() + '&_token=' + CSRF_TOKEN,

                success: function (data) {
                    $("#store_transfer_form").submit();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

    </script>

@endsection
