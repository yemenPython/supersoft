
@if (isset($withStatus))
    @if ($contact->status )
        <span class="label label-success wg-label">{{__('Active')}}</span>
    @else
        <span class="label label-danger wg-label">{{__('inActive')}}</span>
    @endif
@endif

@if (isset($withStartData))
    <span style="background:#F7F8CC !important">
    {{ $contact->start_date }}
</span>
@endif


@if (isset($withEndData))
    <span style="background:rgb(253, 215, 215) !important">
    {{ $contact->end_date }}
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
                   data-id="{{ $contact->id }}"
                   data-name="{{ $contact->name }}"
                   data-phone1="{{ $contact->phone_1 }}"
                   data-phone2="{{ $contact->phone_2 }}"
                   data-address="{{ $contact->address }}"
                   data-start_date="{{ $contact->start_date }}"
                   data-end_date="{{ $contact->end_date }}"
                   data-email="{{ $contact->email }}"
                   data-job_title="{{ $contact->job_title }}"
                   data-status="{{ $contact->status }}"
                   data-title="{{__('Edit Contact')}}"
                   class="btn btn-print-wg text-white">
                    <i class="fa fa-edit"></i>
                    {{__('Edit')}}
                </a>
            </li>
            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                'id'=> $contact->id,
                'route' => 'admin:suppliers_contacts.destroy',
                 ])
                @endcomponent
            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                              'id' =>  $contact->id,
                                             'route' => 'admin:suppliers_contacts.deleteSelected',
                                         ])
    @endcomponent
@endif





