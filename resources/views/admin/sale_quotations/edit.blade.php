@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Sale Quotation') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:sale-quotations.index')}}"> {{__('Sale Quotations')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Sale Quotation')}}</li>
            </ol>
        </nav>

        <div class="">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Edit Sale Quotation')}}
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
                    <form method="post" action="{{route('admin:sale-quotations.update', $saleQuotation->id)}}" class="form"
                          enctype="multipart/form-data" id="quotation_form">
                        @csrf

                        @include('admin.sale_quotations.form')

                        <div class="form-group col-sm-12">
                            <button  type="button" class="btn hvr-rectangle-in saveAdd-wg-btn" onclick="checkStockQuantity()">
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

{{--    <div id="modals_part_types">--}}

{{--        @foreach ($purchaseQuotation->items as $index => $item)--}}

{{--            @php--}}
{{--                $index +=1;--}}
{{--                $part = $item->part;--}}
{{--                $partTypes = partTypes($part);--}}
{{--            @endphp--}}

{{--            @include('admin.purchase_quotations.part_types')--}}

{{--        @endforeach--}}

{{--    </div>--}}

    @include('admin.partial.part_image')

@endsection

@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\SaleQuotation\UpdateRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script src="{{asset('js/sale_quotation/index.js')}}"></script>

    <script type="application/javascript">

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

                url: '{{route('admin:sale.quotations.select.part')}}',

                data: {
                    _token: CSRF_TOKEN,
                    part_id: part_id,
                    index: index,
                },

                success: function (data) {

                    $("#parts_data").append(data.parts);

                    $("#items_count").val(data.index);

                    $("#modals_part_types").append(data.partTypesView);

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

            let barcode = $('#prices_part_' + index).find(":selected").data('barcode');
            let supplier_barcode = $('#prices_part_' + index).find(":selected").data('supplier-barcode');

            $.ajax({

                type: 'post',

                url: '{{route('admin:sale.quotations.price.segments')}}',

                data: {
                    _token: CSRF_TOKEN,
                    price_id:price_id,
                    index:index
                },

                success: function (data) {

                    $("#price_segments_part_" + index).html(data.view);

                    $('.js-example-basic-single').select2();

                    $("#barcode_" + index).text(barcode);
                    $("#supplier_barcode_" + index).text(supplier_barcode);

                    defaultUnitQuantity (index);
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

        // function selectItemType(index, key) {
        //
        //     if ($('#item_type_checkbox_' + index + '_' + key).is(':checked')) {
        //
        //         $('#item_type_real_checkbox_' + index + '_' + key).prop('checked', true);
        //
        //     } else {
        //
        //         $('#item_type_real_checkbox_' + index + '_' + key).prop('checked', false);
        //     }
        // }

        $('.dropdown-toggle').dropdown();

        function ItemTypePrice (index, key) {

            var itemTypePrice = $('#item_type_price_' + index + '_' + key).val();
            $('#item_type_real_price_' + index + '_' + key).val(itemTypePrice);
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

        function getDate () {

            let start_date = $('#date_from').val();
            let end_date = $('#date_to').val();

            const date1 = new Date(start_date);
            const date2 = new Date(end_date);

            const now = new Date();
            let dateNow = now.getFullYear()+'-'+(now.getMonth()+1)+'-'+now.getDate();
            const date0 = new Date(dateNow);

            var diff = date2.getTime() - date1.getTime();

            var remainingTime = date2.getTime() - date0.getTime();

            var daydiff = diff / (1000 * 60 * 60 * 24);

            var remainingTimeDays = remainingTime / (1000 * 60 * 60 * 24);

            $('#different_days').val(daydiff.toFixed(0));

            $('#remaining_days').val(remainingTimeDays.toFixed(0));
        }

        function defaultUnitQuantity (index) {

            let unit_quantity = $('#prices_part_' + index).find(":selected").data('quantity');
            $('#unit_quantity_' + index).text(unit_quantity);
        }

        function getPartImage (index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

        function checkStockQuantity () {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $.ajax({

                type: 'post',

                url: '{{route('admin:sale.quotations.check.stock')}}',

                data: $('#quotation_form').serialize() + '&_token=' + CSRF_TOKEN,

                success: function (data) {
                    $("#quotation_form").submit();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

    </script>

@endsection
