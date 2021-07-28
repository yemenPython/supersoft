
@if (isset($withBranch))
    '<span class="text-danger">{{ optional($item->branch)->name}}</span>
@endif


@if (isset($withDate))
    <span class="text-danger">{!! $item->date !!}</span>
@endif

@if (isset($withNumber))
    {{ $item->type == 'add' ? $item->add_number : $item->withdrawal_number }}
@endif


@if (isset($withTotal))
    <span style="background:#F7F8CC !important">
        {{ $item->total }}
    </span>
@endif


@if(isset($withType))
    @if($item->type == 'positive' )
        <span class="label label-primary wg-label"> {{__('Positive')}} </span>
    @else
        <span class="label label-danger wg-label"> {{__('Negative')}} </span>
    @endif
@endif

@if (isset($withStatus))
    @if( $item->concession->status == 'pending' )
        <span class="label label-info wg-label"> {{__('Pending')}}</span>
    @elseif( $item->concession->status == 'accepted' )
        <span class="label label-success wg-label"> {{__('Accepted')}} </span>
    @elseif( $item->concession->status == 'rejected' )
        <span class="label label-danger wg-label"> {{__('Rejected')}} </span>
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
                            'id' => $item->id,
                            'route'=>'admin:settlements.show'
                             ])
                @endcomponent

            </li>
            <li>

                @component('admin.buttons._edit_button',[
                        'id'=>$item->id,
                        'route' => 'admin:settlements.edit',
                         ])
                @endcomponent

            </li>
            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                        'id'=> $item->id,
                        'route' => 'admin:settlements.destroy',
                         ])
                @endcomponent
            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                                 'id' => $item->id,
                                                  'route' => 'admin:settlements.deleteSelected',
                                                  ])
    @endcomponent
@endif
