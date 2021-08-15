@if (isset($withStatus))
    @if ($item->status )
        <span class="label label-success wg-label">{{__('Active')}}</span>
    @else
        <span class="label label-danger wg-label">{{__('inActive')}}</span>
    @endif
@endif

@if (isset($withStartData))
    <span style="background:#F7F8CC !important">
    {{ $item->created_at }}
</span>
@endif

@if (isset($withEndData))
    <span style="background:rgb(253, 215, 215) !important">
    {{ $item->updated_at }}
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
                   data-asset_id="{{ $item->asset_id }}"
                   data-asset_maintenance_id="{{ $item->id }}"
                   data-name_ar="{{ $item->name_ar }}"
                   data-name_en="{{ $item->name_en }}"
                   data-maintenance_detection_type_id="{{ $item->maintenance_detection_type_id }}"
                   data-maintenance_detection_id="{{ $item->maintenance_detection_id }}"
                   data-maintenance_type="{{ $item->maintenance_type }}"
                   data-number_of_km_h="{{ $item->number_of_km_h }}"
                   data-period="{{ $item->period }}"

                   data-title="{{__('Edit Asset Maintenance') }}  [{{$item->name}}]"
                   class="btn btn-print-wg text-white">
                    <i class="fa fa-edit"></i>
                    {{__('Edit')}}
                </a>
            </li>
            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                'id'=> $item->id,
                'route' => 'admin:assets_maintenance.destroy',
                 ])
                @endcomponent
            </li>
        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',['id' =>  $item->id,'route' => 'admin:assets_maintenance.delete-Selected',])@endcomponent
@endif

