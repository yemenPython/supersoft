@if (isset($withStatus))
    @if ($storeEmployee->status )
        <span class="label label-success wg-label">{{__('Active')}}</span>
    @else
        <span class="label label-danger wg-label">{{__('inActive')}}</span>
    @endif
@endif


@if (isset($withStartData))
    <span style="background:#F7F8CC !important">
    {{ $storeEmployee->start }}
</span>
@endif


@if (isset($withEndData))
    <span style="background:rgb(253, 215, 215) !important">
    {{ $storeEmployee->end }}
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
                   data-asset_employee_id="{{ $storeEmployee->id }}"
                   data-employee_id="{{ $storeEmployee->employee_id }}"
                   data-phone="{{ $storeEmployee->employee->phone1 }}"
                   data-start_date="{{ $storeEmployee->start }}"
                   data-end_date="{{ $storeEmployee->end }}"
                   data-status="{{ $storeEmployee->status }}"
                   data-title="{{__('Edit asset employee')}}"
                   class="btn btn-print-wg text-white">
                    <i class="fa fa-edit"></i>
                    {{__('Edit')}}
                </a>
            </li>
            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                'id'=> $storeEmployee->id,
                'route' => 'admin:store_employee_history.destroy',
                 ])
                @endcomponent
            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                              'id' =>  $storeEmployee->id,
                                             'route' => 'admin:store_employee_history.deleteSelected',
                                         ])
    @endcomponent
@endif





