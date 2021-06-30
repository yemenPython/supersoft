@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Purchase Assets') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Purchase Assets')}}</li>
            </ol>
        </nav>


{{--        @include('admin.purchase-invoices.parts.search')--}}

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
{{--                            @component('admin.buttons._confirm_delete_selected',[--}}
{{--                                                  'route' => 'admin:purchase-assets.deleteSelected',--}}
{{--                                                   ])--}}
{{--                            @endcomponent--}}

                                <button style="margin-bottom: 12px; border-radius: 5px" type="button" class="btn btn-icon btn-icon-left btn-delete-wg waves-effect waves-light hvr-bounce-to-left" onclick="confirmDeleteSelected('{{route('admin:purchase-assets.deleteSelected')}}')">
                                    <i class="ico fa fa-trash"></i>  {{__('Delete Selected')}}
                                </button>

                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
{{--                        @php--}}
{{--                            $view_path = 'admin.purchase-assets.options-datatable';--}}
{{--                        @endphp--}}
{{--                        @include($view_path . '.option-row')--}}
                        <div class="clearfix"></div>
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <tr>
                                <th>#</th>
                                <th class="text-center column-invoice-number"
                                    scope="col">{!! __('Invoice Number') !!}</th>

                                <th class="text-center column-invoice-type" scope="col">{!! __('date and time') !!}</th>

                                <th class="text-center column-supplier" scope="col">{!! __('Supplier Name') !!}</th>

                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th class="text-center column-invoice-number"
                                    scope="col">{!! __('Invoice Number') !!}</th>

                                <th class="text-center column-invoice-type" scope="col">{!! __('date and time') !!}</th>

                                <th class="text-center column-supplier" scope="col">{!! __('Supplier Name') !!}</th>

                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($invoices as $index=>$invoice)
                                <tr>

                                    <td class="text-center">{{$loop->iteration}}</td>
                                    <td class="text-center column-invoice-number">{!! $invoice->invoice_number !!}</td>

                                    <td class="text-center column-date">{{$invoice->date}}{{$invoice->time}}</td>

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

                                        @component('admin.purchase-assets.parts.print',[
                                                    'id'=> $invoice->id,
                                                    'invoice'=> $invoice,
                                                     ])
                                        @endcomponent
                                        </li>


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
                        </table>
                        {{ $invoices->links() }}
                    </div>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Purchase Invoice')}}</h4>
                </div>

                <div class="modal-body" id="invoiceDatatoPrint">
                </div>
                <div class="modal-footer" style="text-align:center">
                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()">
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
{{--    {{dd($view_path)}}--}}
{{--    @include($view_path . '.column-visible')--}}
@endsection

@section('js')


    <script type="application/javascript">

        @if(request()->query('print_type'))

        $(document).ready(function () {
            invoke_datatable($('#datatable-with-btns'))
            var id = '{{request()->query('invoice')}}'

            getPrintData(id);

            $('#boostrapModal').modal('show');
        });

        @endif

        // invoke_datatable($('#purchaseInvoices'))

        function printDownPayment() {
            var element_id = 'purchase_invoice_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            alert(id)
            $.ajax({
                url: "{{ route('admin:purchase-invoices.show') }}?invoiceID=" + id,
                method: 'GET',
                success: function (data) {
                    $("#invoiceDatatoPrint").html(data.invoice)
                    let total = $("#totalInLetters").text()
                    $("#totalInLetters").html(new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }
    </script>
    <script type="application/javascript" src="{{ asset('accounting-module/options-for-dt.js') }}"></script>
@endsection
