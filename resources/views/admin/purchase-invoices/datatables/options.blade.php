

@if (isset($type))
    @if ($item->type === "cash")
        <span class="label label-primary wg-label">
            {{__($item->type)}}
        </span>
    @else
        <span class="label label-danger wg-label">
            {{__($item->type)}}
        </span>
    @endif
@endif


@if (isset($total))
    <span style="background:#F7F8CC !important">
        {!! number_format($item->total, 2) !!}
    </span>
@endif

@if (isset($paid))
    <span style="background:#D7FDF9 !important">
        {!! number_format($item->paid, 2) !!}
    </span>
@endif

@if (isset($remaining))
    <span style="background:#FDD7D7 !important">
        {!! number_format($item->remaining ,2)!!}
    </span>
@endif

@if (isset($withStatus))
    @if($item->status == 'pending' )
        <span class="label label-info wg-label"> {{__('processing')}}</span>
    @elseif($item->status == 'accept' )
        <span
            class="label label-success wg-label"> {{__('Accept Approval')}} </span>
    @else
        <span class="label label-danger wg-label">  {{__('Reject Approval')}} </span>
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

                @component('admin.buttons._edit_button',[
                            'id'=>$item->id,
                            'route' => 'admin:purchase-invoices.edit',
                             ])
                @endcomponent
            </li>
            <li class="btn-style-drop">

                @component('admin.buttons._delete_button',[
                            'id'=> $item->id,
                            'route' => 'admin:purchase-invoices.destroy',
                             ])
                @endcomponent
            </li>

            <li>

                @component('admin.purchase-invoices.parts.print',[
                            'id'=> $item->id,
                            'invoice'=> $item,
                             ])
                @endcomponent
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

                <a href="{{route('admin:purchase-invoices.expenses', ['id' => $item->id])}}"
                   class="btn btn-info-wg hvr-radial-out  ">
                    <i class="fa fa-money"></i> {{__('Payments')}}
                </a>
            </li>

            <li>
                <a style="cursor:pointer" href="{{route('admin:purchase.invoices.data.show', $item->id)}}"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Show')}}">
                    <i class="fa fa-eye"></i> {{__('Show')}}
                </a>
            </li>

            <li>

                @if($item->status != 'accept')

                    <a class="btn btn-approval-wg text-white hvr-radial-out">
                        {{__('item status not complete')}}
                    </a>

                @elseif ($item->invoiceReturn)

                    <a class="btn btn-approval-wg text-white hvr-radial-out">
                        {{__('item already returned before')}}
                    </a>

                @elseif ($item->invoice_type != 'normal')

                    <a class="btn btn-approval-wg text-white hvr-radial-out">
                        {{__('item type not direct invoice')}}
                    </a>

                @else
{{--                @if($item->invoice_type == 'normal' && !$item->invoiceReturn && $item->status == 'accept')--}}

                    <a href="{{route('admin:purchase_returns.create', ['invoice' => $item->id, 'branch_id'=> $item->branch_id])}}"
                       class="btn btn-approval-wg text-white hvr-radial-out">
                        <i class="fa fa-eye"></i>
                        {{__('relay to Purchase Return')}}
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
                                                   'route' => 'admin:purchase-invoices.deleteSelected',

                                                    ])
    @endcomponent
@endif
