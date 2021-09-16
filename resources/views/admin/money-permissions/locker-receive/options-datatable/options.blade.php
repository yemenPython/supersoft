@if(isset($withLocker))
    @if($item->source_type == 'locker')
        {!! optional($item->exchange_permission)->permission_number !!}
    @else
        {!! optional($item->bank_exchange_permission)->permission_number !!}
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

            @if($item->status == 'pending')
                <li>
                    @component('admin.buttons._edit_button',[
                   'id' => $item->id,
                   'route' => 'admin:locker-receives.edit',
             ])
                    @endcomponent
                </li>

                <li class="btn-style-drop">
                    @component('admin.buttons._delete_button',[
                      'id' => $item->id,
                      'route' => 'admin:locker-receives.destroy',
                      'tooltip' => __('Delete '.$item->permission_number),
                  ])
                    @endcomponent
                </li>
            @endif

            <li>
                @include('admin.partial.upload_library.btn_upload', ['id'=> $item->id])
            </li>
            <li>
                <a class="btn btn-info"
                   onclick="load_money_permission_model('{{ route('admin:locker-receives.show' ,['id' => $item->id]) }}')">
                    <i class="fa fa-eye"></i> {{ __('Show') }}
                </a>
            </li>

        </ul>
    </div>
@endif


@if (isset($withOptions))
    @if($item->status == 'pending')
        @component('admin.buttons._delete_selected',[
            'id' => $item->id,
            'route' => 'admin:locker-receives.delete_selected',
        ])
        @endcomponent
    @endif
@endif
