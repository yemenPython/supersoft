@if(filterSetting())
    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card top-search">
            <h4 class="box-title with-control">
                <i class="fa fa-search"></i>{{__('Search filters')}}
                <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
            </h4>
            <div class="card-content js__card_content">
                <form onsubmit="filterFunction($(this));return false;">
                    <div class="list-inline margin-bottom-0 row">

                        <div class="form-group col-md-4">
                            <label> {{ __('Name') }} </label>
                            {!! drawSelect2ByAjax('assetMaintenacceId','AssetMaintenance', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('Select'),request()->assetMaintenacceId) !!}
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Maintenance Types') }} </label>
                            {!! drawSelect2ByAjax('maintenance_detection_type_id_select2','MaintenanceDetectionType', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('Select'),request()->maintenance_detection_type_id) !!}
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Maintenance Detection') }}</label>
                            {!! drawSelect2ByAjax('maintenance_detection_id','MaintenanceDetection', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('Select'),request()->maintenance_detection_id) !!}
                        </div>

                        <div class="switch primary col-md-1">
                            <input type="checkbox" id="switch-slam" name="active">
                            <label for="switch-slam">{{__('Active')}}</label>
                        </div>
                        <div class="switch primary col-md-2">
                            <input type="checkbox" id="switch-ali" name="inactive">
                            <label for="switch-ali">{{__('inActive')}}</label>
                        </div>

                    </div>

                    @include('admin.btns.btn_search')

                </form>
            </div>
        </div>
    </div>
@endif
