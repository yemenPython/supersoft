@if (isset($withBranch))
    <span class="text-danger">{{ optional($item->branch)->name }} </span>
@endif


@if (isset($type_bank_account))
    <span>
        {{ optional($item->mainType)->name }}
        @if ($item->subType)
           <strong class="text-danger">[   {{ optional($item->subType)->name }}  ]</strong>
        @endif
    </span>
@endif

{{--@if (isset($withStopDate))--}}
{{--    <span class="text-danger">{!! $item->stop_date ?? '---'!!}</span>--}}
{{--@endif--}}


{{--@if (isset($withName))--}}
{{--{{ $item->name}}--}}
{{--@if ($item->short_name)--}}
{{--    <span class="text-danger"> - [ {!! $item->short_name !!} ]</span>--}}
{{--@endif--}}
{{--@endif--}}

{{--@if (isset($withStatus))--}}
{{--    @if( $item->status )--}}
{{--        <span class="label label-success wg-label"> {{__('Active')}}</span>--}}
{{--    @else--}}
{{--        <span class="label label-danger wg-label">  {{__('inActive')}} </span>--}}
{{--    @endif--}}
{{--@endif--}}

@if (isset($withActions))

    <div class="btn-group margin-top-10">
        <button type="button" class="btn btn-options dropdown-toggle"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ico fa fa-bars"></i>
            {{__('Options')}} <span class="caret"></span>

        </button>
        <ul class="dropdown-menu dropdown-wg">
            <li>
                <a style="cursor:pointer" onclick="loadDataWithModal('{{route('admin:banks.bank_data.show', [$item->id])}}', '#showBankData', '#showBankDataResponse')" data-id="{{$item->id}}"
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
    @component('admin.buttons._delete_selected',['id' => $item->id, 'route' => 'admin:banks.bank_data.deleteSelected',])
    @endcomponent
@endif
