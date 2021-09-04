@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{__('Lockers Transfer')}} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0 !important;padding:0">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('Lockers Transfer')}}</li>
            </ol>
        </nav>
        @include('admin.lockers-transfer.index_locker_transfer')
    </div>
@endsection

@section('accounting-module-modal-area')
    <div class="remodal" data-remodal-id="show_data" role="dialog" aria-labelledby="modal1Title"
        aria-describedby="modal1Desc">
        <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
        <div class="remodal-content" id="printThis">
            <div id="DataToPrint">
            </div>
        </div>

        <button  class="btn btn-primary waves-effect waves-light" onclick="print_data()">
        <i class='fa fa-print'></i>{{__('Print')}}
        </button>

        <button data-remodal-action="cancel" class="btn btn-danger waves-effect waves-light">
        <i class='fa fa-close'></i>
        {{__('Close')}}</button>
    </div>
@endsection

@section('js')
    <script type="application/javascript">

        function getByBranch() {
            var id = $("#branch_id").val();
            $.ajax({
                url: '{{route('admin:get.lockers.transfer.by.branch')}}',
                type: "get",
                data: {id: id},
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function (data) {
                    $("#data_by_branch").html(data);
                    $('.js-example-basic-single').select2();
                }, error: function (jqXhr, json, errorThrown) {
                    swal("sorry!", 'sorry please try later', "error");
                },
            });
        }

        function print_data() {
            var page_title = document.title
            print_element('printThis' ,page_title)
        }

        function show_transfer_print(url) {
            $("#DataToPrint").html("{{ __('words.data-loading') }}")
            $('[data-remodal-id=show_data]').remodal().open()
            $.ajax({
                url: url,
                method: 'GET',
                success: function (data) {
                    $("#DataToPrint").html(data.code)
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

@endsection
