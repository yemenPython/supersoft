@if (isset($withBranch))
    <td class="text-danger column-branch">{!! optional($item->branch)->name !!}</td>
@endif

@if (isset($withBalance))
    <span class="text-danger">
        {{ $item->balance }}
    </span>
@endif

@if(isset($withStatus))
    @if ($item->status)
        <span class="label label-success wg-label"> {{__('Active')}} </span>
    @else
        <span class="label label-danger wg-label">  {{__('Inactive')}} </span>
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
                @component('admin.buttons._edit_button',['id' => $item->id, 'route'=>'admin:lockers.edit'])@endcomponent
            </li>
            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                'id'=>$item->id,
                'route' => 'admin:lockers.destroy',
                'tooltip' => __('Delete '.$item->name),
                 ])
                @endcomponent
            </li>
            <li>
                @include('admin.partial.upload_library.btn_upload', ['id'=> $item->id])
            </li>
        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',['id' => $item->id, 'route' => 'admin:lockers.deleteSelected', ])
    @endcomponent
@endif

