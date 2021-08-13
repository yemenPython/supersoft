@if(filterSetting())
    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card top-search">
            <h4 class="box-title with-control">
                <i class="fa fa-search"></i>{{__('Search filters')}}
                <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
                <!-- /.controls -->
            </h4>
            <!-- /.box-title -->
            <div class="card-content js__card_content">
                <form onsubmit="filterFunction($(this));return false;">
                    <div class="list-inline margin-bottom-0 row">
                        @if(authIsSuperAdmin())
                            <div class="form-group col-md-4">
                                <label> {{ __('Branch') }} </label>
                                {!! drawSelect2ByAjax('branchId','Branch','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select Branch')) !!}
                            </div>
                        @endif

                        <div class="form-group col-md-4">
                            <label> {{ __('Maintenance centers') }} </label>
                            {!! drawSelect2ByAjax('maintenance_id','MaintenanceCenter','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Select')) !!}
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Phone') }} </label>
                            {!! drawSelect2ByAjax('phone','MaintenanceCenter','phone_1','phone_1',__('Select')) !!}
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Commercial Register') }} </label>
                            {!! drawSelect2ByAjax('commercial_number','MaintenanceCenter','commercial_number','commercial_number',__('Select')) !!}
                        </div>


                        <div class="switch primary col-md-4">
                            <ul class="list-inline">
                                <li>
                                    <input type="checkbox" id="switch-2" name="active" value="1">
                                    <label for="switch-2">{{__('Active')}}</label>
                                </li>
                                <li>
                                    <input type="checkbox" id="switch-3" name="inactive" value="0">
                                    <label for="switch-3">{{__('inActive')}}</label>
                                </li>
                            </ul>
                        </div>
                    </div>

                    @include('admin.btns.btn_search')


                </form>
            </div>
            <!-- /.card-content -->
        </div>
        <!-- /.box-content -->
    </div>
@endif
