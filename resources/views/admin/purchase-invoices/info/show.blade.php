@extends('admin.layouts.app')

@section('title')
    <title>{{__('purchase-invoices.show')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:purchase-invoices.index')}}"> {{__('purchase-invoices.index-title')}}</a>
                </li>
                <li class="breadcrumb-item">  {{__('purchase-invoices.show')}} </li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial">
                    <i class="fa fa-gears"></i> {{__('purchase-invoices.show')}}
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
                                            <td>{{optional($purchaseInvoice)->branch->name}}</td>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('purchase-invoices.serial-number') }}</th>
                                        <td>{{ $purchaseInvoice->invoice_number }}</td>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('purchase-invoices.operation-date') }}</th>
                                        <td>{{$purchaseInvoice->date}}</td>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('purchase-invoices.operation-time') }}</th>
                                        <td>{{ $purchaseInvoice->time}}</td>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('purchase-invoices.supplier') }}</th>
                                        <td>{{ optional($purchaseInvoice->supplier)->name}}</td>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">
                                            {{ __('purchase-invoices.supply_order') }}</th>
                                        <td>{{ __($purchaseInvoice->supplyOrder ? $purchaseInvoice->supplyOrder->number : '---' )}}</td>
                                        </tbody>
                                    </table>
                                </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('purchase-invoices.type') }}</th>
                                            <td>{{ __($purchaseInvoice->type )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('purchase-invoices.status') }}</th>
                                            <td>{{ __($purchaseInvoice->status )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('purchase-invoices.invoice_type') }}</th>
                                            <td>{{ __($purchaseInvoice->invoice_type )}}</td>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>

                    @include('admin.purchase-invoices.info.table_items')

                    @include('admin.purchase-invoices.info.financial')

                    <a href="{{route('admin:purchase-invoices.index')}}"
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

