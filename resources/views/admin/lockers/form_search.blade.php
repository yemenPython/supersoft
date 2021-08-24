<form onsubmit="filterFunction($(this));return false;">
    <div class="list-inline margin-bottom-0 row">

        @if(authIsSuperAdmin())
            <div class="form-group col-md-6">
                <label> {{ __('Branch') }} </label>
                {!! drawSelect2ByAjax('branchId','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select'), request()->branchId) !!}
            </div>
        @endif

        <div class="form-group col-md-6">
            <label> {{ __('Locker name') }} </label>
            {!! drawSelect2ByAjax('locker_id','Locker','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select'), request()->locker_id) !!}
        </div>

        <div class="switch primary col-md-2 mb-5">
            <input type="checkbox" id="switch-2" name="active">
            <label for="switch-2">{{__('Active')}}</label>
        </div>

        <div class="switch primary col-md-2 mb-5">
            <input type="checkbox" id="switch-3" name="inactive">
            <label for="switch-3">{{__('inActive')}}</label>
        </div>
    </div>
    @include('admin.btns.btn_search')
</form>
