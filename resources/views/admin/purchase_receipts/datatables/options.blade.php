@if (isset($withBranch))
    '<span class="text-danger">{{ optional($item->branch)->name}}</span>
@endif

@if (isset($withSupplier))
    <span class="text-danger">{{ $item->supplier ? $item->supplier->name : '' }}</span>
@endif

@if (isset($withDate))
    <span class="text-danger">{!! $item->date !!}</span>
@endif


@if (isset($total))
    <span style="background:#F7F8CC !important">{{ __($item->total) }}</span>
@endif

@if (isset($total_accepted))
    <span style="background:#D7FDF9 !important">
        {{$item->total_accepted}}
    </span>
@endif

@if (isset($total_rejected))
    <span style="background:#FDD7D7 !important">
        {{$item->total_rejected}}
    </span>
@endif



@if (isset($withStatus))
    @if( $item->concession )

        @if( $item->concession->status == 'pending' )
            <span class="label label-info wg-label"> {{__('Pending')}}</span>
        @elseif( $item->concession->status == 'accepted' )
            <span class="label label-success wg-label"> {{__('Accepted')}} </span>
        @elseif( $item->concession->status == 'rejected' )
            <span class="label label-danger wg-label"> {{__('Rejected')}} </span>
        @endif

    @else
        <span
            class="label label-warning wg-label">  {{__('Not determined')}} </span>
    @endif
@endif



@if (isset($executionStatus))
    @if($item->execution)

        @if($item->execution->status == 'pending' )
            <span class="label label-info wg-label"> {{__('Processing')}}</span>

        @elseif($item->execution->status == 'finished' )
            <span class="label label-success wg-label"> {{__('Finished')}} </span>

        @elseif($item->execution->status == 'late' )
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
                @component('admin.buttons._edit_button',[
                            'id'=>$item->id,
                            'route' => 'admin:purchase-receipts.edit',
                             ])
                @endcomponent
            </li>
            <li class="btn-style-drop">

                @component('admin.buttons._delete_button',[
                            'id'=> $item->id,
                            'route' => 'admin:purchase-receipts.destroy',
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
                <a style="cursor:pointer" href="{{route('admin:purchase.receipts.data.show', $item->id)}}"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Show')}}">
                    <i class="fa fa-eye"></i> {{__('Show')}}
                </a>
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
                                                   'route' => 'admin:purchase-receipts.deleteSelected',
                                                   ])
    @endcomponent
@endif


@if (isset($withRelayToPurchaseInvoice))

    <div class="checkbox danger"
         style="{{!$item->purchaseInvoices->count() && $item->concession && $item->concession->status == 'accepted' ? '':'display:none;' }}">

        <input type="checkbox" class="checkbox-relay-quotation" value="{{$item->id}}" id="relay-{{$item->id}}"
               data-branch="{{$item->branch_id}}"
               data-supply-order="{{$item->supply_order_id}}"
        >

        <label for="relay-{{$item->id}}"></label>
    </div>

@endif

@if (isset($withRelayToPurchaseReturn))

    @if($item->concession && $item->concession->status == 'accepted')

        <div class="checkbox danger">

            <input type="checkbox" class="checkbox-relay-return" value="{{$item->id}}" id="relay-return-{{$item->id}}"
                   data-branch="{{$item->branch_id}}"
                   data-supply-order="{{$item->supply_order_id}}"
                   data-supplier-id="{{$item->supplier_id}}"
            >

            <label for="relay-return-{{$item->id}}"></label>
        </div>
    @endif

@endif
