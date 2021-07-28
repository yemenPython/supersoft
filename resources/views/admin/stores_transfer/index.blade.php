@extends('admin.layouts.app')

@section('title')
    <title>{{ __('words.stores-transfers') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
    <style>
        .wg-label {
            font-size: 10px !important;
            padding: 2px !important;
        }
    </style>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{ route('admin:home') }}"> {{ __('Dashboard') }}</a></li>
                <li class="breadcrumb-item active"> {{ __('words.stores-transfers') }}</li>
            </ol>
        </nav>

        @include('admin.stores_transfer.search_form')
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-home"></i>  {{ __('words.stores-transfers') }}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                                'route' => 'admin:stores-transfers.create',
                                'new' => '',
                            ])
                        </li>
                        <li class="list-inline-item">
                            @component(
                                'admin.buttons._confirm_delete_selected', ['route' => 'admin:stores-transfers.deleteSelected']
                            )
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    @php
                        $view_path = 'admin.stores_transfer.options-datatable';
                    @endphp
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%;margin-top:15px">
                        <thead>
                        <tr>
                            <th class="text-center" scope="col">#</th>
                            @if(authIsSuperAdmin())
                                <th scope="col" class="text-center">{!! __('Branch') !!}</th>
                            @endif
                            <th class="text-center" scope="col">{!! __('words.transfer-date') !!}</th>
                            <th class="text-center" scope="col">{{ __('opening-balance.serial-number') }}</th>
                            <th class="text-center" scope="col">{!! __('words.store-from') !!}</th>
                            <th class="text-center" scope="col">{!! __('words.store-to') !!}</th>
                            <th class="text-center" scope="col">{!! __('Total') !!}</th>
                            <th class="text-center" scope="col">{!! __('Concession Status') !!}</th>
                            <th class="text-center" scope="col">{!! __('Created At') !!}</th>
                            <th class="text-center" scope="col">{!! __('Updated At') !!}</th>
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
                        <th class="text-center" scope="col">#</th>
                            @if(authIsSuperAdmin())
                                <th scope="col" class="text-center">{!! __('Branch') !!}</th>
                            @endif
                        <th class="text-center" scope="col">{!! __('words.transfer-date') !!}</th>
                            <th class="text-center" scope="col">{{ __('opening-balance.serial-number') }}</th>
                            <th class="text-center" scope="col">{!! __('words.store-from') !!}</th>
                            <th class="text-center" scope="col">{!! __('words.store-to') !!}</th>
                            <th class="text-center" scope="col">{!! __('Total') !!}</th>
                            <th class="text-center" scope="col">{!! __('Concession Status') !!}</th>
                            <th class="text-center" scope="col">{!! __('Created At') !!}</th>
                            <th class="text-center" scope="col">{!! __('Updated At') !!}</th>
                            <th scope="col">{!! __('Options') !!}</th>
                            <th scope="col">{!! __('Select') !!}</th>
                        </tr>
                        </tfoot>
                    </table></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('words.stores-transfers')}}</h4>
                </div>

                <div class="modal-body" id="store-transfer-print">
                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printStoreTransfer()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"> <i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>
    @include($view_path . '.column-visible')
@endsection

@section('js')
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
    <script type="application/javascript">
        function printStoreTransfer() {
            var element_id = 'store-transfer-content' ,page_title = document.title
            print_element(element_id ,page_title)
        }

        function getPrintData(url) {
            $("#store-transfer-print").html('{{ __('words.data-loading') }}')
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    $("#store-transfer-print").html(response.code)
                }
            });
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
    @include('opening-balance.common-script')
@endsection
