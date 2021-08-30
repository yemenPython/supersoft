@if (isset($withBranch))
    <td class="text-danger column-branch">{!! optional($item->branch)->name !!}</td>
@endif

@if (isset($withTotal))
    <span class="text-danger">
        {{ $item->added_total }}
    </span>
@endif

@if(isset($withStatus))
    @if ($item->status == \App\Enum\Status::Progress)
        <span class="label label-warning wg-label"> {{__('Progress')}} </span>
    @endif
    @if ($item->status == \App\Enum\Status::Accepted)
        <span class="label label-success wg-label">  {{__('Accept')}} </span>
    @endif
    @if ($item->status == \App\Enum\Status::Rejected)
        <span class="label label-danger wg-label">  {{__('Reject')}} </span>
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
                <a style="cursor:pointer" class="btn btn-print-wg text-white"
                   data-toggle="modal"
                   onclick="getPrintData('{{route('admin:lockers_opening_balance.show', $item->id)}}')"
                   data-target="#boostrapModal" title="{{__('print')}}">
                    <i class="fa fa-print"></i> {{__('Print')}}
                </a>
            </li>
            <li>
                @component('admin.buttons._edit_button',['id' => $item->id, 'route'=>'admin:lockers_opening_balance.edit'])@endcomponent
            </li>
            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                'id'=>$item->id,
                'route' => 'admin:lockers_opening_balance.destroy',
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

