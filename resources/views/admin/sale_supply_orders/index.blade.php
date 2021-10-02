@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Sale Supply Orders') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Sale Supply Orders')}}</li>
            </ol>
        </nav>

        @include('admin.sale_supply_orders.search_form')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-text-o"></i> {{__('Sale Supply Orders')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:sale-supply-orders.create',  'new' => '',])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:sale.supply.orders.deleteSelected',])
                            @endcomponent
                        </li>

                        <li class="list-inline-item">
                            <button style=" margin-bottom: 12px; border-radius: 5px" type="button" onclick="relayToSalesInvoice()"
                                    class="btn btn-print-wg btn-icon btn-icon-left  waves-effect waves-light hvr-bounce-to-left"
                            >
                                <i class="ico fa fa-floppy-o"></i>
                                {{__('Relay to sales invoice')}}
                            </button>
                        </li>

                    </ul>

                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Date') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Number') !!}</th>
                                <th scope="col">{!! __('Client Type') !!}</th>
                                <th scope="col">{!! __('Client') !!}</th>

                                <th scope="col">{!! __('Total') !!}</th>

                                <th scope="col">{!! __('sale supply order days') !!}</th>
                                <th scope="col">{!! __('Remaining Days') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Execution Status') !!}</th>
                                <th scope="col">{!! __('Created Date') !!}</th>
                                <th scope="col">{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>

                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}
                                </th>

                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Date') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Number') !!}</th>
                                <th scope="col">{!! __('Client Type') !!}</th>
                                <th scope="col">{!! __('Client') !!}</th>


                                <th scope="col">{!! __('Total') !!}</th>

                                <th scope="col">{!! __('sale supply order days') !!}</th>
                                <th scope="col">{!! __('Remaining Days') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Execution Status') !!}</th>
                                <th scope="col">{!! __('Created Date') !!}</th>
                                <th scope="col">{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    @include('admin.partial.print_modal', ['title'=> __('Sale Supply Orders')])

    @include('admin.sale_supply_orders.terms.supply_terms', ['items' => $sale_supply_orders->get()])

    @include('admin.partial.upload_library.form', ['url'=> route('admin:sale.supply.upload_library')])

@endsection

@section('js-validation')

    <script type="application/javascript">

        function printDownPayment() {
            var element_id = 'concession_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {

            $.ajax({
                url: "{{ route('admin:sale.supply.orders.print') }}?sale_supply_order_id=" + id,
                method: 'GET',
                success: function (data) {
                    $("#data_to_print").html(data.view);

                    let total = $("#totalInLetters").text()
                    $("#totalInLetters").html(new Tafgeet(parseFloat(total), '{{config("currency.defualt_currency")}}').parse())
                }
            });
        }

        function getItemValue(id) {

            $('#purchase_quotation_id').val(id);
        }

        server_side_datatable('#datatable-with-btns');

        function filterFunction($this) {
            $("#loaderSearch").show();
            $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
            $datatable.ajax.url($url).load();
            $(".js__card_minus").trigger("click");
            setTimeout(function () {
                $("#loaderSearch").hide();
            }, 1000)
        }

        function getLibraryFiles(id) {

            $("#library_item_id").val(id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',
                url: '{{route('admin:sale.supply.library.get.files')}}',
                data: {
                    _token: CSRF_TOKEN,
                    id: id,
                },

                success: function (data) {

                    $("#files_area").html(data.view);
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
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
                        url: '{{route('admin:sale.supply.library.file.delete')}}',
                        data: {
                            _token: CSRF_TOKEN,
                            id: id,
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

        function uploadFiles() {

            var form_data = new FormData();

            var item_id = $("#library_item_id").val();
            var title_ar = $("#library_title_ar").val();
            var title_en = $("#library_title_en").val();

            var totalfiles = document.getElementById('files').files.length;

            for (var index = 0; index < totalfiles; index++) {
                form_data.append("files[]", document.getElementById('files').files[index]);
            }

            form_data.append("item_id", item_id);
            form_data.append("title_ar", title_ar);
            form_data.append("title_en", title_en);

            $.ajax({
                url: "{{route('admin:sale.supply.upload_library')}}",
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

                    $("#files_area").prepend(data.view);

                    $("#files").val('');
                    $("#library_title_ar").val('');
                    $("#library_title_en").val('');

                    $("#no_files").remove();

                },
                error: function (jqXhr, json, errorThrown) {

                    $('#upload_loader').hide();
                    var errors = jqXhr.responseJSON;
                    swal("{{__('Sorry')}}", errors, "error");
                },
            });
        }

        function changeType () {

            if ($('#customer_radio').is(':checked')) {

                $('#supplier_select').hide();
                $('#customer_select').show();

                $('supplier_id').val('').change();

            }else {

                $('#supplier_select').show();
                $('#customer_select').hide();

                $('customer_id').val('').change();
            }
        }

        function changeSupplyType () {

            let supplyType = $('#supply_type').find(':selected').val();

            if (supplyType == 'from_sale_quotation') {

                $('#quotations_number').show();

            }else {

                $('#quotations_number').hide();
            }
        }

    </script>

    <script type="application/javascript">

        function relayToSalesInvoice () {

            var checkbox_list = [];
            var branch_ids = [];
            var types = [];
            var salesableIds = [];
            var ids_cant_to_relay = [];

            $(".checkbox-relay-quotation").each(function() {

                if ($(this).is(":checked")) {

                    if ($(this).data('can-relay-to-sales-invoice') == 0) {
                        ids_cant_to_relay.push($(this).data('order-number'))
                    }

                    checkbox_list.push($(this).val());
                    branch_ids.push($(this).data('branch'));
                    types.push($(this).data('type-for'));
                    salesableIds.push($(this).data('salesable-id'));
                }
            });

            if (checkbox_list.length == 0) {

                swal("{{__('Error')}}", '{{__('sorry, please select items')}}', "error");
                return false;
            }

            if (ids_cant_to_relay.length != 0) {

                swal("{{__('Error')}}", '{{__('sorry, this item not valid : ')}}' + ids_cant_to_relay.toString(), "error");
                return false;
            }

            let unique_branch_id = [...new Set(branch_ids)];
            let unique_client_type_id = [...new Set(types)];
            let unique_clients_ids = [...new Set(salesableIds)];


            if (unique_branch_id.length > 1) {

                swal("{{__('Error')}}", '{{__('sorry, branches is different')}}', "error");
                return false;
            }

            if (unique_clients_ids.length > 1) {

                swal("{{__('Error')}}", '{{__('sorry, Clients is different')}}', "error");
                return false;
            }

            if (unique_client_type_id.length > 1) {

                swal("{{__('Error')}}", '{{__('sorry, Clients type is different')}}', "error");
                return false;
            }

            let branch_id = unique_branch_id[0];
            let type = unique_client_type_id[0];

            setTimeout(function () {
                window.location.href = '{{url('/'). '/admin/sales-invoices/create?'}}' +
                    'orders=' + checkbox_list + '&branch_id=' + branch_id + '&type=' + type + '&invoice_type=from_sale_supply_order'
            }, 1000);
        }

    </script>

@endsection
