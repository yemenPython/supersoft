

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
                            'route' => 'admin:purchase_returns.edit',
                             ])
                @endcomponent
            </li>
            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                            'id'=> $item->id,
                            'route' => 'admin:purchase_returns.destroy',
                             ])
                @endcomponent
            </li>
            <li>

                @component('admin.purchase_returns.parts.print',[
                   'id'=> $item->id,
                   'invoice'=> $item,
                  ])
                @endcomponent
            </li>

            <li>
                <a style="cursor:pointer" href="{{route('admin:purchase.returns.data.show', $item->id)}}"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Show')}}">
                    <i class="fa fa-eye"></i> {{__('Show')}}
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
        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                                  'id' => $item->id,
                                                  'route' => 'admin:purchase_returns.deleteSelected',
                                                   ])
    @endcomponent
@endif
