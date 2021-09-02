@if (isset($withStatus))
    @if ($item->status )
        <span class="label label-success wg-label">{{__('Active')}}</span>
    @else
        <span class="label label-danger wg-label">{{__('inActive')}}</span>
    @endif
@endif

@if (isset($withPhones))
    @if(optional($item->employee)->phone1)
        <p style="border: 1px solid red;">{{ optional($item->employee)->phone1 }}</p>
    @endif
    @if(optional($item->employee)->phone2)
        <p style="border: 1px solid red;">{{ optional($item->employee)->phone2 }}</p>
    @endif
@endif

@if (isset($withStartData))
    <span style="background:#F7F8CC !important">
    {{ $item->date_from }}
</span>
@endif


@if (isset($withEndData))
    <span style="background:rgb(253, 215, 215) !important">
    {{ $item->date_to }}
</span>
@endif





@if (isset($withActions))
    <div class="btn-group margin-top-10">

        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="false">
            <i class="ico fa fa-bars"></i>
            {{__('Options')}} <span class="caret"></span>

        </button>
        <ul class="dropdown-menu dropdown-wg">
            <li>
                <a style=" margin-bottom: 12px; border-radius: 5px"
                   type="button"
                   data-toggle="modal" data-target="#add-employee-modal"
                   data-old_bank_commissioner_id="{{ $item->id }}"
                   data-employee_id="{{ optional($item->employee)->id }}"
                   data-phone="{{ optional($item->employee)->phone1 }}"
                   data-date_from="{{ $item->date_from }}"
                   data-date_to="{{ $item->date_to }}"
                   data-status="{{ $item->status }}"
                   data-title="{{__('Edit Bank Commissioner')}} - [{{ optional($item->employee)->name }}]"
                   class="btn btn-print-wg text-white">
                    <i class="fa fa-edit"></i>
                    {{__('Edit')}}
                </a>
            </li>
            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                'id'=> $item->id,
                'route' => 'admin:banks.bank_commissioners.destroy',
                 ])
                @endcomponent
            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected', [
                  'id' =>  $item->id,
                 'route' => 'admin:banks.bank_commissioners.deleteSelected',
     ])
    @endcomponent
@endif



