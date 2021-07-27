
@if (isset($withBranch))
    '<span class="text-danger">{{ optional($item->branch)->name}}</span>
@endif


@if (isset($withDate))
    <span class="text-danger">{!! $item->date !!}</span>
@endif

@if (isset($withDifferentDays))
    <span class="part-unit-span">{{ $item->different_days }} </span>
@endif

@if (isset($remaining_days))
    <span class="price-span">{{ $item->remaining_days }} </span>
@endif

@if (isset($withStatus))
    @if($item->status == 'under_processing' )
        <span class="label label-info wg-label"> {{__('Under Processing')}}</span>
    @elseif($item->status == 'ready_for_approval' )
        <span
            class="label label-primary wg-label"> {{__('Ready For Approval')}} </span>
    @elseif($item->status == 'accept_approval' )
        <span
            class="label label-success wg-label"> {{__('Accept Approval')}} </span>
    @else
        <span class="label label-danger wg-label"> {{__('Reject Approval')}} </span>
    @endif
@endif



@if (isset($executionStatus))
    @if($item->execution)


        @if($item->execution ->status == 'pending' )
            <span class="label label-info wg-label"> {{__('Processing')}}</span>

        @elseif($item->execution ->status == 'finished' )
            <span class="label label-success wg-label"> {{__('Finished')}} </span>

        @elseif($item->execution ->status == 'late' )
            <span class="label label-danger wg-label"> {{__('Late')}} </span>
        @endif


    @else
        <span class="label label-warning wg-label">
{{__('Not determined')}}
</span>

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
                <a style="cursor:pointer" class="btn btn-print-wg text-white"
                   data-toggle="modal"
                   onclick="getPrintData({{$item->id}})"
                   data-target="#boostrapModal" title="{{__('print')}}">
                    <i class="fa fa-print"></i> {{__('Print')}}
                </a>

            </li>

            <li>
                <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                   data-toggle="modal"
                   onclick="shortPrintData({{$item->id}})"
                   data-target="#boostrapModal" title="{{__('Short Print')}}">
                    <i class="fa fa-print"></i> {{__('Short Print')}}
                </a>

            </li>
            <li>

                @component('admin.buttons._edit_button',[
                        'id'=>$item->id,
                        'route' => 'admin:purchase-requests.edit',
                         ])
                @endcomponent
            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                        'id'=> $item->id,
                        'route' => 'admin:purchase-requests.destroy',
                         ])
                @endcomponent
            </li>

            @if($item->status == 'ready_for_approval')
                <li>


                    <a href="{{route('admin:purchase-requests.edit', ['id'=> $item->id, 'request_type'=>'approval'])}}"
                       class="btn btn-approval-wg text-white hvr-radial-out">
                        <i class="fa fa-check"></i>
                        {{__('Approval')}}
                    </a>

                </li>
            @endif


            <li>
                @include('admin.partial.execution_period', ['id'=> $item->id])
            </li>

            <li>
                @include('admin.partial.upload_library.btn_upload', ['id'=> $item->id])
            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                                 'id' => $item->id,
                                                  'route' => 'admin:purchase-requests.deleteSelected',
                                                  ])
    @endcomponent
@endif
