@if (isset($withDate))
    <span class="text-danger">{!! $item->date !!}</span>
@endif

@if (isset($withBranch))
    '<span class="text-danger">{{ optional($item->branch)->name}}</span>
@endif

@if (isset($withTotal))
    <span style="background:#F7F8CC !important">{{ $item->total }}</span>
@endif

@if (isset($different_days))
    <span class="part-unit-span">{{ $item->different_days }}</span>
@endif

@if (isset($remaining_days))
    <span class="price-span">{{ $item->remaining_days }}</span>
@endif



@if (isset($withStatus))
    @if($item->status == 'pending' )
        <span class="label label-info wg-label"> {{__('pending')}}</span>
    @elseif($item->status == 'processing' )
        <span class="label label-warning wg-label"> {{__('processing')}} </span>
    @elseif ($item->status == 'finished' )
        <span class="label label-success wg-label"> {{__('finished')}} </span>
    @endif
@endif

@if (isset($withStatusExecution))
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
                @component('admin.buttons._edit_button',[
                            'id'=>$item->id,
                            'route' => 'admin:sale-supply-orders.edit',
                             ])
                @endcomponent
            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                            'id'=> $item->id,
                            'route' => 'admin:sale-supply-orders.destroy',
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
                <a style="cursor:pointer" href="{{route('admin:sale-supply-orders.show', $item->id)}}"
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

    <form action="{{route('admin:sale.supply.orders.deleteSelected')}}" method="post" id="deleteSelected">
        @csrf

        <div class="checkbox danger">
            <input type="checkbox" name="ids[]" value="{{$item->id}}" class="checkbox-relay-quotation"
                   id="checkbox-{{$item->id}}"

                   data-branch="{{$item->branch_id}}"
                   data-order-number="{{$item->number}}"
                   data-type-for="{{$item->type_for}}"
                   data-salesable-id="{{$item->salesable_id}}"

                   data-can-relay-to-sales-invoice="{{ !$item->salesInvoices->count() && $item->status == 'finished' ? 1:0}}"
            >

            <label for="checkbox-{{$item->id}}"></label>

        </div>

    </form>


@endif
