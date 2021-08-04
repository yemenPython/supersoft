@extends('admin.layouts.app')

@section('title')
    <title> {{ __('Show Purchase Request') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:purchase-requests.index')}}"> {{__('Purchase Requests')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Show Purchase Request')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-gear"></i>
                    {{__('Show Purchase Request')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                          {{__('Save')}}
                          <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                               src="{{asset('assets/images/f1.png')}}">
                        </button>
                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}">
                        </button>

                        <button class="control text-white" style="background:none;border:none;font-size:12px">
                                {{__('Back')}}
                                <img class="img-fluid"
                                     style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px"
                                     src="{{asset('assets/images/f3.png')}}">
                            </button>
						</span>
                </h4>

                <div class="box-content">
                    @include('admin.purchase_requests.show_form')
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>

    <!-- /.row small-spacing -->
@endsection

@section('js-validation')

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('modals')

    @foreach ($purchaseRequest->items as $index => $item)
        @php
            $index +=1;
            $part = $item->part;
        @endphp

        <div class="modal fade" id="part_types_{{$index}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content wg-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel-1">{{__('Types')}}</h4>
                    </div>

                    <div class="modal-body">

                        <table id="" class="table table-striped table-bordered display" style="width:100%">
                            <thead>
                            <tr class="text-center-inputs">
                                <th scope="col">{!! __('Select') !!}</th>
                                <th scope="col">{!! __('Type') !!}</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr class="text-center-inputs">
                                <th scope="col">{!! __('Select') !!}</th>
                                <th scope="col">{!! __('Type') !!}</th>
                            </tr>
                            </tfoot>
                            <tbody>

                            @if(!empty(partTypes($part)))

                                @foreach(partTypes($part) as $key=>$value)
                                    <tr class="text-center-inputs">
                                        <td>
                                            <div class="checkbox">
                                                <input type="checkbox" id="item_type_checkbox_{{$index}}_{{$key}}"
                                                       disabled
                                                       onclick="selectItemType('{{$index}}', '{{$key}}')"
                                                    {{isset($item) && in_array($key, $item->spareParts->pluck('id')->toArray()) ? 'checked':''}}
                                                >
                                                <label for="item_type_checkbox_{{$index}}_{{$key}}"></label>
                                            </div>
                                        </td>
                                        <td>{!! $value !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="text-center-inputs">
                                    <td colspan="2">
                                        <span>{{__('No Types')}}</span>
                                    </td>
                                </tr>
                            @endif

                            </tbody>
                        </table>

                    </div>

                    <div class="modal-footer">

                        <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                                data-dismiss="modal">
                            {{__('Close')}}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection
