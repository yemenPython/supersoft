<div class="modal fade" id="add-employee-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33">
    <div class="modal-dialog" role="document">
        <div class="modal-content wg-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel-1">{{__('Bank Officials')}}</h4>
            </div>

            <form id="newAssetEmployee-form" method="post" action="{{ route('admin:banks.bank_commissioners.store') }}">
                <div class="modal-body">
                    <div class="row">
                        @csrf
                        <input type="hidden" value="{{$bankData->id}}" name="bank_data_id" id="bank_data_id">
                        <input type="hidden" value="" name="old_bank_commissioner_id" id="old_bank_commissioner_id">

                        <div class="form-group col-md-12">
                            <label>{{ __('name') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-user"></span>
                                <select class="form-control select2" name="employee_id" id="empId">
                                    <option value="0"> {{ __('Select Employee') }} </option>
                                    @foreach($employeesData as $employee)
                                        <option
                                            {{ old('employee_id') == $employee->id ? 'selected' : '' }}
                                            value="{{ $employee->id }}"> {{ $employee->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-12">
                            <label> {{ __('phone') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-phone"></span>
                                <input type="text" name="phone" id="phone" class="form-control" disabled>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('words.date-from') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="date_from" id="date_from" value="{{now()}}"
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('words.date-to') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="date_to" id="date_to" value="{{now()}}"
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="control-label">{{__('Status')}}</label>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="switch-1" name="status" value="1" checked>
                                <label for="switch-1">{{__('Active')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        {{__('save')}}
                    </button>
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

