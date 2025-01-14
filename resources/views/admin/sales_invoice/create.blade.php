@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Sales Invoice') }} </title>
@endsection

@section('style')

@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">
                    <a href="{{route('admin:sales.invoices.index')}}"> {{__('Sales Invoices')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Sales Invoice')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Create Sales Invoice')}}
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
                    <form method="post" action="{{route('admin:sales.invoices.store')}}" class="form"
                          id="sales_invoice_form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.sales_invoice.form')

                        <div class="form-group col-sm-12">
                            <button id="btnsave" type="button" class="btn hvr-rectangle-in saveAdd-wg-btn"
                                    onclick="checkStockQuantity()">
                                <i class="ico ico-left fa fa-save"></i>
                                {{__('Save')}}
                            </button>

                            <button id="reset" type="button" class="btn hvr-rectangle-in resetAdd-wg-btn">
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

    <div class="modal fade" id="purchase_quotations" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content wg-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="myModalLabel-1">{{__('Sale Quotations')}}</h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 margin-bottom-20">
                            <table id="sale_quotations_table" class="table table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{!! __('Check') !!}</th>
                                    <th scope="col">{!! __('Sale Quotation num.') !!}</th>
                                    <th scope="col">{!! __('Customer name') !!}</th>
                                </tr>
                                </thead>

                                <form id="sale_quotation_form" method="post">
                                    @csrf

                                    <tbody id="sale_quotation_data">

                                    {{--                                    @foreach( $data['saleQuotations'] as $saleQuotation)--}}

                                    {{--                                        <tr>--}}
                                    {{--                                            <td>--}}
                                    {{--                                                <input type="checkbox" name="sale_quotations[]"--}}
                                    {{--                                                       value="{{$saleQuotation->id}}"--}}
                                    {{--                                                       onclick="selectSaleQuotation('{{$saleQuotation->id}}')"--}}
                                    {{--                                                       class="sale_quotation_box_{{$saleQuotation->id}}"--}}
                                    {{--                                                >--}}
                                    {{--                                            </td>--}}
                                    {{--                                            <td>--}}
                                    {{--                                                <span>{{$saleQuotation->number}}</span>--}}
                                    {{--                                            </td>--}}
                                    {{--                                            <td>--}}
                                    {{--                                                <span>{{optional($saleQuotation->salesable)->name}}</span>--}}
                                    {{--                                            </td>--}}
                                    {{--                                        </tr>--}}

                                    {{--                                    @endforeach--}}

                                    </tbody>

                                </form>
                            </table>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">


                    @if(!request()->query('quotations') && !request()->query('type') && !request()->query('invoice_type') )
                        <button type="button" class="btn btn-primary btn-sm waves-effect waves-light"
                                onclick="addSelectedSaleQuotations()">
                            {{__('Add Item')}}
                        </button>
                    @endif

                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        {{__('Close')}}
                    </button>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="sale_supply_order" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content wg-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="myModalLabel-1">{{__('Sale Supply Order')}}</h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 margin-bottom-20">
                            <table id="sale_supply_table" class="table table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{!! __('Check') !!}</th>
                                    <th scope="col">{!! __('Sale Supply Order num.') !!}</th>
                                    <th scope="col">{!! __('Client name') !!}</th>
                                </tr>
                                </thead>

                                <form id="sale_supply_order_form" method="post">
                                    @csrf

                                    <tbody id="sale_supply_data">

                                    </tbody>

                                </form>
                            </table>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    @if(!request()->query('orders') && !request()->query('type') && !request()->query('invoice_type') )
                        <button type="button" class="btn btn-primary btn-sm waves-effect waves-light"
                                onclick="addSelectedSaleSupply()">
                            {{__('Add Item')}}
                        </button>
                    @endif

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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\salesInvoice\CreateSalesInvoiceRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script src="{{asset('js/sales_invoice/index.js')}}"></script>

    <script type="application/javascript">

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:sales.invoices.create')}}" + "?branch_id=" + branch_id;
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
                    order: order
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

        function addSelectedSaleQuotations() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            var selected = [];

            $('#sale_quotation_data input:checked').each(function () {
                selected.push($(this).attr('value'));
            });

            $('#sale_quotation_ids').empty();

            for (var i = 0; i < selected.length; i++) {
                $('#sale_quotation_ids').append(' <input type="hidden" name="sale_quotation_ids[]" value="' + selected[i] + '">');
            }

            let type_for = 'customer';

            if ($('#supplier_radio').is(':checked')) {

                type_for = 'supplier';
            } else {
                type_for = 'customer';
            }

            $.ajax({

                type: 'post',

                url: '{{route('admin:sales.invoices.add.sale.quotations')}}',

                data: {_token: CSRF_TOKEN, sale_quotations: selected, type_for: type_for},

                success: function (data) {

                    $("#parts_data").html(data.view);

                    $("#items_count").val(data.index);

                    $('.js-example-basic-single').select2();

                    if (data.type_for == 'customer') {
                        $("#customer_id").val(data.client_id).change();
                    } else {
                        $("#supplier_id").val(data.client_id).change();
                    }

                    @if(request()->query('quotations') && request()->query('type') && request()->query('invoice_type'))
                    getSelectedClientName()
                    @endif

                    executeAllItems();
                },

                error: function (jqXhr, json, errorThrown) {
                    $(".remove_on_change_branch").remove();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function addSelectedSaleSupply() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            var selected = [];

            $('#sale_supply_data input:checked').each(function () {
                selected.push($(this).attr('value'));
            });

            $('#sale_supply_orders_ids').empty();

            for (var i = 0; i < selected.length; i++) {
                $('#sale_supply_orders_ids').append(' <input type="hidden" name="sale_supply_orders[]" value="' + selected[i] + '">');
            }

            let type_for = 'customer';

            if ($('#supplier_radio').is(':checked')) {

                type_for = 'supplier';
            } else {
                type_for = 'customer';
            }

            $.ajax({

                type: 'post',

                url: '{{route('admin:sales.invoices.add.sale.supply.order')}}',

                data: {_token: CSRF_TOKEN, sale_supply_orders: selected, type_for: type_for},

                success: function (data) {

                    $("#parts_data").html(data.view);

                    $("#items_count").val(data.index);

                    $('.js-example-basic-single').select2();

                    if (data.type_for == 'customer') {
                        $("#customer_id").val(data.client_id).change();
                    } else {
                        $("#supplier_id").val(data.client_id).change();
                    }

                    @if(request()->query('orders') && request()->query('type') && request()->query('invoice_type'))
                    getSelectedClientName()
                    @endif

                    executeAllItems();
                },

                error: function (jqXhr, json, errorThrown) {
                    $(".remove_on_change_branch").remove();
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

                url: '{{route('admin:sales.invoices.select.part')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id: part_id,
                    index: index,
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

        function priceSegments(index) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let price_id = $('#prices_part_' + index).val();

            let barcode = $('#prices_part_' + index).find(":selected").data('barcode');
            let supplier_barcode = $('#prices_part_' + index).find(":selected").data('supplier-barcode');

            $.ajax({

                type: 'post',

                url: '{{route('admin:sales.invoices.price.segments')}}',

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

                    // let items_count = $('#items_count').val();
                    //
                    // $('#items_count').val();

                    calculateItem(index);
                    reorderItems();
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

        function defaultUnitQuantity(index) {

            let unit_quantity = $('#prices_part_' + index).find(":selected").data('quantity');
            $('#unit_quantity_' + index).text(unit_quantity);
        }

        function getPartImage(index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

        function getSaleQuotations() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $("#branch_id").val();

            let salesable_id = null;

            let type_for = 'customer';

            if ($('#supplier_radio').is(':checked')) {

                type_for = 'supplier';
                salesable_id = $("#supplier_id").val();

            } else {
                type_for = 'customer';
                salesable_id = $("#customer_id").val();
            }

            $.ajax({

                type: 'post',

                url: '{{route('admin:sales.invoices.get.sale.quotations')}}',

                data: {_token: CSRF_TOKEN, branch_id: branch_id, salesable_id: salesable_id, type_for: type_for},

                success: function (data) {

                    @if(!request()->query('quotations') && !request()->query('type') && !request()->query('invoice_type') )
                    $('#purchase_quotations').modal('show');
                    @endif

                    $("#sale_quotation_data").html(data.view);

                    $('.js-example-basic-single').select2();

                    @if(request()->query('quotations') && request()->query('type') && request()->query('invoice_type'))
                    selectQuotations();
                    addSelectedSaleQuotations();
                    @endif

                    invoke_datatable_quotations('sale_quotations_table');
                },

                error: function (jqXhr, json, errorThrown) {
                    $(".remove_on_change_branch").remove();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function getSaleSupply() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let branch_id = $("#branch_id").val();

            let salesable_id = null;

            let type_for = 'customer';

            if ($('#supplier_radio').is(':checked')) {

                type_for = 'supplier';
                salesable_id = $("#supplier_id").val();

            } else {
                type_for = 'customer';
                salesable_id = $("#customer_id").val();
            }

            $.ajax({

                type: 'post',

                url: '{{route('admin:sales.invoices.get.sale.supply.order')}}',

                data: {_token: CSRF_TOKEN, branch_id: branch_id, salesable_id: salesable_id, type_for: type_for},

                success: function (data) {

                    @if(!request()->query('orders') && !request()->query('type') && !request()->query('invoice_type') )
                    $('#sale_supply_order').modal('show');
                    @endif

                    $("#sale_supply_data").html(data.view);

                    @if(request()->query('orders') && request()->query('type') && request()->query('invoice_type'))
                    selectOrders()
                    addSelectedSaleSupply()
                    @endif

                    $('.js-example-basic-single').select2();

                    invoke_datatable_quotations('sale_supply_table');
                },

                error: function (jqXhr, json, errorThrown) {
                    $(".remove_on_change_branch").remove();
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

        function checkStockQuantity() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',

                url: '{{route('admin:sales.invoices.check.stock')}}',

                data: $('#sales_invoice_form').serialize() + '&_token=' + CSRF_TOKEN,

                success: function (data) {
                    $("#sales_invoice_form").submit();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }


    </script>

    <script type="application/javascript">
        invoke_datatable($('#sale_quotations_table'))
        invoke_datatable($('#sale_supply_table'))
    </script>


    {{-- RELAY SALE QUOTATION TO SALES INVOICE --}}
    <script>

        function getSelectedClientName() {

            @if(request()->query('type') == 'supplier')
            $('#disabled_supplier_name').val($('#supplier_id').find(':selected').text().replace(/\s/g, ''));

            @else
            $('#disabled_customer_name').val($('#customer_id').find(':selected').text().replace(/\s/g, ''));
            @endif
        }

        @if(request()->query('quotations') && request()->query('type') && request()->query('invoice_type') )
        selectRelayQuotations()
        @endif

        function selectRelayQuotations() {

            let invoice_type = '{{request()->query('invoice_type')}}'

            $('#invoice_type').val(invoice_type).change();

            $('#disabled_invoice_type').val($('#invoice_type').find(':selected').text().replace(/\s/g, ''))

            $('#disabled_type_for').val('{{request()->query('type')}}');


            @if(request()->query('type') == 'supplier')

            $('#supplier_radio').prop('checked', true);
            $('#customer_radio').prop('checked', false);

            @else

            $('#supplier_radio').prop('checked', false);
            $('#customer_radio').prop('checked', true);

            @endif

            changeTypeFor()

            getSaleQuotations();
        }

        function selectQuotations() {

            $(".quotations_boxes").prop('checked', false)
            $(".quotations_boxes").prop('disabled', true)

            let quotation_ids = '{{request()->query('quotations')}}';

            let quotation_ids_arr = quotation_ids.split(',');

            quotation_ids_arr.forEach(function (quotation_id) {

                $(".sale_quotation_box_" + quotation_id).prop('checked', true);
            });

            // $("#purchase_quotations").modal('hide');
        }

    </script>

    {{-- RELAY SUPPLY ORDER TO SALES INVOICE --}}
    <script>

        @if(request()->query('orders') && request()->query('type') && request()->query('invoice_type') )
        selectRelayOrders()
        @endif

        function selectRelayOrders() {

            let invoice_type = '{{request()->query('invoice_type')}}'

            $('#invoice_type').val(invoice_type).change();

            $('#disabled_invoice_type').val($('#invoice_type').find(':selected').text().replace(/\s/g, ''))

            $('#disabled_type_for').val('{{request()->query('type')}}');


            @if(request()->query('type') == 'supplier')

            $('#supplier_radio').prop('checked', true);
            $('#customer_radio').prop('checked', false);

            @else

            $('#supplier_radio').prop('checked', false);
            $('#customer_radio').prop('checked', true);

            @endif

            changeTypeFor()

            getSaleSupply();
        }

        function selectOrders() {

            $(".orders_boxes").prop('checked', false)
            $(".orders_boxes").prop('disabled', true)

            let orders_ids = '{{request()->query('orders')}}';

            let orders_ids_arr = orders_ids.split(',');

            orders_ids_arr.forEach(function (order_id) {
                $(".sale_quotation_box_" + order_id).prop('checked', true);
            });

            // $("#purchase_quotations").modal('hide');
        }

    </script>

@endsection

