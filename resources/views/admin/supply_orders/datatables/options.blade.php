
@if (isset($withBranch))
    '<span class="text-danger">{{ optional($item->branch)->name}}</span>
@endif

@if (isset($withSupplier))
    <span class="text-danger">{{ optional($item->supplier)->name}}</span>
@endif


@if (isset($withDate))
    <span class="text-danger">{!! $item->date !!}</span>
@endif


@if (isset($total))
    <span style="background:#F7F8CC !important">{{ __($item->total) }}</span>
@endif

@if (isset($different_days))
    <span class="part-unit-span">{{ $item->different_days }} </span>
@endif

@if (isset($remaining_days))
    <span class="price-span">{{ $item->remaining_days }} </span>
@endif

@if (isset($withStatus))
    @if($item->status == 'pending' )
        <span class="label label-info wg-label"> {{__('processing')}}</span>
    @elseif($item->status == 'accept' )
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
        <span class="label label-warning wg-label">{{__('Not determined')}}</span>
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
                @component('admin.buttons._edit_button',[
                            'id'=>$item->id,
                            'route' => 'admin:supply-orders.edit',
                             ])
                @endcomponent
            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                            'id'=> $item->id,
                            'route' => 'admin:supply-orders.destroy',
                             ])
                @endcomponent
            </li>

            <li>
                <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                   data-toggle="modal"
                   onclick="getPrintData({{$item->id}})"
                   data-target="#boostrapModal" title="{{__('print')}}">
                    <i class="fa fa-print"></i> {{__('Print')}}
                </a>
            </li>

            <li>
                <a style="cursor:pointer"
                   class="btn btn-terms-wg text-white hvr-radial-out"
                   data-toggle="modal" data-target="#terms_{{$item->id}}"
                   title="{{__('Terms')}}">
                    <i class="fa fa-check-circle"></i> {{__('Terms')}}
                </a>
            </li>

            <li>
                <a style="cursor:pointer" href="{{route('admin:supply-orders.show', $item->id)}}"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Show')}}">
                    <i class="fa fa-eye"></i> {{__('Show')}}
                </a>
            </li>

            <li>
                @if($item->status == 'accept')

                    <a href="{{route('admin:purchase-receipts.create', ['supply_order' => $item->id, 'branch_id'=> $item->branch_id, ])}}"
                       class="btn btn-approval-wg text-white hvr-radial-out">
                        <i class="fafa-eye"></i>
                        {{__('relay to Purchase Receipt')}}
                    </a>

                @else
                    <a class="btn btn-approval-wg text-white hvr-radial-out">
                        {{__('item status not accept')}}
                    </a>
                @endif
            </li>

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
                                                  'route' => 'admin:supply-orders.deleteSelected',
                                                  ])
    @endcomponent
@endif
