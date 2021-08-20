@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Returned Sales Receipt') }} </title>
@endsection

@section('style')

@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:return-sale-receipts.index')}}"> {{__('Returned Sales Receipts')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Returned Sales Receipt')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Create Returned Sales Receipt')}}
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
                    <form method="post" action="{{route('admin:return-sale-receipts.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.returned_sale_receipt.form')

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

    <div class="modal fade" id="purchase_quotations" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                    <h4 class="modal-title" id="myModalLabel-1">{{__('Purchase Quotations')}}</h4>
                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-12 margin-bottom-20">
                            <table id="purchase_quotations_table" class="table table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col">{!! __('Check') !!}</th>
                                    <th scope="col">{!! __('Purchase quotations') !!}</th>
                                    <th scope="col">{!! __('Supplier') !!}</th>
                                </tr>
                                </thead>

                                <form id="purchase_quotation_form" method="post">
                                    @csrf

                                    <tbody id="purchase_quotation_data">

                                    </tbody>

                                </form>
                            </table>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                            data-dismiss="modal">
                        {{__('Close')}}
                    </button>

                    <button type="button" class="btn btn-primary btn-sm waves-effect waves-light"
                            onclick="addSelectedPurchaseQuotations()">
                        {{__('Add Item')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="modals_part_types">

    </div>

    @include('admin.partial.part_image')

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\ReturnSaleReceipt\CreateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script type="application/javascript">

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:return-sale-receipts.create')}}" + "?branch_id=" + branch_id;
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

        function validateItemQuantity (index) {

            let total_quantity = parseInt($('#remaining_quantity_' + index).val());
            let accepted_quantity = parseInt($('#accepted_quantity_' + index).val());
            let refused_quantity = parseInt($('#refused_quantity_' + index).val());

            if (accepted_quantity > total_quantity || refused_quantity > total_quantity) {

                $('#accepted_quantity_' + index).val(total_quantity);
                $('#refused_quantity_' + index).val(0);
                $('#defect_percent_' + index).text(' 0 %');

                return false;
            }

            return true;
        }

        function calculateRefusedQuantity (index) {

            let total_quantity = $('#remaining_quantity_' + index).val();
            let refused_quantity = $('#refused_quantity_' + index).val();

            if (!refused_quantity) {
                refused_quantity = 0;
            }

            if (!validateItemQuantity (index)) {

                swal({text: '{{__('sorry, Refused quantity is more than total')}}', icon: "error"})
                calculateTotal ();
                return false
            }

            let remainQty = parseInt(total_quantity) - parseInt(refused_quantity);

            $('#accepted_quantity_' + index).val(remainQty);

            let item_total_qty = $('#total_quantity_' + index).val();

            let defect_percent = parseFloat(refused_quantity) * parseInt(100) / parseInt(item_total_qty);

            $('#defect_percent_' + index).text( ' % ' +defect_percent.toFixed(2));

            calculateTotal ();
        }

        function calculateAcceptedQuantity (index) {

            let total_quantity = $('#remaining_quantity_' + index).val();
            let accepted_quantity = $('#accepted_quantity_' + index).val();

            if (!accepted_quantity) {
                accepted_quantity = 0;
            }

            if (!validateItemQuantity (index)) {

                swal({text: '{{__('sorry, Accepted quantity is more than total')}}', icon: "error"})
                calculateTotal ();
                return false
            }

            let remainQty = parseInt(total_quantity) - parseInt(accepted_quantity);

            $('#refused_quantity_' + index).val(remainQty);

            let item_total_qty = $('#total_quantity_' + index).val();

            let defect_percent = parseFloat(remainQty) * parseInt(100) / parseInt(item_total_qty);

            $('#defect_percent_' + index).text( ' % ' +defect_percent.toFixed(2));

            calculateTotal ();
        }

        function calculateTotal () {

            let items_count = $('#items_count').val();
            let total = 0;
            let total_accepted = 0;
            let total_rejected = 0;

            for (let i = 1; i <= items_count; i++) {

                let price = $('#price_' + i).val();
                let accepted_quantity = $('#accepted_quantity_' + i).val();
                let rejected_quantity = $('#refused_quantity_' + i).val();
                let remaining_quantity = $('#remaining_quantity_' + i).val();

                total += parseFloat(price) * parseFloat(remaining_quantity);
                total_accepted += parseFloat(price) * parseFloat(accepted_quantity);
                total_rejected += parseFloat(price) * parseFloat(rejected_quantity);
            }

            $('#total').val(total.toFixed(2));
            $('#total_accepted').val(total_accepted.toFixed(2));
            $('#total_rejected').val(total_rejected.toFixed(2));
        }

        function getPartImage (index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

        function selectReturnedType () {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let type = $('#type').find(":selected").val();
            let branch_id = $('#branch_id').val();

            $.ajax({

                type: 'post',
                url: '{{route('admin:return.sale.receipts.select.type')}}',
                data: {
                    _token: CSRF_TOKEN,
                    type: type,
                    branch_id:branch_id
                },

                success: function (data) {

                    $("#returned_type_items").html(data.parts);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getTypeItems () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let type = $('#type').find(":selected").val();
            let item_id = $('#item_id').find(":selected").val();
            let branch_id = $('#branch_id').val();

            $.ajax({

                type: 'post',
                url: '{{route('admin:return.sale.receipts.get.type.items')}}',
                data: {
                    _token: CSRF_TOKEN,
                    type: type,
                    branch_id:branch_id,
                    item_id:item_id
                },

                success: function (data) {

                    $("#parts_data").html(data.view);
                    $("#items_count").val(data.index);

                    $('.js-example-basic-single').select2();

                    calculateTotal ()
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });

        }

    </script>

@endsection
