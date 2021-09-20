@if (isset($withBranch))
    <td class="text-danger column-branch">{!! optional($item->branch)->name !!}</td>
@endif

@if (isset($withTotal))
    <span class="text-danger">
        {{ $item->items->sum('total') }}
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
                <a style="cursor:pointer" class="btn btn-print-wg text-white"
                   data-toggle="modal"
                   onclick="getPrintData('{{route('admin:banks.opening_balance_accounts.show', $item->id)}}')"
                   data-target="#boostrapModal" title="{{__('print')}}">
                    <i class="fa fa-print"></i> {{__('Print')}}
                </a>
            </li>
            <li>
                @component('admin.buttons._edit_button',['id' => $item->id, 'route'=>'admin:banks.opening_balance_accounts.edit'])@endcomponent
            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                'id'=>$item->id,
                'route' => 'admin:banks.opening_balance_accounts.destroy',
                'tooltip' => __('Delete '.$item->name),
                 ])
                @endcomponent
            </li>
        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',['id' => $item->id, 'route' => 'admin:lockers_opening_balance.deleteSelected', ])
    @endcomponent
@endif

