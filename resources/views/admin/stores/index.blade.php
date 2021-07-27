@extends('admin.layouts.app')
@section('title')
<title>{{ __('Stores') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Stores')}}</li>
            </ol>
        </nav>

        @include('admin.stores.parts.search')
        <div class="col-xs-12">
        <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-home"></i>  {{__('Stores')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:stores.create',
                      'new' => '',
                     ])
                       </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                      'route' => 'admin:stores.deleteSelected',
                                       ])
                                @endcomponent
                            </li>

                    </ul>
                       <div class="clearfix"></div>

                        <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Store Name') !!}</th>
                                <th scope="col">{!! __('employees count') !!}</th>
                                <th scope="col">{!! __('Created At') !!}</th>
                                <th scope="col">{!! __('Updated At') !!}</th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                <div class="checkbox danger">
                                        <input type="checkbox"  id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col">{!! __('#') !!}</th>
                                @if(authIsSuperAdmin())
                                    <th scope="col">{!! __('Branch') !!}</th>
                                @endif
                                <th scope="col">{!! __('Store Name') !!}</th>
                                <th scope="col">{!! __('employees count') !!}</th>
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

@section('modals')
    <div class="modal fade wg-content" id="empModal" role="dialog">
        <div class="modal-dialog" style="width:800px;">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="box-loader">
                        <p>{{__('Loading')}}</p>
                        <div class="loader-31"></div>
                    </div>
                    <div id="storeData">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script type="application/javascript">
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
        function loadDataWithModal(storeId) {
            event.preventDefault();
            $.ajax({
                url: '{{route('admin:stores.show')}}',
                type: 'post',
                data: {
                    _token: '{{csrf_token()}}',
                    storeId: storeId
                },
                success: function (response) {
                    $('#empModal').modal('show');
                    setTimeout( () => {
                        $('.box-loader').hide();
                        $('#storeData').html(response.data);
                        },3000)
                }
            });
        }

        $('#empModal').on('hidden.bs.modal', function () {
            $('.box-loader').show();
            $('#storeData').html('');
        })
    </script>

@endsection

