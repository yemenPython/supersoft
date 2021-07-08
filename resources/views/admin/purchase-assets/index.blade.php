@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Purchase Assets') }} </title>
@endsection

@section('style')
{{--    <link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">--}}
{{--    <link href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css" rel="stylesheet">--}}
{{--    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>--}}
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Purchase Assets')}}</li>
            </ol>
        </nav>


{{--                @include('admin.purchase-assets.parts.search')--}}

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-file-o"></i> {{__('Purchase Assets')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [
                           'route' => 'admin:purchase-assets.create',
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
{{--                                                @php--}}
{{--                                                    $view_path = 'admin.purchase-assets.options-datatable';--}}
{{--                                                @endphp--}}
{{--                                                @include($view_path . '.option-row')--}}

                        <table id="datatable-with-btns" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th class="text-center column-invoice-number"
                                    scope="col">{!! __('Invoice Number') !!}</th>

                                <th class="text-center column-invoice-type" scope="col">{!! __('Date') !!}</th>

                                <th class="text-center column-supplier" scope="col">{!! __('Supplier Name') !!}</th>

                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($invoices as $index=>$invoice)
                                <tr>

                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td class="text-center column-invoice-number">{!! $invoice->invoice_number !!}</td>

                                    <td class="text-center column-date">{{$invoice->date}} {{$invoice->time}}</td>

                                    <td class="text-center column-supplier">{!! optional($invoice->supplier)->name !!}</td>

                                    <td>
                                        <div class="btn-group margin-top-10">

                                            <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="ico fa fa-bars"></i>
                                                {{__('Options')}} <span class="caret"></span>

                                            </button>
                                            <ul class="dropdown-menu dropdown-wg">
                                                <li>

                                                    @component('admin.buttons._edit_button',[
                                                                'id'=>$invoice->id,
                                                                'route' => 'admin:purchase-assets.edit',
                                                                 ])
                                                    @endcomponent
                                                </li>
                                                <li class="btn-style-drop">

                                                    @component('admin.buttons._delete_button',[
                                                                'id'=> $invoice->id,
                                                                'route' => 'admin:purchase-assets.destroy',
                                                                 ])
                                                    @endcomponent
                                                </li>

                                                <li>
                                                    <a style="cursor:pointer"class="btn btn-print-wg text-white  " data-toggle="modal" onclick="getPrintData('{{route('admin:purchase-assets.show',$invoice->id)}}')"
                                                       data-target="#boostrapModal" title="{{__('print')}}">
                                                        <i class="fa fa-print"></i> {{__('Print')}}
                                                    </a>

                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>
                                        @component('admin.buttons._delete_selected',[
                                                   'id' => $invoice->id,
                                                   'route' => 'admin:purchase-assets.deleteSelected',

                                                    ])
                                        @endcomponent
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th class="text-center column-invoice-number"
                                    scope="col">{!! __('Invoice Number') !!}</th>

                                <th class="text-center column-invoice-type" scope="col">{!! __('Date') !!}</th>

                                <th class="text-center column-supplier" scope="col">{!! __('Supplier Name') !!}</th>

                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                        </table>
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop


@section('js')

{{--<script type="application/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>--}}
{{--<script type="application/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>--}}
{{--<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>--}}
{{--<script type="application/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}
    <script type="application/javascript">

        $(document).ready(function () {
            invoke_datatable($('#datatable-with-btns'))
            $(".select2").select2()
        })
        $(document).ready(function () {
                // $('#datatable-with-btns').DataTable( {
                //     dom: 'Bfrtip',
                //     buttons: [
                //         'copy', 'csv', 'excel', 'pdf', 'print'
                //     ]
                // } );

            {{--$('#datatable-with-btns').DataTable({--}}
            {{--    "bSort": withSorting,--}}
            {{--    "language": {--}}
            {{--        "url": "{{app()->isLocale('ar')  ? "//cdn.datatables.net/plug-ins/1.10.20/i18n/Arabic.json" :--}}
            {{--                                 "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/English.json"}}",--}}
            {{--    },--}}
            {{--    dom: 'Bfrtip',--}}
            {{--    buttons: [--}}
            {{--        {--}}
            {{--            extend: 'print',--}}
            {{--            text: '<i class="fa fa-print"></i> {{__('Print')}}',--}}
            {{--            autoPrint: false,--}}
            {{--        },--}}
            {{--        {--}}
            {{--            extend: 'csv',--}}
            {{--            text: '<i class="fa fa-table"></i> {{__('Excel')}}',--}}
            {{--            // exportOptions: {--}}
            {{--            //     columns: ':visible:not(:last-child)'--}}
            {{--            // }--}}
            {{--        },--}}
            {{--        {--}}
            {{--            extend: 'colvis',--}}
            {{--            text: '<i class="fa fa-list"></i> {{__('Columns visibility')}}',--}}
            {{--        },--}}
            {{--    ],--}}
            {{--});--}}
            // $('#boostrapModal').modal('show');
        });

        function printAsset() {
            var element_id = 'asset_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(route) {
            $.ajax({
                url: route,
                method: 'GET',
                success: function (data) {
                    $("#invoiceDatatoPrint").html(data.invoice)
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

                <div class="modal-body" id="invoiceDatatoPrint">
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
