@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Assets Expenses') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Assets Expenses')}}</li>
            </ol>
        </nav>
        @include('admin.assets_expenses.search_form')
                <div class="col-xs-12">
                <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-money"></i> {{__('Assets Expenses')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:assets_expenses.create',
                      'new' => '',
                     ])
                       </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:assets_expenses_items.deleteSelected',
                                 ])
                                @endcomponent
                            </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="datatable-with-btns" class="table table-bordered table-hover wg-table-print"  style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        @if(authIsSuperAdmin())
                        <th scope="col">{!! __('Branch') !!}</th>
                        @endif
                        <th scope="col">{!! __('Date') !!}</th>
                        <th scope="col">{!! __('Number') !!}</th>
                        <th scope="col">{!! __('Total') !!}</th>
                        <th scope="col">{!! __('Status') !!}</th>
                        <th scope="col">{!! __('Created at') !!}</th>
                        <th scope="col">{!! __('Updated at') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">
                        <div class="checkbox danger">
                                <input type="checkbox"  id="select-all">
                                <label for="select-all"></label>
                            </div>{!! __('Select') !!}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        @if(authIsSuperAdmin())
                        <th scope="col">{!! __('Branch') !!}</th>
                        @endif
                        <th scope="col">{!! __('Date') !!}</th>
                        <th scope="col">{!! __('Number') !!}</th>
                        <th scope="col">{!! __('Total') !!}</th>
                        <th scope="col">{!! __('Status') !!}</th>
                        <th scope="col">{!! __('Created at') !!}</th>
                        <th scope="col">{!! __('Updated at') !!}</th>
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
    </div>
@endsection
@section('js')
    <script type="application/javascript">
        server_side_datatable('#datatable-with-btns');
        function printAsset() {
            var element_id = 'assetDatatoPrint', page_title = document.title
            print_element(element_id, page_title)
        }
        function getPrintData(id, show = null) {
            $.ajax({
                url: "{{ url('admin/assets_expenses/')}}" +'/'+ id,
                method: 'GET',
                data : {
                    show : show,
                },
                success: function (data) {
                    if (show) {
                        $("#boostrapModalResponse").html(data.view)
                        let total = $("#totalInLettersShow").text()
                        $("#totalInLettersShow").html(new Tafgeet(total, '{{config("currency.defualt_currency")}}').parse())
                    } else {
                        $("#assetDatatoPrint").html(data.view)
                        let total = $("#totalInLetters").text()
                        $("#totalInLetters").html(new Tafgeet(total, '{{config("currency.defualt_currency")}}').parse())
                    }
                }
            });
        }

        function filterFunction($this) {
            $("#loaderSearch").show();
            $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
            $datatable.ajax.url($url).load();
            $(".js__card_minus").trigger("click");
            setTimeout(function () {
                $("#loaderSearch").hide();
            }, 1000)
        }
    </script>
@endsection
@section('modals')

    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printAsset()" id="print_sales_invoice">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>
                </div>

                <div class="modal-body" id="assetDatatoPrint">


                </div>
                <div class="modal-footer" style="text-align:center">



                </div>

            </div>
        </div>
    </div>


    <div class="modal fade" id="boostrapModalShow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>

                    <h3 class="text-center"><span> {{__('Asset Expenses invoice')}} </span></h3>
                </div>

                <div class="modal-body" id="boostrapModalResponse">


                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>
                </div>

            </div>
        </div>
    </div>
@endsection
