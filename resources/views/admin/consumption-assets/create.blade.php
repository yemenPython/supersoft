@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create Consumption Asset') }} </title>
@endsection

@section('style')

@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">
                    <a href="{{route('admin:consumption-assets.index')}}"> {{__('Consumption Asset')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Consumption Asset')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Create Consumption Asset')}}
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
                    <form method="post" action="{{route('admin:consumption-assets.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.consumption-assets.form')

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


@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\ConsumptionAssetRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script src="{{asset('js/purchase_invoice/index.js')}}"></script>

    <script type="application/javascript">

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:consumption-assets.create')}}" + "?branch_id=" + branch_id;
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
                    calculateItem(index);
                    reorderItems();
                    totalPurchaseCost(index);
                    totalPastConsumtion(index);
                    netTotal(index);
                    totalReplacements()
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


        $('#assetsGroups').on('change', function () {

            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
            let isSuperAdmin = '{{authIsSuperAdmin()}}';
            if (isSuperAdmin) {
                var branch_id = $('#branch_id').find(":selected").val();
            }else {
                var branch_id = $('#branch_id_hidden').val();
            }
            $.ajax({
                url: "{{ route('admin:assets_expenses.getAssetsByAssetGroup') }}?asset_group_id=" + $(this).val()+"&branch_id="+branch_id,
                method: 'GET',
                success: function (data) {
                    $('#assetsOptions').html(data.assets);
                }
            });
        });
        $('#assetsOptions').on('change', function () {
            var dateFrom = $('#date_from').val();
            var dateTo = $('#date_to').val();
            let type = $("input[name='type']:checked").val();
            if (dateFrom == '') {
                swal({text: '{{__('sorry, please select date from first')}}', icon: "error"});
                $('#assetsOptions').val('');
                return false;
            }
            if (dateTo == '') {
                swal({text: '{{__('sorry, please select date to first')}}', icon: "error"});
                $('#assetsOptions').val('');
                return false;
            } if (type == '' || type== undefined) {
                swal({text: '{{__('sorry, please select type first')}}', icon: "error"});
                $('#assetsOptions').val('');
                return false;
            }
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
            if (checkIfAssetExists($(this).val())) {
                swal({text: '{{__('sorry, you have already add this asset before')}}', icon: "warning"});
                return false;
            }
            let branch_id = $('#branch_id').find(":selected").val();
            let index = $('#items_count').val();
            let date_from = $('#date_from').val();
            let date_to = $('#date_to').val();

            $.ajax({
                url: "{{ route('admin:consumption_assets.get_Assets_By_Asset_Id') }}?asset_id=" + $(this).val(),
                method: 'get',
                data: {
                    asset_id: $(this).val(),
                    branch_id: branch_id,
                    index: index,
                    date_from: date_from,
                    date_to: date_to,
                    type: type,
                    _token: '{{csrf_token()}}',
                },
                success: function (data) {
                    $('#items_data').append(data.items);
                    $("#items_count").val(data.index);
                    $("#expenses_total_"+ data.index).val(data.expenses_total.toFixed(2));
                    $("#expenses_total_hidden_"+ data.index).val(data.expenses_total.toFixed(2));
                    totalPurchaseCost(index);
                    totalPastConsumtion(index);
                    netTotal(index);
                    consumptionAmount(data.index,data.diff);
                    totalReplacements();
                    checkType(data.index);
                    $('.js-example-basic-single').select2();
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        });

        function checkIfAssetExists(index) {
            var ids = [];
            $('.assetExist').each(function () {
                ids.push($(this).val())
            })
            return ids.includes(index);
        }

        function totalPurchaseCost() {
            let total = '';
            $(".purchase_cost").each(function () {
                var value = $($(this)).val();
                total = +total + +value;
            });
            $('#total_purchase_cost').val(total);
        }

        function totalPastConsumtion() {
            let total = '';
            $(".past_consumtion").each(function () {
                var value = $($(this)).val();
                total = +total + +value;
            });
            $('#total_past_consumtion').val(total);
        }
        function totalReplacements() {
            let total = '';
            var value = $("input[name='type']:checked").val()
            if (value =='asset' || value =='both') {
                $(".total_replacement").each(function () {
                    var value = $($(this)).val();
                    total = +total + +value;
                });
            }
            if (value =='expenses' || value =='both') {
                $(".total_replacement_expenses").each(function () {
                    var value = $($(this)).val();
                    total = +total + +value;
                });
            }
            $('#total_replacement').val(total);
        }

        function netTotal() {
            let total = '';
            $(".current_consumtion").each(function () {
                var value = $($(this)).val();
                total = +total + +value;
            });
            $('#net_total').val(total);
        }

        function annual_consumtion_rate_value(index) {
            var annual_consumtion_rate = $('.annual_consumtion_rate_' + index).val();

            var purchase_cost = $('.purchase_cost_' + index).val();

            if (annual_consumtion_rate != '' && purchase_cost != '') {

                var asset_age = (purchase_cost / annual_consumtion_rate) / 100;
                $('.asset_age_' + index).val(asset_age.toFixed(2));
            }
        }

        $('#note').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
        });
        $('#date').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
        });
        $('#time').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
        });
        $('#date_to').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            if (date_from == '') {
                swal({text: '{{__('sorry, please select date from first')}}', icon: "error"});
                return false;
            }
            const date1 = new Date(date_from);
            const date2 = new Date(date_to);
            const date_of_wok = new Date(date_to);
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            if (diffDays < 30) {
                swal({text: '{{__('sorry, please select period equal or more than month')}}', icon: "error"});
                var date_to = $('#date_to').val('');
                return false;
            }
           let index = $("#items_count").val();
            consumptionAmount(index);
        });
        $('#date_from').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
            let index = $("#items_count").val();
            consumptionAmount(index);
        });
        function consumptionAmount(index,diff=0) {
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            var total_net_purchase_cost = $('.net_purchase_cost_' + index).val();
            var total_replacements = $('.total_replacements_' + index).val();
            var annual_consumtion_rate = $('.annual_consumtion_rate_' + index).val();
            var net_purchase_cost = +total_net_purchase_cost + +total_replacements;
            if (date_from != '' && date_to != '' && net_purchase_cost != '' && annual_consumtion_rate != '') {
                const date1 = new Date(date_from);
                const date2 = new Date(date_to);
                const diffTime = Math.abs(date2 - date1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                var age = (+net_purchase_cost / +annual_consumtion_rate) / 100;
                var months = age * 12;
                var asd = +net_purchase_cost / +months;
                var any = diffDays - diff;
                var value = +asd * (+any / 30);
                $('.consumption_amount_' + index).val(value.toFixed(2));
            }
        }
        function checkType(index){
            var value = $("input[name='type']:checked").val();

            if(value =='asset'){
                $('.type_expenses').hide();
                $('.type_asset').show();
                // $('#expenses_total_' + index).val(0);
            }else if (value =='expenses'){
                $('.type_asset').hide();
                $('.type_expenses').show();
                // $('.consumption_amount_' + index).val(0);
            }else {
                $('.type_asset').show();
                $('.type_expenses').show();
            }
            totalReplacements();
        }
        $(function (){
            $("input[type='radio'][name='type']").click(function() {

                checkType( $("#items_count").val());
            });
        })
    </script>


@endsection
