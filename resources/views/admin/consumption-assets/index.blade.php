@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Consumption Assets') }} </title>
@endsection

@section('style')

@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Consumption Assets')}}</li>
            </ol>
        </nav>


{{--                @include('admin.purchase-assets.parts.search')--}}

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-o"></i> {{__('Consumption Assets')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:consumption-assets.create',
                           'new' => '',
                          ])
                        </li>

                        <li class="list-inline-item">
                            <button style="margin-bottom: 12px; border-radius: 5px" type="button" class="btn btn-icon btn-icon-left btn-delete-wg waves-effect waves-light hvr-bounce-to-left" onclick="confirmDeleteSelected('{{route('admin:purchase-assets.deleteSelected')}}')">
                                <i class="ico fa fa-trash"></i>  {{__('Delete Selected')}}
                            </button>

                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center column-invoice-number"
                                    scope="col">{!! __('Number') !!}</th>
                                <th class="text-center column-invoice-type" scope="col">{!! __('Date') !!}</th>
                                <th class="text-center" scope="col">{!! __('Date From') !!}</th>
                                <th class="text-center" scope="col">{!! __('Date to') !!}</th>
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
                                <th>#</th>
                                <th class="text-center"
                                    scope="col">{!! __('Number') !!}</th>
                                <th class="text-center column-invoice-type" scope="col">{!! __('Date') !!}</th>
                                <th class="text-center" scope="col">{!! __('Date From') !!}</th>
                                <th class="text-center" scope="col">{!! __('Date to') !!}</th>
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
@stop


@section('js')
    <script type="application/javascript">

        $(document).ready(function () {
            server_side_datatable($('#datatable-with-btns'))
            $(".select2").select2()
        })
        function printAsset() {
            var element_id = 'asset_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:consumption-assets.show') }}?id=" + id,
                method: 'GET',
                success: function (data) {

                    $("#assetDatatoPrint").html(data.view)
                }
            });
        }
    </script>
{{--    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>--}}
@stop
@section('modals')
    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Purchase Asset')}}</h4>
                </div>

                <div class="modal-body" id="assetDatatoPrint">
                </div>
                <div class="modal-footer" style="text-align:center">
                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printAsset()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">
                        <i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>

    {{--        @include($view_path . '.column-visible')--}}
@endsection
