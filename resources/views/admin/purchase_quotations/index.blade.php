@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Purchase Quotations') }} </title>
@endsection

@section('style')
    <style>
        .wg-label {
            font-size: 10px;
            padding: 3px;
        }
    </style>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Purchase Quotations')}}</li>
            </ol>
        </nav>

        @include('admin.purchase_quotations.search_form')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-text-o"></i> {{__('Purchase Quotations')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:purchase-quotations.create',  'new' => '',])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:damaged-stock.create.deleteSelected',])
                            @endcomponent
                        </li>

                        <li class="list-inline-item">
                            <button style="
    margin-bottom: 12px; border-radius: 5px" type="button" onclick="relayToSupplyOrder()"
                                    class="btn btn-print-wg btn-icon btn-icon-left  waves-effect waves-light hvr-bounce-to-left"
                            >
                                <i class="ico fa fa-floppy-o"></i>
                                {{__('Relay to Supply Orders')}}
                            </button>
                        </li>

                    </ul>

                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Date') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('supplier') !!}</th>
                                <th scope="col">{!! __('Quotation Number') !!}</th>


                                <th scope="col">{!! __('Quotation type') !!}</th>

                                <th scope="col">{!! __('Total') !!}</th>


                                <th scope="col">{{__('quotation days')}}</th>
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
                                <th scope="col">{!! __('Supplier') !!}</th>
                                <th scope="col">{!! __('Quotation Number') !!}</th>



                                <th scope="col">{!! __('Quotation type') !!}</th>


                                <th scope="col">{!! __('Total') !!}</th>

                                <th scope="col">{{__('quotation days')}}</th>
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

    @include('admin.partial.execution_period_form', [
    'items'=> $data->get(), 'url'=> route('admin:purchase.quotations.execution.save'), 'title' => __('Purchase Quotation Execution') ])

    @include('admin.partial.upload_library.form', ['url'=> route('admin:purchase.quotations.upload_library')])

    @include('admin.partial.print_modal', ['title'=> __('Purchase Quotations')])

    @include('admin.purchase_quotations.terms.supply_terms', ['items' => $data->get()])

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\PurchaseQuotationExecution\CreateRequest', '.form'); !!}

    <script type="application/javascript">

        function printDownPayment() {
            var element_id = 'concession_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {

            $.ajax({
                url: "{{ route('admin:purchase.quotations.print') }}?purchase_quotation_id=" + id,
                method: 'GET',
                success: function (data) {
                    $("#data_to_print").html(data.view);
                    let total = $("#totalInLetters").text()
                    $("#totalInLetters").html(new Tafgeet(parseFloat(total), '{{config("currency.defualt_currency")}}').parse())
                }
            });
        }

        function getLibraryFiles(id) {

            $("#library_item_id").val(id);

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',
                url: '{{route('admin:purchase.quotations.library.get.files')}}',
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
                        url: '{{route('admin:purchase.quotations.library.file.delete')}}',
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
                url: "{{route('admin:purchase.quotations.upload_library')}}",
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

        function getItemValue(id) {

            $('#purchase_quotation_id').val(id);
        }

        server_side_datatable('#datatable-with-btns');

        function filterFunction($this) {
            $("#loaderSearch").show();
            $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
            $datatable.ajax.url($url).load();
            $(".js__card_minus").trigger("click");
            setTimeout( function () {
                $("#loaderSearch").hide();
            }, 1000)
        }

        function relayToSupplyOrder () {

            var checkbox_list = [];
            var branch_ids = [];
            var purchase_request_ids = [];
            var suppliers_ids = [];
            var ids_cant_to_relay = [];

            $(".checkbox-relay-quotation").each(function() {
                if ($(this).is(":checked")) {

                    if ($(this).data('can-to-relay') == 0) {
                        ids_cant_to_relay.push($(this).data('quotation-number'))
                    }

                    checkbox_list.push($(this).val());
                    branch_ids.push($(this).data('branch'));
                    purchase_request_ids.push($(this).data('purchase-request'));
                    suppliers_ids.push($(this).data('supplier-id'));
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
            let unique_purchase_request_id = [...new Set(purchase_request_ids)];
            let unique_suppliers_id = [...new Set(suppliers_ids)];

            if (unique_branch_id.length > 1) {

                swal("{{__('Error')}}", '{{__('sorry, branches is different')}}', "error");
                return false;
            }

            if (unique_purchase_request_id.length > 1) {

                swal("{{__('Error')}}", '{{__('sorry, Purchase Requests is different')}}', "error");
                return false;
            }

            if (unique_suppliers_id.length > 1) {

                swal("{{__('Error')}}", '{{__('sorry, Suppliers is different')}}', "error");
                return false;
            }

            let purchase_request_id = unique_purchase_request_id[0];
            let branch_id = unique_branch_id[0];


            setTimeout(function () {
                window.location.href = '{{url('/'). '/admin/supply-orders/create?'}}' +
                    'quotation=' + checkbox_list + '&branch_id=' + branch_id + '&p_request=' + purchase_request_id
            }, 1000);
        }

    </script>

@endsection
