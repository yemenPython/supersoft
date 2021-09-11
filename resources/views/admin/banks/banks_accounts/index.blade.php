@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Accounts') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugin/iCheck/skins/square/blue.css')}}">
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0 !important;padding:0">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="#"> {{__('Managing bank accounts')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Accounts')}}</li>
            </ol>
        </nav>

         {{--@include('admin.banks.bank_data.search_form')--}}
        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-bank"></i> {{__('Accounts')}}
                    <span class="text-danger">[ {{count($items->get())}} ]</span>
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', ['route' => 'admin:banks.banks_accounts.create', 'new' => ''])
                        </li>
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:suppliers.deleteSelected'])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th scope="col"> {{ __('#') }} </th>
                                    <th>{{__('Bank Name')}}</th>
                                    <th>{{__('Branch Name')}}</th>
                                    <th>{{__('Branch Code')}}</th>
                                    <th>{{__('Swift Code')}}</th>
                                    <th scope="col"> {{ __('Start Date With Bank') }} </th>
                                    <th scope="col"> {{ __('Stop Date With Bank') }} </th>
                                    <th scope="col"> {{ __('status') }} </th>
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
                                    <th scope="col"> {{ __('#') }} </th>
                                    <th>{{__('Bank Name')}}</th>
                                    <th>{{__('Branch Name')}}</th>
                                    <th>{{__('Branch Code')}}</th>
                                    <th>{{__('Swift Code')}}</th>
                                    <th scope="col"> {{ __('Start Date With Bank') }} </th>
                                    <th scope="col"> {{ __('Stop Date With Bank') }} </th>
                                    <th scope="col"> {{ __('status') }} </th>
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

@section('modals')
    <div class="modal fade wg-content" id="showBankData" role="dialog">
        <div class="modal-dialog" style="width:800px;">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="box-loader">
                        <p>{{__('Loading')}}</p>
                        <div class="loader-31"></div>
                    </div>
                    <div id="showBankDataResponse">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        server_side_datatable('#datatable-with-btns');
        function filterFunction($this) {
            $("#loaderSearch").show();
            $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
            $datatable.ajax.url($url).load();
            $(".js__card_minus").trigger("click");
            setTimeout(function () {
                $("#loaderSearch").hide();
            }, 1000)
        }

        function loadDataWithModal(route, modal, target) {
            event.preventDefault();
            $.ajax({
                url: route,
                type: 'get',
                success: function (response) {
                    $('#showBankData').modal('show');
                    setTimeout( () => {
                        $('.box-loader').hide();
                        $('#showBankDataResponse').html(response.data);
                        modalDatatable('productsTable')
                    },1000)
                }
            });
        }

        $('#showBankData').on('hidden.bs.modal', function () {
            $('.box-loader').show();
            $('#showBankDataResponse').html('');
        })


    </script>
@endsection
