@if (isset($withDate))
    <span class="text-danger">{!! $item->date !!}</span>
@endif


@if (isset($withName))
{{ $item->name}}
@if ($item->short_name)
    <span class="text-danger"> - [ {!! $item->short_name !!} ]</span>
@endif
@endif

@if (isset($withStatus))
    @if( $item->status )
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
            <li>
                @if ($item->status)
                    <a class="btn btn-wg-edit hvr-radial-out" onclick="confirmAction('{{route('admin:banks.bank_data.StartDealing', [$item->id])}}' ,'{{__('Are you Sure To Stop Dealing')}}')">
                        <i class="fa fa-ban text-danger"></i>  {{__('Stop Dealing')}}
                    </a>
                @else
                    <a class="btn btn-wg-edit hvr-radial-out" onclick="confirmAction('{{route('admin:banks.bank_data.StartDealing', [$item->id])}}', '{{__('Are you Sure To Start Dealing')}}')">
                        <i class="fa fa-check text-success"></i>  {{__('Start Dealing')}}
                    </a>
                @endif
            </li>
            <li>
                @component('admin.buttons._edit_button',[
                            'id'=> $item->id,
                            'route' => 'admin:banks.bank_data.edit',
                             ])
                @endcomponent
            </li>

            <li>
                <a style="cursor:pointer" onclick="loadDataWithModal('{{route('admin:banks.branch_product.show', [$item->id])}}', '#showProducts', '#showProductsResponse')" data-id="{{$item->id}}"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('branch Products')}}">
                    <i class="fa fa-product-hunt"></i> {{__('branch products')}}
                </a>
            </li>

            <li>

                <a style="cursor:pointer" onclick="loadDataWithModal('{{route('admin:banks.bank_data.show', [$item->id])}}', '#showBankData', '#showBankDataResponse')" data-id="{{$item->id}}"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Show')}}">
                    <i class="fa fa-eye"></i> {{__('Show')}}
                </a>
            </li>


            <li>
                @include('admin.partial.upload_library.btn_upload', ['id'=> $item->id])
            </li>

            <li>
                <a style="cursor:pointer" onclick="OpenLocation('{{$item->lat}}', '{{$item->long}}')"
                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Location')}}">
                    <i class="fa fa-location-arrow"></i> {{__('Location')}}
                </a>
            </li>

            <li class="btn-style-drop">
                @component('admin.buttons._delete_button',[
                            'id'=> $item->id,
                            'route' => 'admin:banks.bank_data.destroy',
                             ])
                @endcomponent
            </li>
        </ul>
    </div>

@endif

@if (isset($withOptions))
    @component('admin.buttons._delete_selected',['id' => $item->id, 'route' => 'admin:banks.bank_data.deleteSelected',])
    @endcomponent
@endif
