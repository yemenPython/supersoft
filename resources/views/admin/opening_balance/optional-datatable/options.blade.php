
@if (isset($withBranch))
     <span class="text-danger">{{ optional($openingBalances->branch )->name}} </span>
@endif

@if(isset($withOperationData))
    <span class="text-danger">{{ $openingBalances->operation_date}} </span>
@endif

@if (isset($withTotal))
    <span style="background:#F7F8CC !important"> {{ $openingBalances->total_money }} </span>
@endif


@if (isset($withStatus))
    @if( $openingBalances->concession )
        @if( $openingBalances->concession->status == 'pending' )
            <span class="label label-info wg-label"> {{__('Pending')}}</span>
        @elseif( $openingBalances->concession->status == 'accepted' )
            <span class="label label-success wg-label"> {{__('Accepted')}} </span>
        @elseif( $openingBalances->concession->status == 'rejected' )
            <span class="label label-danger wg-label"> {{__('Rejected')}} </span>
        @endif
    @else
        <span class="label label-warning wg-label">  {{__('Not determined')}} </span>
    @endif
@endif



@if (isset($withActions))
    <div class="btn-group margin-top-10">
        <button type="button" class="btn btn-options dropdown-toggle"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ico fa fa-bars"></i>
            {{__('Options')}} <span class="caret"></span>
        </button>
        <ul class="dropdown-menu dropdown-wg">
            <li>
                @component('admin.buttons._show_button',[
                           'id' => $openingBalances->id,
                           'route'=>'admin:opening-balance.show'
                            ])
                @endcomponent

            </li>
            <li>

                <a class="btn btn-wg-edit hvr-radial-out"
                   href="{{ route('admin:opening-balance.edit', $openingBalances->id) }}">
                    <i class="fa fa-edit"></i>
                    {{__('Edit')}}
                </a>
            </li>

            <li>
                <a style="cursor:pointer" class="btn btn-print-wg text-white"
                   data-toggle="modal"
                   onclick="getPrintData({{$openingBalances->id}})"
                   data-target="#boostrapModal" title="{{__('print')}}">
                    <i class="fa fa-print"></i> {{__('Print')}}
                </a>
            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
               'id'=> $openingBalances->id,
               'route' => 'admin:opening-balance.destroy',
                ])
                @endcomponent
            </li>

            <li>
                @include('admin.partial.upload_library.btn_upload', ['id'=> $openingBalances->id])
            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                          'id' => $openingBalances->id,
                                          'route' => 'opening-balance.deleteSelected',
                                      ])
    @endcomponent
@endif





