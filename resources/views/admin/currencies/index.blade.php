@extends('admin.layouts.app')
@section('title')
<title>{{ __('currencies') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item">  {{__('currencies')}}</li>
            </ol>
        </nav>


        <div class="col-xs-12">

            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control"><i class="fa fa-money"></i>   {{__('currencies')}}
                    @if ($setting->active_multi_currency)
                 [ <span class="text-from text-danger">{{__('you can only detect one currency as a Main Currency')}}</span> ]
                @endif
                </h4>


                 <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                    @if ($setting->active_multi_currency)
                       <li class="list-inline-item">

                   

                       @include('admin.buttons.add-new', [
                  'route' => 'admin:currencies.create',
                      'new' => '',
                     ])
                       </li>
                       @endif

                            <li class="list-inline-item">
                                @component('admin.buttons._confirm_delete_selected',[
                                        'route' => 'admin:currencies.deleteSelected',
                                         ])
                                @endcomponent
                            </li>

                    </ul>
                    <div class="clearfix"></div>

                    <div class="table-responsive">
                <table id="currencies" class="table table-bordered wg-table-print table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Name') !!}</th>
                        <th scope="col">{!! __('Symbol') !!}</th>
                        @if ($setting->active_multi_currency)
                            <th scope="col">{!! __('Is Main Currency') !!}</th>
                            <th scope="col">{!! __('Conversion Factor') !!}</th>
                        @endif
                        <th scope="col">{!! __('Status') !!}</th>
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
                        <th scope="col">{!! __('Name') !!}</th>
                        <th scope="col">{!! __('Symbol') !!}</th>
                        @if ($setting->active_multi_currency)
                            <th scope="col">{!! __('Is Main Currency') !!}</th>
                            <th scope="col">{!! __('Conversion Factor') !!}</th>
                        @endif
                        <th scope="col">{!! __('Status') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                        <th scope="col">{!! __('Select') !!}</th>
                    </tr>
                    </tfoot>
                    <tbody>
                    @foreach($currencies as $index=>$currency)
                        <tr>
                            <td>{!! $index +1 !!}</td>
                            <td>{!! $currency->name !!}</td>
                            <td class="text-danger">{!! $currency->symbol !!}</td>
                            @if ($setting->active_multi_currency)
                                <td>
                                    @if ($currency->is_main_currency)
                                        <span class="text-success"><i style="font-size: 20px" class="fa fa-check-circle wg-label"></i></span>
                                    @else
                                        <span class="text-danger"><i style="font-size: 20px" class="fa fa-times"></i></span>
                                    @endif
                                </td>
                                <td>{!! $currency->conversion_factor ?? __('Not determined') !!}</td>
                            @endif
                            <td>
                                @if ($currency->status)
                                    <span class="label label-success wg-label  rounded-0">{{__('Active')}}</span>
                                @else
                                    <span class="label label-danger wg-label  rounded-0">{{__('inActive')}}</span>
                                @endif
                            </td>
                            <td>
                            <div class="btn-group margin-top-10">

                                        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ico fa fa-bars"></i>
                                        {{__('Options')}} <span class="caret"></span>

                                    </button>
                                    <ul class="dropdown-menu dropdown-wg">
                                            <li>

                                            @component('admin.buttons._edit_button',[
                                            'id'=>$currency->id,
                                            'route' => 'admin:currencies.edit',
                                             ])
                                @endcomponent

                                            </li>
                                            <li class="btn-style-drop">

                                            @component('admin.buttons._delete_button',[
                                            'id'=> $currency->id,
                                            'route' => 'admin:currencies.destroy',
                                             ])
                                @endcomponent

                                            </li>

                                        </ul>
                                    </div>

                                <!-- @component('admin.buttons._edit_button',[
                                            'id'=>$currency->id,
                                            'route' => 'admin:currencies.edit',
                                             ])
                                @endcomponent

                                @component('admin.buttons._delete_button',[
                                            'id'=> $currency->id,
                                            'route' => 'admin:currencies.destroy',
                                             ])
                                @endcomponent -->
                            </td>
                            <td>
                            @component('admin.buttons._delete_selected',[
                                      'id' => $currency->id,
                                       'route' => 'admin:currencies.deleteSelected',
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
@endsection
@section('js')
    <script type="application/javascript">
        invoke_datatable($('#currencies'))
    </script>
@endsection
