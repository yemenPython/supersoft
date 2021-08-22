@extends('admin.layouts.app')

@section('title')
    <title>{{__('sales-returns.show')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:sales.invoices.return.index')}}"> {{__('sales-returns.index-title')}}</a>
                </li>
                <li class="breadcrumb-item">  {{__('sales-returns.show')}} </li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial">
                    <i class="fa fa-gears"></i> {{__('sales-returns.show')}}
                    <span class="controls hidden-sm hidden-xs pull-left">

							<button class="control text-white"
                                    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

                <div class="box-content">

                    {{-- Info  --}}
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="row top-data-wg"
                                 style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                                @if(authIsSuperAdmin())
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">{{__('Branch')}}</th>
                                            <td>{{optional($salesInvoiceReturn->branch)->name}}</td>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('sales-invoices.serial-number') }}</th>
                                        <td>{{ $salesInvoiceReturn->number }}</td>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('sales-invoices.operation-date') }}</th>
                                        <td>{{$salesInvoiceReturn->date}}</td>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('sales-invoices.operation-time') }}</th>
                                        <td>{{ $salesInvoiceReturn->time}}</td>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('sales-invoices.supplier') }}</th>
                                        <td>{{ optional($salesInvoiceReturn->clientable)->name}}</td>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">
                                            {{ __('sales-invoices.returnedInvoice') }}</th>
                                        <td>
                                          {{$salesInvoiceReturn->invoiceable ? $salesInvoiceReturn->invoiceable->number : '---'}}
                                        </td>
                                        </tbody>
                                    </table>
                                </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sales-invoices.type') }}</th>
                                            <td>{{ __($salesInvoiceReturn->type )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sales-invoices.status') }}</th>
                                            <td>{{ __($salesInvoiceReturn->status )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sales-invoices.invoice_type') }}</th>
                                            <td>{{ __($salesInvoiceReturn->invoice_type )}}</td>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>

                    @include('admin.sales_invoice_return.info.table_items')

                    @include('admin.sales_invoice_return.info.financial')

                    <a href="{{route('admin:sales.invoices.return.index')}}"
                       class="btn btn-danger waves-effect waves-light">
                        <i class=" fa fa-reply"></i> {{__('Back')}}
                    </a>
                </div>
            </div>


        </div>
    </div>
@endsection

@section('modals')
    @include('admin.partial.part_image')
@endsection

@section('js-validation')

    <script type="application/javascript">

        function getPartImage(index) {

            let image_path = $('#part_img_id_' + index).data('img');
            $('#part_image').attr('src', image_path);
        }

    </script>

@endsection

