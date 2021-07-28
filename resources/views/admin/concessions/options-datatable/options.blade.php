@if (isset($withDate))
    <span class="text-danger">{!! $item->date !!}</span>
@endif

@if (isset($withNumber))
    {{ $item->type == 'add' ? $item->add_number : $item->withdrawal_number }}
@endif


@if (isset($withTotal))
    <span style="background:#F7F8CC !important">
        {{$item->total}}
    </span>
@endif


@if(isset($withType))
    @if($item->type == 'add' )
        <span class="label label-primary wg-label"> {{ __('Add Concession') }} </span>
    @else
        <span class="label label-info wg-label"> {{ __('Withdrawal Concession') }} </span>
    @endif
@endif

@if(isset($withConcessionType))
    <span class="label wg-label" style="background: rgb(113, 101, 218) !important;">
        {{ optional($item->concessionType)->name }}
    </span>
@endif




@if (isset($withStatus))
    @if($item->status == 'pending' )
        <span class="label label-info wg-label"> {{__('Pending')}}</span>
    @elseif($item->status == 'accepted' )
        <span class="label label-success wg-label"> {{__('Accepted')}} </span>
    @else
        <span class="label label-danger wg-label"> {{__('Rejected')}} </span>
    @endif
@endif

@if (isset($withConcessionStatus))
    @if($item->concessionExecution)
        @if($item->concessionExecution ->status == 'pending' )
            <span class="label label-info wg-label"> {{__('Processing')}}</span>
        @elseif($item->concessionExecution ->status == 'finished' )
            <span class="label label-success wg-label"> {{__('Finished')}} </span>
        @elseif($item->concessionExecution ->status == 'late' )
            <span class="label label-danger wg-label"> {{__('Late')}} </span>
        @endif
    @else
        <span class="label label-warning wg-label">
            {{__('Not determined')}}
        </span>
    @endif
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

                @component('admin.buttons._edit_button',[
                            'id'=>$item->id,
                            'route' => 'admin:concessions.edit',
                             ])
                @endcomponent

            </li>
            <li class="btn-style-drop">

                @component('admin.buttons._delete_button',[
                            'id'=> $item->id,
                            'route' => 'admin:concessions.destroy',
                             ])
                @endcomponent

            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._add_to_archive',[
                                  'id'=>$item->id,
                                  'route' => 'admin:concessions.archiveData',
                                  'tooltip' => __('Delete '.$item['name']),
                              ])
                @endcomponent
            </li>


            <li>
                <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                   data-toggle="modal"

                   onclick="getPrintData({{$item->id}})"
                   data-target="#boostrapModal" title="{{__('print')}}">
                    <i class="fa fa-print"></i> {{__('Print')}}
                </a>
            </li>


            <li>
                @include('admin.partial.execution_period', ['id'=> $item->id])
            </li>


            <li>
                @include('admin.partial.upload_library.btn_upload', ['id'=> $item->id])

            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
                                                      'id' => $item->id,
                                                       'route' => 'admin:concessions.deleteSelected',
                                                       ])
    @endcomponent
@endif
