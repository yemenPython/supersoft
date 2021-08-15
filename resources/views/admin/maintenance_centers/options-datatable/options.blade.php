@if (isset($branch))
    <span class="text-danger">
      {{ optional($item->branch)->name }}
    </span>
@endif

@if (isset($withStatus))
    @if($item->status == '1' )
        <span class="label label-success wg-label"> {{__('Active')}}</span>
    @else
        <span class="label label-danger wg-label">  {{__('inActive')}} </span>
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
            <input type="hidden" id="latVal" value="{{$item->lat}}">
            <input type="hidden" id="longVal" value="{{$item->long}}">
            <li>
                <a style="cursor:pointer" onclick="OpenLocation('{{$item->lat}}', '{{$item->long}}')"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Location')}}">
                    <i class="fa fa-location-arrow"></i> {{__('Location')}}
                </a>
            </li>
            <li>
                <a style="cursor:pointer" onclick="openModalToShow('{{$item->id}}')"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Show')}}">
                    <i class="fa fa-eye"></i> {{__('Show')}}
                </a>
            </li>

            <li>

                @component('admin.buttons._edit_button',[
                            'id'=>$item->id,
                            'route' => 'admin:maintenance_centers.edit',
                             ])
                @endcomponent
            </li>
            <li class="btn-style-drop">

                @component('admin.buttons._delete_button',[
                            'id'=> $item->id,
                            'route' => 'admin:maintenance_centers.destroy',
                             ])
                @endcomponent
            </li>
        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                                   'id' => $item->id,
                                                   'route' => 'admin:maintenance_centers.deleteSelected',

                                                    ])
    @endcomponent
@endif
