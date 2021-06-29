@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Assets Expenses') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">

    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Assets Expenses')}}</li>
            </ol>
        </nav>
                <div class="col-xs-12">
                <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                <i class="fa fa-money"></i> {{__('Assets Expenses')}}
                 </h4>

                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">

                       <li class="list-inline-item">
                       @include('admin.buttons.add-new', [
                  'route' => 'admin:assets_expenses.create',
                      'new' => '',
                     ])
                       </li>

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:assets_expenses_items.deleteSelected',
                                 ])
                                @endcomponent
                            </li>

                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                <table id="revenuesItems" class="table table-bordered" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Number') !!}</th>
                        <th scope="col">{!! __('Date') !!}</th>
                        <th scope="col">{!! __('Time') !!}</th>
                        <th scope="col">{!! __('Status') !!}</th>
                        <th scope="col">{!! __('Total') !!}</th>
                        <th scope="col">{!! __('Created at') !!}</th>
                        <th scope="col">{!! __('Updated at') !!}</th>
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
                        <th scope="col">{!! __('Number') !!}</th>
                        <th scope="col">{!! __('Date') !!}</th>
                        <th scope="col">{!! __('Time') !!}</th>
                        <th scope="col">{!! __('Status') !!}</th>
                        <th scope="col">{!! __('Total') !!}</th>
                        <th scope="col">{!! __('Created at') !!}</th>
                        <th scope="col">{!! __('Updated at') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">{!! __('Select') !!}</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($assetsExpenses as $index=>$item)
                        <tr>
                            <td>{!! $index +1 !!}</td>
                            <td>{!! $item->number !!}</td>
                            <td>{!! $item->date  !!} </td>
                            <td>{!! $item->time  !!} </td>
                            <td>
                                @if( $item->status == 'pending' )
                                    <span class="label label-info wg-label"> {{__('Pending')}}</span>
                                @elseif( $item->status == 'accept' )
                                    <span class="label label-success wg-label"> {{__('Accepted')}} </span>
                                @elseif( $item->status == 'cancel' )
                                    <span class="label label-danger wg-label"> {{__('Rejected')}} </span>
                                @endif

                            </td>
                            <td> <span class="label label-warning wg-label"> {!! number_format($item->total, 2) !!} </span></td>
                            <td>{!! $item->created_at->format('y-m-d h:i:s A') !!}</td>
                            <td>{!! $item->updated_at->format('y-m-d h:i:s A') !!}</td>
                            <td>
                                @component('admin.buttons._edit_button',[
                                            'id'=>$item->id,
                                            'route' => 'admin:assets_expenses.edit',
                                             ])
                                @endcomponent

                                @component('admin.buttons._delete_button',[
                                            'id'=> $item->id,
                                            'route' => 'admin:assets_expenses.destroy',
                                             ])
                                @endcomponent
                                    <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                                       data-toggle="modal"

                                       onclick="getPrintData({{$item->id}})"
                                       data-target="#boostrapModal" title="{{__('print')}}">
                                        <i class="fa fa-print"></i> {{__('Print')}}
                                    </a>

                            </td>
                            <td>
                                @component('admin.buttons._delete_selected',[
                                         'id' => $item->id,
                                         'route' => 'admin:assets_expenses.deleteSelected',
                                          ])
                                @endcomponent
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            </div>
            </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="application/javascript">
        invoke_datatable($('#revenuesItems'))
        function printAsset() {
            var element_id = 'assetDatatoPrint', page_title = document.title
            print_element(element_id, page_title)
        }
        function getPrintData(id) {
            $.ajax({
                url: "{{ url('admin/assets_expenses/')}}" +'/'+ id,
                method: 'GET',
                success: function (data) {
                    $("#assetDatatoPrint").html(data.view)
                }
            });
        }
    </script>
@endsection
@section('modals')

    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                <!-- <h4 class="modal-title" id="myModalLabel-1">{{__('Concession')}}</h4> -->
                </div>

                <div class="modal-body" id="assetDatatoPrint">


                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printAsset()" id="print_sales_invoice">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>
@endsection
