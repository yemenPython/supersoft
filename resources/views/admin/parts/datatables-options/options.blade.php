
@if (isset($withPartImage))
    <span style="cursor: pointer" data-toggle="modal" data-target="#part_img" title="Part image" id="part_id_{{$part->id}}"
          onclick="getPartImage('{{$part->id}}')"
          data-img="{{$part->image}}">
        {!! $part->name !!}
    </span>
@endif


@if (isset($withSparePart))
    <span class="text-danger">
        {{$part->spareParts->first() ? $part->spareParts->first()->type : '---'}}
    </span>
@endif


@if (isset($withQ))
    <a data-toggle="modal" data-target="#part_quantity_{{$part->id}}"
       title="Part" class="btn btn-info">
        {!! $part->quantity !!}
    </a>
@endif


@if (isset($withStatus))
    @if($part->status == 1 )
        <span class="label label-success wg-label"> {{ __('Active') }} </span>
    @else
        <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
    @endif
@endif

@if (isset($witReviewable))
    @if($part->reviewable == 1 )
        <span class="label label-success wg-label"> {{ __('Reviewed') }} </span>
    @else
        <span class="label label-danger wg-label"> {{ __('Not reviewed') }} </span>
    @endif
@endif

@if (isset($witTaxable))
    @if($part->taxable == 1 )
        <span class="label label-success wg-label"> {{ __('Active') }} </span>
    @else
        <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
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

                @component('admin.buttons._show_button',[
                           'id' => $part->id,
                           'route'=>'admin:parts.show'
                            ])
                @endcomponent


            </li>
            <li>
                @component('admin.buttons._edit_button',[
                        'id' => $part->id,
                        'route'=>'admin:parts.edit'
                         ])
                @endcomponent

            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                        'id'=>$part->id,
                        'route' => 'admin:parts.destroy',
                        'tooltip' => __('Delete '.$part['name']),
                         ])
                @endcomponent
            </li>

            <li>
                <a data-toggle="modal" data-target="#boostrapModal-2"
                   onclick="getLibraryPartId('{{$part->id}}')"
                   title="Part Library" class="btn btn-warning"
                   style="margin-bottom:5px">
                    <i class="fa fa-plus"> </i> {{__('Library')}}
                </a>
            </li>

            <li>
                @include('admin.parts.alternative_parts')
            </li>

            <li>
                <a data-toggle="modal" data-target="#part_taxes_{{$part->id}}"
                   onclick="partTaxable('{{$part->id}}')"
                   title="Part taxes" class="btn btn-info">
                    <i class="fa fa-money"> </i> {{__('Taxes')}}
                </a>

            </li>

        </ul>
    </div>
@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',[
    'id' => $part->id,
   'route' => 'admin:parts.deleteSelected',
  ])
    @endcomponent
@endif





