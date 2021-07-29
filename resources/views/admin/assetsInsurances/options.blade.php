
@if (isset($withStatus))
    @if ($item->status )
        <span class="label label-success wg-label">{{__('Active')}}</span>
    @else
        <span class="label label-danger wg-label">{{__('inActive')}}</span>
    @endif
@endif




@if (isset($withActions))
    <div class="btn-group margin-top-10">

        <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ico fa fa-bars"></i>
            {{__('Options')}} <span class="caret"></span>

        </button>
        <ul class="dropdown-menu dropdown-wg">
            <li>
                <a style=" margin-bottom: 12px; border-radius: 5px"
                   type="button"
                   data-toggle="modal" data-target="#add-employee-modal"
                   data-insurance_id="{{ $item->id }}"
                   data-name="{{ $item->insurance_details }}"
                   data-start_date="{{ $item->start_date }}"
                   data-end_date="{{ $item->end_date }}"
                   data-status="{{ $item->status }}"
                   data-title="{{__('Edit asset Insurance')}}"
                   class="btn btn-print-wg text-white">
                    <i class="fa fa-edit"></i>
                    {{__('Edit')}}
                </a>

            </li>
            <li class="btn-style-drop">

                @component('admin.buttons._delete_button',[
                'id'=> $item->id,
                'route' => 'admin:assetsInsurances.destroy',
                 ])
                @endcomponent

            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                              'id' =>  $item->id,
                                              'route' => 'admin:assetsInsurances.deleteSelected',
                                          ])
    @endcomponent
@endif
