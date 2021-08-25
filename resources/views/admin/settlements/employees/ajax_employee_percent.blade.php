<div class="col-md-12" id="employee_{{$employeeIndex}}"
     style="margin:10px auto;box-shadow:0 0 7px 1px #DDD;padding:20px">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputDescription" class="control-label">{{__('Employees')}}</label>
                <div class="input-group">
                    <select class="form-control js-example-basic-single" name="employees[]">
                        <option value="">{{__('Select Employee')}}</option>
                        @foreach($employees as $employee)
                            <option
                                value="{{$employee->id}}" {{isset($settlementEmployee) && $settlementEmployee->id == $employee->id ? 'selected':''}}>
                                {{$employee->name}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="form-group">

                @if(isset($settlement))

                    <button type="button" class="btn btn-danger fa fa-trash" style="margin-top: 30px;"
                            onclick="removeOldEmployee('{{$settlementEmployee->id}}' , '{{$employeeIndex}}')">
                    </button>
                @else
                    <button type="button" class="btn btn-danger fa fa-trash" style="margin-top: 30px;"
                            onclick="removeEmployee('{{$employeeIndex}}')">
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>



