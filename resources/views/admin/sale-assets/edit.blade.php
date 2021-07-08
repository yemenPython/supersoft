@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Purchase Invoice') }} </title>
@endsection

@section('style')

@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">
                    <a href="{{route('admin:sale-assets.index')}}"> {{__('Sale Assets')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Sale Assets')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{__('Edit Sale Asset')}}
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
                    <form method="post" action="{{route('admin:sale-assets.update', $saleAsset->id)}}"
                          class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        @include('admin.sale-assets.form')

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

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\SaleAssetRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')

    <script src="{{asset('js/purchase_invoice/index.js')}}"></script>

    <script type="application/javascript">

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:sale-assets.create')}}" + "?branch_id=" + branch_id;
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
                    totalPurchaseCost();
                    totalPastConsumtion();
                    total_replacement_cost();
                    total_total_current_consumtion();
                    total_final_total_consumtion();
                    totalSaleAmount();
                }
            });
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
            $.ajax({
                url: "{{ route('admin:assets_expenses.getAssetsByAssetGroup') }}?asset_group_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#assetsOptions').html(data.assets);
                }
            });
        });
        $('#assetsOptions').on('change', function () {
            var dateFrom = $('#date_from').val();

            if (dateFrom == '') {
                swal({text: '{{__('sorry, please select date from first')}}', icon: "error"});
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
                url: "{{ route('admin:consumption_assets.getAssetsByAssetId') }}?asset_id=" + $(this).val(),
                method: 'get',
                data: {
                    asset_id: $(this).val(),
                    branch_id: branch_id,
                    index: index,
                    date_from: date_from,
                    date_to: date_to,
                    _token: '{{csrf_token()}}',
                },
                success: function (data) {
                    $('#items_data').append(data.items);
                    $("#items_count").val(data.index);
                    totalPurchaseCost();
                    totalPastConsumtion();
                    total_replacement_cost();
                    total_total_current_consumtion();
                    total_final_total_consumtion();
                    totalSaleAmount();
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

        function total_replacement_cost(){
            let total = '';
            $(".replacement_cost").each(function () {
                var value = $($(this)).val();
                total = +total + +value;
            });

            $('#total_replacement').val(total);
        }
        function total_total_current_consumtion(){
            let total = '';
            $(".total_current_consumtion").each(function () {
                var value = $($(this)).val();
                total = +total + +value;
            });
            $('#total_current_consumtion').val(total);
        }
        function total_final_total_consumtion()
        {
            let total = '';
            $(".final_total_consumtion").each(function () {
                var value = $($(this)).val();
                total = +total + +value;
            });
            $('#final_total_consumtion').val(total);
        }
        function totalSaleAmount()
        {
            let total = '';
            $(".sale_amount").each(function () {
                var value = $($(this)).val();
                total = +total + +value;
            });
            $('#total_sale_amount').val(total);
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
    </script>


@endsection
