
@if (isset($withBranch))
    <span class="text-danger">{{ optional($storeTransfer->branch )->name}} </span>
@endif

@if(isset($withOperationData))
    <span class="text-danger">{{ $storeTransfer->transfer_date}} </span>
@endif


@if (isset($withStoreFrom))
    <span class="label wg-label" style="background:#7165DA !important">
        {!! $lang == 'ar' ? optional($storeTransfer->store_from)->name_ar : optional($storeTransfer->store_from)->name_en !!}
    </span>
@endif


@if (isset($withStoreTo))
    <span class="label wg-label" style="background:#7165DA !important">
        {!! $lang == 'ar' ? optional($storeTransfer->store_to)->name_ar : optional($storeTransfer->store_to)->name_en !!}

    </span>
@endif

@if (isset($withTotal))
    <span style="background:#F7F8CC !important">
        {!! $storeTransfer->total !!}
    </span>
@endif


@if (isset($withStatus))
    @if( $storeTransfer->concession )
        @if( $storeTransfer->concession->status == 'pending' )
            <span class="label label-info wg-label"> {{__('Pending')}}</span>
        @elseif( $storeTransfer->concession->status == 'accepted' )
            <span class="label label-success wg-label"> {{__('Accepted')}} </span>
        @elseif( $storeTransfer->concession->status == 'rejected' )
            <span class="label label-danger wg-label"> {{__('Rejected')}} </span>
        @endif
    @else
        <span class="label label-warning wg-label">  {{__('Not determined')}} </span>
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
                @component('admin.buttons._show_button',[
             'id' => $storeTransfer->id,
             'route'=>'admin:stores-transfers.show'
              ])
                @endcomponent

            </li>
            <li>
                @component('admin.buttons._edit_button',[
            'id' => $storeTransfer->id,
            'route' => 'admin:stores-transfers.edit',
        ])
                @endcomponent

            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                      'id' => $storeTransfer->id,
                      'route' => 'admin:stores-transfers.destroy',
                  ])
                @endcomponent
            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                        'id' => $storeTransfer->id,
                                        'route' => 'admin:stores-transfers.deleteSelected',
                                    ])
    @endcomponent
@endif





