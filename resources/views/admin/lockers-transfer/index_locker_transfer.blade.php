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
                <form  onsubmit="filterFunction($(this));return false;">
                    <input type="hidden" name="filter_with_locker_transfer" value="1">
                    <div class="list-inline margin-bottom-0 row">
                        @if(authIsSuperAdmin())
                            <div class="form-group col-md-12">
                                <label> {{ __('Branch') }} </label>
                                <select name="branch_id" class="form-control js-example-basic-single"
                                        id="branch_id" onchange="getByBranch()">
                                    <option value="">{{__('Select Branch')}}</option>
                                    @foreach($branches as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="form-group col-md-4" id="data_by_branch">
                            <label> {{ __('Locker From') }} </label>
                            <select name="locker_from_id" class="form-control js-example-basic-single">
                                <option value="">{{__('Select Locker')}}</option>
                                @foreach($lockers as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group col-md-4" id="data_by_branch">
                            <label> {{ __('Locker To') }} </label>
                            <select name="locker_to_id" class="form-control js-example-basic-single">
                                <option value="">{{__('Select Locker')}}</option>
                                @foreach($lockers as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>

                        </div>
                        <div class="form-group col-md-4" id="data_by_branch">

                            <label> {{ __('Username') }} </label>
                            <select name="created_by" class="form-control js-example-basic-single">
                                <option value="">{{__('Select User')}}</option>
                                @foreach($users as $k=>$v)
                                    <option value="{{$k}}">{{$v}}</option>
                                @endforeach
                            </select>

                        </div>


                        <div class="form-group col-md-4">
                            <label>{{__('Date From')}}</label>
                            <input type="date" name="date_from" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label>{{__('Date To')}}</label>
                            <input type="date" name="date_to" class="form-control">
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

<div class="col-xs-12">
    <div class="box-content card bordered-all js__card">
        <h4 class="box-title bg-secondary with-control">
            <i class="fa fa-money"></i>   {{__('Lockers Transfer')}}
        </h4>
        <div class="card-content js__card_content" style="">
            <div class="clearfix"></div>
            <div class="table-responsive">
                <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover" style="width:100%">
                    <thead>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Locker From') !!}</th>
                        <th scope="col">{!! __('Locker To') !!}</th>
                        <th scope="col">{!! __('the Amount') !!}</th>
                        <th scope="col">{!! __('Created By') !!}</th>
                        <th scope="col">{!! __('created at') !!}</th>
                        <th scope="col">{!! __('Updated at') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                    </tr>
                    </thead>
                    <tfoot>
                    <tr>
                        <th scope="col">{!! __('#') !!}</th>
                        <th scope="col">{!! __('Locker From') !!}</th>
                        <th scope="col">{!! __('Locker To') !!}</th>
                        <th scope="col">{!! __('the Amount') !!}</th>
                        <th scope="col">{!! __('Created By') !!}</th>
                        <th scope="col">{!! __('created at') !!}</th>
                        <th scope="col">{!! __('Updated at') !!}</th>
                        <th scope="col">{!! __('Options') !!}</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
