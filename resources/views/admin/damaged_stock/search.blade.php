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
            <div class="card-content js__card_content" style="padding:20px">
                <form onsubmit="filterFunction($(this));return false;">
                    <ul class="list-inline margin-bottom-0 row">
                        @if (authIsSuperAdmin())
                            <li class="form-group col-md-12">
                                <div class="form-group">
                                    <label> {{ __('Branches') }} </label>
                                    <div class="input-group">

                                        <span class="input-group-addon fa fa-file"></span>
                                        <select class="form-control select2" name="branch_id" id="branchId">
                                            <option value=""> {{ __('Select Branch') }} </option>
                                            @foreach(\App\Models\Branch::all() as $branch)
                                                <option
                                                    {{ isset($_GET['branch']) && $_GET['branch'] == $branch->id ? 'selected' : '' }}
                                                    value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </li>
                        @endif

                        <li class="form-group col-md-3">
                            <label> {{ __('Store Name') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-building-o"></span>
                                {!! drawSelect2ByAjax('store_id','Store','name_'.app()->getLocale(),'name_'.app()->getLocale(),__('Store Name'),request()->store_id) !!}
                            </div>
                        </li>

                        <li class="form-group col-md-3">
                            <label> {{ __('opening-balance.serial-number') }}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-bars"></span>
                                {!! drawSelect2ByAjax('number','DamagedStock','number', 'number', __('opening-balance.serial-number'),request()->number) !!}
                            </div>
                        </li>


                        <li class="form-group col-md-3">
                            <label>{{__('Date From')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" name="dateFrom" class="form-control datepicker">
                            </div>
                        </li>


                        <li class="form-group col-md-3">
                            <label>{{__('Date To')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-calendar"></span>
                                <input type="text" name="dateTo" class="form-control datepicker">
                            </div>
                        </li>


                        <li class="form-group col-md-6">
                            <label>{{__('spart')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-bars"></span>
                                {!! drawSelect2ByAjax('part_name','Part', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->part_name) !!}
                            </div>
                        </li>

                        <li class="form-group col-md-3">
                            <label>{{ __('Barcode') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-bars"></span>
                                <input type="text" name="barcode" class="form-control"
                                       value="{{$_GET['barcode'] ?? null}}">
                            </div>
                        </li>

                        <li class="form-group col-md-3">
                            <label> {{ __('Supplier Barcode') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-bars"></span>
                                <input type="text" name="supplier_barcode" class="form-control"
                                       value="{{$_GET['supplier_barcode'] ?? null}}">
                            </div>
                        </li>

                        <li class="form-group col-md-3">
                            <label> {{ __('Type') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-cubes"></span>
                                <select class="form-control select2" name="partId" id="loadAllParts">
                                    {!! loadPartTypeSelectAsTree() !!}
                                </select>
                            </div>
                        </li>

                        <li class="form-group col-md-3">
                            <label> {{ __('Concession Status') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-cubes"></span>
                                <select class="form-control js-example-basic-single" name="concession_status">
                                    <option value="">{{__('Select Status')}}</option>
                                    <option
                                        value="pending" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'pending' ? 'selected' : '' }}>{{__('Pending')}}</option>
                                    <option
                                        value="accepted" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'accepted' ? 'selected' : '' }}>{{__('Accepted')}}</option>
                                    <option
                                        value="rejected" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'rejected' ? 'selected' : '' }}>{{__('Rejected')}}</option>
                                    <option
                                        value="rejected" {{ isset($_GET['concession_status']) && $_GET['concession_status'] == 'not_found' ? 'selected' : '' }}>{{__('Not determined')}}</option>

                                </select>
                            </div>
                        </li>

                        <li class="form-group col-md-3" style="display: none" id="showEmployeeData">
                            <label>{{__('Employee')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-bars"></span>
                                {!! drawSelect2ByAjax('employee','EmployeeData', 'name_'.app()->getLocale(),'name_'.app()->getLocale(),  __('opening-balance.select-one'),request()->employee) !!}
                            </div>
                        </li>


                        <li class="col-md-6" style="margin-top:20px">
                            <div class="radio primary col-md-4">
                                <input type="radio" name="damage_type" value="natural"
                                       {{isset($_GET['damage_type']) && $_GET['damage_type'] == 'natural' ? 'checked' : ''}} id="natural"
                                       onclick="showEmployeeData('natural')">
                                <label for="natural">{{__('Natural')}}</label>
                            </div>

                            <div class="radio primary col-md-4" style="margin-top: 11px;">
                                <input type="radio" name="damage_type" id="un_natural" value="un_natural"
                                       {{isset($_GET['damage_type']) && $_GET['damage_type'] == 'un_natural' ? 'checked' : ''}} onclick="showEmployeeData('un_natural')">
                                <label for="un_natural">{{__('un_natural')}}</label>
                            </div>

                            <div class="radio primary col-md-3" style="margin-top: 11px;">
                                <input type="radio" name="damage_type" id="both" value="both"
                                       {{isset($_GET['damage_type']) && $_GET['damage_type'] == 'both' ? 'checked' : ''}} onclick="showEmployeeData('both')">
                                <label for="both">{{__('All')}}</label>
                            </div>
                        </li>
                    </ul>
                    @include('admin.btns.btn_search')
                </form>

            </div>
        </div>
    </div>
@endif
