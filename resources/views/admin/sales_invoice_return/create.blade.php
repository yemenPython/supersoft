@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Sales Invoice Return') }} </title>
@endsection

@section('style')

@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">
                    <a href="{{route('admin:sales.invoices.return.index')}}"> {{__('Sales Invoices Return')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Sales Invoice Return')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Create Sales Invoice Return')}}
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
                    <form method="post" action="{{route('admin:sales.invoices.return.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.sales_invoice_return.form')

                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\SalesInvoicesReturn\CreateSalesInvoiceReturnRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script src="{{asset('js/sales_invoice_return/index.js')}}"></script>

    <script type="application/javascript">

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:sales.invoices.return.create')}}" + "?branch_id=" + branch_id;
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

                    $('#tr_part_' + index).remove();
                    $('#part_types_' + index).remove();

                    // let items_count = $('#items_count').val();
                    //
                    // $('#items_count').val();

                    calculateItem(index);
                    reorderItems();
                }
            });
        }

        function getTypeItems() {


            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let type = $('#invoice_type').find(":selected").val();;

            let data = {
                _token: CSRF_TOKEN,
                type: type
            }

            $.ajax({

                type: 'post',
                url: '{{route('admin:sales.invoices.return.type.items')}}',
                data: data,

                success: function (data) {

                    $("#invoiceable_id").html(data.view);

                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function selectInvoiceOrReceipt() {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let type = $('#invoice_type').find(":selected").val();
            let item_id = $('#invoiceable_id').find(":selected").val();

            let data = {
                _token: CSRF_TOKEN,
                type: type,
                item_id: item_id
            }

            $.ajax({

                type: 'post',
                url: '{{route('admin:sales.invoices.return.select.invoice.or.Receipt')}}',
                data: data,

                success: function (data) {

                    $("#parts_data").html(data.view);

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

        function getPartImage (index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

    </script>

@endsection
