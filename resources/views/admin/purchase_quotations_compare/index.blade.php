@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Purchase Quotations Compare') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Purchase Quotations Compare')}}</li>
            </ol>
        </nav>

        @include('admin.purchase_quotations_compare.search_form')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-text-o"></i> {{__('Purchase Quotations Compare')}}
                </h4>

                <div class="card-content js__card_content" style="">

                    <form action="{{route('admin:purchase.quotations.compare.store')}}" method="post">
                        @csrf

                        @if(authIsSuperAdmin())

                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                                    <div class="input-group">

                                        <span class="input-group-addon fa fa-file"></span>

                                        <select class="form-control js-example-basic-single" name="branch_id"
                                                id="branch_id" onchange="changeBranch()"
                                        >
                                            <option value="">{{__('Select Branch')}}</option>

                                            @foreach($branches as $branch)
                                                <option value="{{$branch->id}}"
                                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                                >
                                                    {{$branch->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{input_error($errors,'branch_id')}}
                                </div>

                            </div>

                        @endif

                        <ul class="list-inline pull-left top-margin-wg">
                            <li class="list-inline-item">

                                <button type="submit" style="margin-bottom: 12px; border-radius: 5px"
                                        class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
                                    {{__('create supply order')}}
                                    <i class="ico fa fa-plus"></i>
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
                                    <th scope="col">{!! __('Purchase Quotation Number') !!}</th>
                                    <th scope="col">{!! __('Purchase Request Number') !!}</th>
                                    <th scope="col">{!! __('Supplier name') !!}</th>
                                    <th scope="col">{!! __('Part') !!}</th>
                                    <th scope="col">{!! __('Part Type') !!}</th>
                                    <th scope="col">{!! __('Unit') !!}</th>
                                    <th scope="col">{!! __('Price Segments') !!}</th>
                                    <th scope="col">{!! __('Quantity') !!}</th>
                                    <th scope="col">{!! __('Price') !!}</th>

                                    <th scope="col">{!! __('Discount Type') !!}</th>
                                    <th scope="col">{!! __('Discount') !!}</th>
                                    <th scope="col">{!! __('Total Before Discount') !!}</th>
                                    <th scope="col">{!! __('Total After Discount') !!}</th>
                                    <th scope="col">{!! __('The Tax') !!}</th>
                                    <th scope="col">{!! __('Total') !!}</th>
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
                                    <th scope="col">{!! __('Purchase Quotation Number') !!}</th>
                                    <th scope="col">{!! __('Purchase Request Number') !!}</th>
                                    <th scope="col">{!! __('Supplier') !!}</th>
                                    <th scope="col">{!! __('Part') !!}</th>
                                    <th scope="col">{!! __('Part Type') !!}</th>
                                    <th scope="col">{!! __('Unit') !!}</th>
                                    <th scope="col">{!! __('Price Segments') !!}</th>
                                    <th scope="col">{!! __('Quantity') !!}</th>
                                    <th scope="col">{!! __('Price') !!}</th>

                                    <th scope="col">{!! __('Discount Type') !!}</th>
                                    <th scope="col">{!! __('Discount') !!}</th>
                                    <th scope="col">{!! __('Total Before Discount') !!}</th>
                                    <th scope="col">{!! __('Total After Discount') !!}</th>
                                    <th scope="col">{!! __('The Tax') !!}</th>
                                    <th scope="col">{!! __('Total') !!}</th>
                                    <th scope="col">{!! __('Options') !!}</th>
                                    <th scope="col">
                                        <div class="checkbox danger">
                                            <input type="checkbox" id="select-all">
                                            <label for="select-all"></label>
                                        </div>{!! __('Select') !!}
                                    </th>
                                </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    @include('admin.partial.print_modal', ['title'=> __('Purchase Requests')])

    @include('admin.partial.part_image')

@endsection

@section('js-validation')

    <script type="application/javascript">

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:purchase.quotations.compare.index')}}" + "?branch_id=" + branch_id;
        }

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
                    $("#totalInLetters").html(new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }

        function getParts() {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let spare_part_id = $('#spare_part_id').find(":selected").val();

            $.ajax({

                type: 'post',

                url: '{{route('admin:purchase.quotations.compare.get.parts')}}',

                data: {
                    _token: CSRF_TOKEN,
                    spare_part_id: spare_part_id
                },

                success: function (data) {

                    $("#parts_options").html(data.parts);
                    $('.js-example-basic-single').select2();
                },

                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        }

        function getPurchaseQuotations(type) {

            let CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            let item_id = $('#supplier').find(":selected").val();

            if (type == 'purchase_request') {
                item_id = $('#purchase_request').find(":selected").val();
            }

            $.ajax({

                type: 'post',
                url: '{{route('admin:purchase.quotations.compare.get.quotations')}}',
                data: {
                    _token: CSRF_TOKEN,
                    type: type,
                    item_id: item_id
                },

                success: function (data) {

                    $("#purchase_quotations").html(data.quotations);
                    $('.js-example-basic-single').select2();
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

        function getPartImage(index) {

            let image_path = $('.part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
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

    </script>
@endsection

@section('js')
    <script type="application/javascript">
        invoke_datatable($('#cities'))
    </script>
@endsection
