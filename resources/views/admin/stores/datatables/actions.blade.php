<div class="btn-group margin-top-10">

    <button type="button" class="btn btn-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="ico fa fa-bars"></i>
        {{__('Options')}} <span class="caret"></span>

    </button>
    <ul class="dropdown-menu dropdown-wg">
        <li>


            <a class="btn btn-wg-show hvr-radial-out" onclick="loadDataWithModal('{{$store->id}}')" data-id="{{$store->id}}">
                <i class="fa fa-eye"></i> {{__('Show')}}
            </a>

        </li>
        <li>

            @component('admin.buttons._edit_button',[
                    'id'=>$store->id,
                    'route' => 'admin:stores.edit',
                     ])
            @endcomponent
        </li>

        <li class="btn-style-drop">
            @component('admin.buttons._delete_button',[
                    'id'=> $store->id,
                    'route' => 'admin:stores.destroy',
                     ])
            @endcomponent
        </li>
        <li>
            <a class="btn btn-wg-show hvr-radial-out" target="_blank"
               href="{{route('admin:store_employee_history.index', ['store' => $store->id])}}" >
                <i class="fa fa-eye"></i>{{ __( 'employees history' )}}</a>

        </li>

    </ul>
</div>
