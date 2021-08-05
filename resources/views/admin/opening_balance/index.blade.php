@extends('admin.layouts.app')

@section('title')
    <title>{{ __('opening-balance.index-title') }} </title>
@endsection

@section('accounting-module-modal-area')
    @include('admin.opening_balance.optional-datatable.column-visible')
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item active"> {{ __('opening-balance.index-title') }} </li>
        </ol>
    </nav>

    @include('admin.opening_balance.search')

    <div class="col-md-12">
        <div class="box-content card bordered-all primary">
            <h4 class="box-title bg-primary"><i class="ico fa fa-gears"></i>
                {{ __('opening-balance.index-title') }}
            </h4>

            <div class="card-content js__card_content" style="">
                <ul class="list-inline pull-left top-margin-wg">

                    <li class="list-inline-item">
                        @include('admin.buttons.add-new', [
                            'route' => 'admin:opening-balance.create',
                            'new' => '',
                        ])
                    </li>

                    <li class="list-inline-item">
                        @component('admin.buttons._confirm_delete_selected',[
                            'route' => 'admin:opening-balance.deleteSelected',
                        ])
                        @endcomponent
                    </li>
                </ul>


                <div class="clearfix"></div>
                <div class="card-content">
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th class="text-center column-id">#</th>
                                @if(authIsSuperAdmin())
                                    <th class="text-center column-name"> {{ __('opening-balance.branch') }} </th>
                                @endif
                                <th class="text-center"> {{ __('opening-balance.operation-date') }} </th>
                                <th class="text-center"> {{ __('opening-balance.serial-number') }} </th>
                                <th class="text-center"> {{ __('opening-balance.total') }} </th>
                                <th class="text-center"> {{ __('opening-balance.status') }} </th>
                                <th class="text-center"> {{ __('created at') }} </th>
                                <th class="text-center"> {{ __('Updated at') }} </th>
                                <th class="text-center"> {{ __('Options') }} </th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>
                                    {!! __('Select') !!}
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th class="text-center column-id">#</th>
                                @if(authIsSuperAdmin())
                                    <th class="text-center column-name"> {{ __('opening-balance.branch') }} </th>
                                @endif
                                <th class="text-center"> {{ __('opening-balance.operation-date') }} </th>
                                <th class="text-center"> {{ __('opening-balance.serial-number') }} </th>
                                <th class="text-center"> {{ __('opening-balance.total') }} </th>
                                <th class="text-center"> {{ __('opening-balance.status') }} </th>
                                <th class="text-center"> {{ __('created at') }} </th>
                                <th class="text-center"> {{ __('Updated at') }} </th>
                                <th class="text-center"> {{ __('Options') }} </th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>
                                    {!! __('Select') !!}
                                </th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')

    @include('admin.partial.print_modal', ['title'=> __('Purchase Quotations')])

@endsection


@section('accounting-scripts')
    <script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>

    <script type="application/javascript">

        function printDownPayment() {
            var element_id = 'data_to_print', page_title = document.title
            print_element(element_id, page_title)
        }

        function getPrintData(id) {

            $.ajax({
                url: "{{ route('admin:opening.balance.print') }}?opening_balance_id=" + id,
                method: 'GET',
                success: function (data) {
                    $("#data_to_print").html(data.view);
                    let total = $("#totalInLetters").text()
                    $("#totalInLetters").html(new Tafgeet(parseFloat(total), '{{config("currency.defualt_currency")}}').parse())
                }
            });
        }

        function alert(message) {
            swal({
                title: "{{ __('accounting-module.warning') }}",
                text: message,
                icon: "warning",
                reverseButtons: false,
                buttons: {
                    cancel: {
                        text: "{{ __('words.ok') }}",
                        className: "btn btn-primary",
                        value: null,
                        visible: true
                    }
                }
            })
        }
    </script>

    <script type="application/javascript">
        function delete_confirm(url) {
            swal({
                title: "{{ __('accounting-module.warning') }}",
                text: "{{ __('opening-balance.are-you-sure-to-deleted') }}",
                icon: "warning",
                reverseButtons: false,
                buttons: {
                    confirm: {
                        text: "{{ __('words.yes_delete') }}",
                        className: "btn btn-default",
                        value: true,
                        visible: true
                    },
                    cancel: {
                        text: "{{ __('words.no') }}",
                        className: "btn btn-default",
                        value: null,
                        visible: true
                    }
                }
            }).then(function (confirm_delete) {
                if (confirm_delete) {
                    window.location = url
                } else {
                    alert("{{ __('opening-balance.balance-not-deleted') }}")
                }
            })
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
