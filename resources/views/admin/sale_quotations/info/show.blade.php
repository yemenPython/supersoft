@extends('admin.layouts.app')

@section('title')
    <title>{{__('sale-quotations.show')}} </title>
@endsection

@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a
                        href="{{route('admin:sale-quotations.index')}}"> {{__('sale-quotations.index-title')}}</a>
                </li>
                <li class="breadcrumb-item">  {{__('sale-quotations.show')}} </li>
            </ol>
        </nav>

        <div class="col-xs-12">
            <div class="card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial">
                    <i class="fa fa-gears"></i> {{__('sale-quotations.show')}}
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
                                            <td>{{optional($saleQuotation)->branch->name}}</td>
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('sale-quotations.serial-number') }}</th>
                                        <td>{{ $saleQuotation->number }}</td>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('sale-quotations.operation-date') }}</th>
                                        <td>{{$saleQuotation->date}}</td>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('sale-quotations.operation-time') }}</th>
                                        <td>{{ $saleQuotation->time}}</td>
                                        </tbody>
                                    </table>
                                </div>


                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <th style="width:50%;background:#ddd !important;color:black !important">{{ __('sale-quotations.cstomer') }}</th>
                                        <td>{{ optional($saleQuotation->customer)->name}}</td>
                                        </tbody>
                                    </table>
                                </div>


                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sale-quotations.type') }}</th>
                                            <td>{{ __($saleQuotation->type )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sale-quotations.status') }}</th>
                                            <td>{{ __($saleQuotation->status )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sale-quotations.supply_date_from') }}</th>
                                            <td>{{ __($saleQuotation->supply_date_from )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sale-quotations.supply_date_to') }}</th>
                                            <td>{{ __($saleQuotation->supply_date_to )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sale-quotations.date_from') }}</th>
                                            <td>{{ __($saleQuotation->date_from )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sale-quotations.date_to') }}</th>
                                            <td>{{ __($saleQuotation->date_to )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sale-quotations.different_day') }}</th>
                                            <td>{{ __($saleQuotation->different_days )}}</td>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <tbody>
                                            <th style="width:50%;background:#ddd !important;color:black !important">
                                                {{ __('sale-quotations.remaining_days') }}</th>
                                            <td>{{ __($saleQuotation->remaining_days )}}</td>
                                            </tbody>
                                        </table>
                                    </div>
                            </div>
                        </div>
                    </div>

                    @include('admin.sale_quotations.info.table_items')

                    @include('admin.sale_quotations.info.financial')

                    <a href="{{route('admin:sale-quotations.index')}}"
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

