@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Maintenance centers') }} </title>
@endsection

@section('style')
    <link rel="stylesheet" href="{{asset('assets/plugin/iCheck/skins/square/blue.css')}}">
    <link rel="stylesheet" href="{{ asset('accounting-module/daily-restriction.css') }}"/>
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Maintenance centers')}}</li>
            </ol>
        </nav>

        @include('admin.maintenance_centers.search_from')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-check-square-o"></i> {{__('Maintenance centers')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', ['route' => 'admin:maintenance_centers.create','new' => '',])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                          'route' => 'admin:maintenance_centers.deleteSelected',
                           ])
                            @endcomponent
                        </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Phone') !!}</th>
                                <th scope="col">{!! __('E-Mail') !!}</th>
                                <th scope="col">{!! __('Commercial Number') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Created At') !!}</th>
                                <th scope="col">{!! __('Updated At') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                <th scope="col">{!! __('Name') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Phone') !!}</th>
                                <th scope="col">{!! __('E-Mail') !!}</th>
                                <th scope="col">{!! __('Commercial Number') !!}</th>
                                <th scope="col">{!! __('Status') !!}</th>
                                <th scope="col">{!! __('Created At') !!}</th>
                                <th scope="col">{!! __('Updated At') !!}</th>
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
@endsection

@section('js')
    <script type="application/javascript" defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>

    <Script>
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

        function openModalToShow(id) {
            event.preventDefault();
            $.ajax({
                url: '{{url('admin/maintenance_centers')}}' + '/' + id,
                type: 'get',
                success: function (response) {
                    $('#showAssetModal').modal('show');
                    $('#showAssetResponse').html(response.data);
                }
            });
        }

        var map;
        var marker = false;
        function initMap(lat, long) {
            let longVal = parseFloat(long);
            let latVal = parseFloat(lat);
            const myLatLng = { lat: latVal, lng: longVal };
            var centerOfMap = new google.maps.LatLng(latVal, longVal);
            var options = {
                center: centerOfMap,
                zoom: 7
            };
            map = new google.maps.Map(document.getElementById('map'), options);
            new google.maps.Marker({
                position: myLatLng,
                map:map
            });
        }
        function OpenLocation(lat, long) {
            $('#boostrapModal-2').modal('show');
            google.maps.event.addDomListener(window, 'load', initMap(lat, long));
        }
    </Script>
@endsection


@section('modals')

    <div class="modal fade" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Location')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body" id="map" style="height: 400px;">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection
