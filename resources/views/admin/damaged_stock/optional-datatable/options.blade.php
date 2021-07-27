
@if (isset($withBranch))
    <td class="text-danger column-branch">{!! optional($damagedStock->branch)->name !!}</td>
@endif

@if(isset($withDamageType))
    @if($damagedStock->type == 'natural' )
        <span class="label label-primary wg-label"> {{__('Natural')}} </span>
    @else
        <span class="label label-danger wg-label"> {{__('un_natural')}} </span>
    @endif

@endif


@if (isset($withTotal))
    <span style="background:#F7F8CC !important">
        {{ $damagedStock->total }}
    </span>
@endif


@if (isset($withStatus))
    @if( $damagedStock->concession )

        @if( $damagedStock->concession->status == 'pending' )
            <span class="label label-info wg-label"> {{__('Pending')}}</span>
        @elseif( $damagedStock->concession->status == 'accepted' )
            <span class="label label-success wg-label"> {{__('Accepted')}} </span>
        @elseif( $damagedStock->concession->status == 'rejected' )
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
        <ul class="dropdown-menu  dropdown-wg">
            <li>
                @component('admin.buttons._show_button',[
                              'id' => $damagedStock->id,
                              'route'=>'admin:damaged-stock.show'
                               ])
                @endcomponent

            </li>
            <li>

                @component('admin.buttons._edit_button',[
                        'id'=>$damagedStock->id,
                        'route' => 'admin:damaged-stock.edit',
                         ])
                @endcomponent

            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                        'id'=> $damagedStock->id,
                        'route' => 'admin:damaged-stock.destroy',
                         ])
                @endcomponent
            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                                 'id' => $damagedStock->id,
                                                  'route' => 'admin:damage.stock.deleteSelected',
                                                  ])
    @endcomponent
@endif





