@if (isset($withBranch))
    <span class="text-danger">{{ optional($item->branch)->name }} </span>
@endif


@if (isset($balance))
    <span class="text-danger">{{ number_format($item->balance, 2) }} </span>
@endif


@if (isset($type_bank_account))
    <span>
        {{ optional($item->mainType)->name }}
        @if ($item->subType)
           <strong class="text-danger">[   {{ optional($item->subType)->name }}  ]</strong>
        @endif
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
                <a style="cursor:pointer" onclick="loadDataWithModal('{{route('admin:banks.banks_accounts.show', [$item->id])}}', '#showBankData', '#showBankDataResponse')" data-id="{{$item->id}}"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Show')}}">
                    <i class="fa fa-eye"></i> {{__('Show')}}
                </a>
            </li>

            <li>
                @component('admin.buttons._edit_button',[
                            'id'=> $item->id,
                            'route' => 'admin:banks.banks_accounts.edit',
                             ])
                @endcomponent
            </li>

            <li>
                @include('admin.partial.upload_library.btn_upload', ['id'=> $item->id])
            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                            'id'=> $item->id,
                            'route' => 'admin:banks.banks_accounts.destroy',
                             ])
                @endcomponent
            </li>
        </ul>
    </div>

@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',['id' => $item->id, 'route' => 'admin:banks.banks_accounts.deleteSelected',])
    @endcomponent
@endif
