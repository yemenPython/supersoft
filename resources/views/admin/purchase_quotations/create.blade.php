@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Purchase Quotation') }} </title>
@endsection

{{--@section('style')--}}
{{--    --}}
{{--@endsection--}}

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:purchase-quotations.index')}}"> {{__('Purchase Quotation')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Purchase Quotation')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Create Purchase Quotation')}}
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

                    <div class="nex-table-collapse">
                        <div class="">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Employee
                                </div>
                                <div class="panel-body">
                                    <table class="table table-condensed table-striped">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Fist Name</th>
                                            <th>Last Name</th>
                                            <th>City</th>
                                            <th>State</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr data-toggle="collapse" data-target="#demo1" class="accordion-toggle">
                                            <td>
                                                <button class="btn btn-default btn-xs">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                </button>
                                            </td>
                                            <td>Carlos</td>
                                            <td>Mathias</td>
                                            <td>Leme</td>
                                            <td>SP</td>
                                            <td>new</td>
                                        </tr>

                                        <tr>
                                            <td colspan="12" class="hiddenRow">
                                                <div class="accordian-body collapse" id="demo1">
                                                    <table class="table table-striped">
                                                        <thead>
                                                        <tr class="info">
                                                            <th>Job</th>
                                                            <th>Company</th>
                                                            <th>Salary</th>
                                                            <th>Date On</th>
                                                            <th>Date off</th>
                                                            <th>Action</th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>

                                                        <tr data-toggle="collapse" class="accordion-toggle"
                                                            data-target="#demo10">
                                                            <td><a href="#">Enginner Software</a></td>
                                                            <td>Google</td>
                                                            <td>U$8.00000</td>
                                                            <td> 2016/09/27</td>
                                                            <td> 2017/09/27</td>
                                                            <td>
                                                                <a href="#" class="btn btn-default btn-sm">
                                                                    <i class="glyphicon glyphicon-cog"></i>
                                                                </a>
                                                            </td>
                                                        </tr>

                                                        </tbody>
                                                    </table>

                                                </div>
                                            </td>
                                        </tr>


                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>

                    <form method="post" action="{{route('admin:purchase-quotations.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.purchase_quotations.form')

                        @include('admin.buttons._save_buttons')

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

    <div id="modals_part_types">

    </div>

    @include('admin.partial.part_image')

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\PurchaseQuotation\CreateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script src="{{asset('js/purchase_quotation/index.js')}}"></script>

    <script type="application/javascript">

        @if(request()->query('quotation'))

            var purchase_request_id = '{{request()->query('quotation')}}';

            $("#purchase_request_id").val(purchase_request_id).change();

            // $("#purchase_request_id").find(':selected').attr('disabled', false);

            $('#disabled_purchase_request').val($("#purchase_request_id").find(':selected').text());

        @endif


        function changeBranch() {

            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:purchase-quotations.create')}}" + "?branch_id=" + branch_id;
        }

        function dataByMainType() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let main_type_id = $('#main_types_select').find(":selected").val();
            let order = $('#main_types_select').find(":selected").data('order');

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.main.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id: branch_id,
                    main_type_id: main_type_id,
                    order: order,
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

        function dataBySubType() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $('#branch_id').find(":selected").val();
            let sub_type_id = $('#sub_types_select').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:parts.common.filter.data.by.sub.type')}}',

                data: {
                    _token: CSRF_TOKEN,
                    branch_id: branch_id,
                    sub_type_id: sub_type_id
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

        function selectPart() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let part_id = $('#parts_select').find(":selected").val();

            let index = $('#items_count').val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.quotations.select.part')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id: part_id,
                    index: index,
                },

                success: function (data) {

                    $("#parts_data").append(data.parts);

                    $("#modals_part_types").append(data.partTypesView);

                    $("#items_count").val(data.index);

                    executeAllItems();

                    $('.js-example-basic-single').select2();

                    reorderItems();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function priceSegments(index) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let price_id = $('#prices_part_' + index).val();

            let barcode = $('#prices_part_' + index).find(":selected").data('barcode');
            let supplier_barcode = $('#prices_part_' + index).find(":selected").data('supplier-barcode');

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.quotations.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    price_id: price_id,
                    index: index
                },

                success: function (data) {

                    $("#price_segments_part_" + index).html(data.view);
                    $('.js-example-basic-single').select2();

                    $("#barcode_" + index).text(barcode);
                    $("#supplier_barcode_" + index).text(supplier_barcode);

                    defaultUnitQuantity(index);
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function removeItem(index) {

            swal({

                title: "Delete Item",
                text: "Are you sure want to delete this item ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {

                if (willDelete) {

                    $('.tr_part_' + index).remove();
                    $('#part_types_' + index).remove();
                    calculateItem(index);
                    reorderItems();
                }
            });
        }

        function selectItemType(index, key) {

            if ($('#item_type_checkbox_' + index + '_' + key).is(':checked')) {

                $('#item_type_real_checkbox_' + index + '_' + key).prop('checked', true);

            } else {

                $('#item_type_real_checkbox_' + index + '_' + key).prop('checked', false);
            }
        }

        function ItemTypePrice(index, key) {

            var itemTypePrice = $('#item_type_price_' + index + '_' + key).val();
            $('#item_type_real_price_' + index + '_' + key).val(itemTypePrice);
        }

        function selectPurchaseRequest() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let purchase_request_id = $('#purchase_request_id').find(":selected").val();

            $.ajax({

                type: 'post',
                url: '{{route('admin:purchase.quotations.select.request')}}',
                data: {
                    _token: CSRF_TOKEN,
                    purchase_request_id: purchase_request_id
                },

                success: function (data) {

                    $("#parts_data").html(data.view);
                    $("#items_count").val(data.index);
                    $(".remove_for_new_purchase_request").remove();

                    executeAllItems();

                    for (var i in data.partTypesViews) {

                        $("#modals_part_types").append(data.partTypesViews[i]);
                    }

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function reorderItems() {

            let items_count = $('#items_count').val();

            let index = 1;

            for (let i = 1; i <= items_count; i++) {

                if ($('#price_' + i).length) {
                    $('#item_number_' + i).text(index);

                } else {
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

        $('.dropdown-toggle').dropdown();

        function getDate() {

            let start_date = $('#date_from').val();
            let end_date = $('#date_to').val();

            const date1 = new Date(start_date);
            const date2 = new Date(end_date);

            const now = new Date();
            let dateNow = now.getFullYear() + '-' + (now.getMonth() + 1) + '-' + now.getDate();
            const date0 = new Date(dateNow);

            var diff = date2.getTime() - date1.getTime();

            var remainingTime = date2.getTime() - date0.getTime();

            var daydiff = diff / (1000 * 60 * 60 * 24);

            var remainingTimeDays = remainingTime / (1000 * 60 * 60 * 24);

            $('#different_days').val(daydiff.toFixed(0));

            $('#remaining_days').val(remainingTimeDays.toFixed(0));
        }

        function defaultUnitQuantity(index) {

            let unit_quantity = $('#prices_part_' + index).find(":selected").data('quantity');
            $('#unit_quantity_' + index).text(unit_quantity);
        }

        function getPartImage(index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

    </script>

@endsection
