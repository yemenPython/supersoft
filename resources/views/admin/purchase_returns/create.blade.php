@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Purchase Invoice Return') }} </title>
@endsection

@section('style')

@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">
                    <a href="{{route('admin:purchase_returns.index')}}"> {{__('Purchase Invoices Return')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Purchase Invoice Return')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Create Purchase Invoice Return')}}
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
                    <form method="post" action="{{route('admin:purchase_returns.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.purchase_returns.form')

                        <div class="form-group col-sm-12">
                            <button type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn">
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

    <div class="modal fade" id="purchase_receipts" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content wg-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="myModalLabel-1">{{__('Purchase Receipts')}}</h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 margin-bottom-20">
                            <table id="purchase_quotations_table" class="table table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{!! __('Check') !!}</th>
                                    <th scope="col">{!! __('Purchase Receipt Number') !!}</th>
                                    <th scope="col">{!! __('Supplier name') !!}</th>
                                </tr>
                                </thead>

                                <form id="purchase_quotation_form" method="post">
                                    @csrf

                                    <tbody id="purchase_receipts_data">

                                    </tbody>

                                </form>
                            </table>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="add_p_receipts"
                            onclick="addSelectedPurchaseReceipts(); selectSupplier('from_supply_order')">
                        {{__('Add Item')}}
                    </button>


                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="part_quantity" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content wg-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="myModalLabel-1">{{__('Part Quantity')}}</h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 margin-bottom-20">
                            <table id="purchase_quotations_table" class="table table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{!! __('Store') !!}</th>
                                    <th scope="col">{!! __('Quantity') !!}</th>
                                </tr>
                                </thead>

                                <tbody id="store_quantity">

                                </tbody>

                            </table>
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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\PurchaseReturn\PurchaseReturnRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script src="{{asset('js/purchase_invoice_return/index.js')}}"></script>

    <script type="application/javascript">

        @if(request()->query('invoice'))

        var purchase_invoice_id = '{{request()->query('invoice')}}';

        $("#invoice_type").val('normal').change();

        $("#purchase_invoice_id").val(purchase_invoice_id).change();

        $("#purchase_invoice_id").find(':selected').attr('disabled', false);

        @endif

        ///////////////////////////////////////////////////////////////////////////////

        @if(request()->query('p_receipts') && request()->query('s_order'))

        selectSupplyOrders()

        $("#supply_order_ids").find(':selected').attr('disabled', false);

        getPurchaseReceipts()

        // addSelectedPurchaseReceipts()

        // $("#purchase_quotations").modal('hide');
        //

        @endif


        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:purchase_returns.create')}}" + "?branch_id=" + branch_id;
        }

        function selectPurchaseInvoice() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            let purchase_invoice_id = $('#purchase_invoice_id').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.returns.select.purchase.invoice')}}',

                data: {
                    _token: CSRF_TOKEN,
                    purchase_invoice_id: purchase_invoice_id,
                },

                success: function (data) {

                    $("#parts_data").append(data.view);

                    $("#items_count").val(data.index);

                    $('.js-example-basic-single').select2();

                    reorderItems();

                    executeAllItems();
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

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.invoices.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    price_id: price_id,
                    index: index
                },

                success: function (data) {

                    $("#price_segments_part_" + index).html(data.view);
                    $('.js-example-basic-single').select2();
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

        function getPurchaseReceipts() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            $("#purchase_quotations_table").dataTable().fnDestroy()

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let supply_order_ids = $('#supply_order_ids').val();

            let data = {
                _token: CSRF_TOKEN,
                supply_order_ids: supply_order_ids
            }

            $.ajax({

                type: 'post',
                url: '{{route('admin:purchase.returns.purchase-receipts')}}',
                data: data,

                success: function (data) {

                    $("#purchase_receipts_data").html(data.view);

                    $("#purchase_receipts_selected").html(data.real_purchase_receipts);

                    @if(!request()->query('p_receipts') && !request()->query('s_order'))
                    $("#purchase_receipts").modal('show');
                    @endif

                    $('.js-example-basic-single').select2();

                    invoke_datatable_quotations($('#purchase_quotations_table'));

                    executeAllItems()

                    @if(request()->query('p_receipts') && request()->query('s_order'))
                    selectPurchaseReceipts()

                    $('#add_p_receipts').click()

                    // addSelectedPurchaseReceipts()
                    @endif
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function addSelectedPurchaseReceipts() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            var selected = [];

            $('#purchase_receipts_data input:checked').each(function () {
                selected.push($(this).attr('value'));
            });

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.returns.add.purchase.receipts')}}',

                data: {_token: CSRF_TOKEN, purchase_receipts: selected},

                success: function (data) {

                    $("#parts_data").html(data.view);

                    $("#items_count").val(data.index);

                    $('.js-example-basic-single').select2();

                    executeAllItems();
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

        function storeQuantity(part_id) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.returns.show.part.quantity')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id: part_id,
                },

                success: function (data) {

                    $("#store_quantity").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getPartImage(index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

        function selectSupplyOrders() {

            @if(request()->query('p_receipts') && request()->query('s_order'))

            let supplyOrderIds = '{{request()->query('s_order')}}';

            let supplyOrderIds_arr = supplyOrderIds.split(',');

            supplyOrderIds_arr.forEach(function (supply_id) {
                $('#supply_order_ids option[value=' + supply_id + ']').attr('selected', true);
            });

            @endif
        }

        function selectPurchaseReceipts() {

            @if(request()->query('p_receipts') && request()->query('s_order'))

            let receipts_ids = '{{request()->query('p_receipts')}}';

            let receipts_ids_ids_arr = receipts_ids.split(',');

            receipts_ids_ids_arr.forEach(function (receipt_id) {

                $(".real_purchase_quotation_box_" + receipt_id).prop('checked', true);
                $(".real_purchase_quotation_box_" + receipt_id).prop('disabled', false);

                $(".purchase_quotation_box_" + receipt_id).prop('checked', true);
                $(".purchase_quotation_box_" + receipt_id).prop('disabled', false);
            });
            @endif
        }

    </script>

@endsection
