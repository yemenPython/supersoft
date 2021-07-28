@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Settlements') }} </title>
@endsection

@section('modals')
    @include('admin.settlements.optional-datatable.column-visible')
@endsection
@section('content')
    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Settlements')}}</li>
            </ol>
        </nav>

        @include('admin.settlements.search')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-cube"></i>  {{__('Settlements')}}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                        <li class="list-inline-item">
                            @include('admin.buttons.add-new', [  'route' => 'admin:settlements.create',  'new' => '',])
                        </li>

                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',['route' => 'admin:settlements.deleteSelected',])
                            @endcomponent
                        </li>

                    </ul>

                    <div class="clearfix"></div>

                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col" >{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col" >{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col" >{!! __('Date') !!}</th>
                                <th scope="col">{!! __('Number') !!}</th>
                                <th scope="col" >{{__('settlement type')}}</th>
                                <th scope="col" >{!! __('Total') !!}</th>
                                <th scope="col" >{!! __('Concession Status') !!}</th>
                                <th scope="col" >{!! __('Created Date') !!}</th>
                                <th scope="col" >{!! __('Updated Date') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col" >{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                <th scope="col" >{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col" >{!! __('Date') !!}</th>
                                <th scope="col">{!! __('Number') !!}</th>
                                <th scope="col" >{{__('settlement type')}}</th>
                                <th scope="col" >{!! __('Total') !!}</th>
                                <th scope="col" >{!! __('Concession Status') !!}</th>
                                <th scope="col" >{!! __('Created Date') !!}</th>
                                <th scope="col" >{!! __('Updated Date') !!}</th>
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
    @include('opening-balance.common-script')
    <script>
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
