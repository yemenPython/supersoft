@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Edit Assets Expenses') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:assets_expenses.index')}}"> {{__('Assets Expenses')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Assets Expenses')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>  {{__('Edit Assets Expenses')}}
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

                    <form  method="post" action="{{route('admin:assets_expenses.update', ['assetExpense' => $assetExpense->id])}}" class="form">
                        @csrf
                        @method('put')
                        @include('admin.assets_expenses.form')
                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\AssetExpenseRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        function checkBranchValidation() {
            let branch_id = $('#branch_id').find(":selected").val();
            let isSuperAdmin = '{{authIsSuperAdmin()}}';
            if (isSuperAdmin && !branch_id) {
                return true;
            }
            if (!isSuperAdmin && !branch_id) {
                return false;
            }
        }
        function changeBranch () {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:assets_expenses.create')}}" + "?branch_id=" + branch_id ;
        }

        $('#assetsGroups').on('change', function () {
            if (checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
            let branch_id = $('#branch_id').find(":selected").val();
            $.ajax({
                url: "{{ route('admin:assets_expenses.getAssetsByAssetGroup') }}?asset_group_id=" + $(this).val(),
                method: 'GET',
                data: {
                    branch_id : branch_id,
                },
                success: function (data) {
                    $('#assetsOptions').html(data.assets);
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        });

        $('#assetsOptions').on('change', function () {
            if (checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }

            if (checkIfAssetExists( $(this).val())) {
                swal({text: '{{__('sorry, you have already add this asset before')}}', icon: "warning"});
                return false;
            }
            let branch_id = $('#branch_id').find(":selected").val();
            let indexItem = indexTable("#items_count", "increment");
            $.ajax({
                url: "{{ route('admin:assets_expenses.getItemsByAssetId') }}?asset_id=" + $(this).val(),
                method: 'get',
                data : {
                    asset_id: $(this).val(),
                    branch_id: branch_id,
                    _token: '{{csrf_token()}}',
                    index: indexItem,
                },
                success: function (data) {
                    $('#items_data').append(data.items);
                    $('.js-example-basic-single').select2();
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        });

        function removeItem(index) {
            swal({
                title: "Delete Item",
                text: "Are you sure want to delete this item ?",
                icon: "warning",
                buttons: true,
                dangerMode: true,

            }).then((willDelete) => {
                if (willDelete) {
                    indexTable("#items_count", "decrement");
                    $('#item_' + index).remove();
                    addPriceToTotal()
                }
            });
        }

        function addPriceToTotal() {
            var total = 0;
            $(".priceItem").each(function(index, item){
                var v =  parseInt($(this).val());
                if (isNaN( parseInt($(this).val()))) {
                    v  = 0
                }
                total = (parseInt(total) + v).toFixed();
            })
            $('#total_price').val(total);
            $('#total_price_hidden').val(total);
        }

        function checkIfAssetExists(index) {
            var ids = [];
            $('.assetExist').each(function () {
                ids.push($(this).val())
            })
            return ids.includes(index);
        }

        function indexTable(id, type) {
            var currentIndex = $(id).val();
            if (type === 'increment') {
                var incrementIndex = parseInt(currentIndex) + 1;
            }
            if (type === 'decrement') {
                var incrementIndex = parseInt(currentIndex) - 1;
            }
            $(id).val(incrementIndex)
            return incrementIndex;
        }

        function getAssetItemsByAssetTypeId(asset_expense_type_index)
        {
            let assetExpenseTypeId = $('#asset_expense_type_index'+asset_expense_type_index).val();
            let branch_id = $('#branch_id').find(":selected").val();
            $.ajax({
                url: "{{ route('admin:assets_expenses_items.getAssetItemsExpenseById') }}",
                method: 'get',
                data : {
                    assets_type_expenses_id: assetExpenseTypeId,
                    branch_id: branch_id,
                    _token: '{{csrf_token()}}',
                },
                success: function (data) {
                    $('#assetExpensesItemsSelect'+asset_expense_type_index).html(data.items);
                    $('.js-example-basic-single').select2();
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }
        function annual_consumtion_rate_value(index) {
            var annual_consumtion_rate = $('.annual_consumtion_rate_'+index).val();

            var price = $('.price_'+index).val();
            if (annual_consumtion_rate !='' && price !=''){

                var asset_age = ( price / annual_consumtion_rate) / 100;
                $('.asset_age_'+index).val( asset_age.toFixed(2));
            }
        }
        function checkType(index,value) {
            var rVal = $('.group_consumption_type'+index).val();
            if (rVal =='manual' && value =='automatic'){
                swal({text: '{{__('sorry,Consumption Type of asset group is manual')}}', icon: "warning"});
                $('#radio_status_sale_'+index).prop('checked', true);
                return false;
            }
            if (value == 'manual') {
                $('.type_automatic'+index).hide();
                $('.type_manual'+index).show();
            } else if (value == 'automatic') {
                $('.type_manual'+index).hide();
                $('.type_automatic'+index).show();
            }
        }
    </script>
@endsection
