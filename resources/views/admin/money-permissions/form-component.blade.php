<form  onsubmit="filterFunction($(this));return false;">
    <input type="hidden" name="filter" value="1">

    <div class="list-inline margin-bottom-0 row">
        @if(authIsSuperAdmin())
            <div class="col-md-12">
                <div class="form-group has-feedback">
                    <label class="control-label">
                        {{ __('Select Branch') }}
                    </label>
                    <select name="branch_id" class="form-control  js-example-basic-single" id="branch_id">
                        @foreach(\App\Models\Branch::all() as $branch)
                            <option value="{{ $branch->id }}" {{ isset($_GET['branch_id']) && $_GET['branch_id'] == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="form-group col-md-6">
            <label> {{ __('words.money-source') }} </label>
            <select name="source_id" class="form-control js-example-basic-single">
                <option value="">{{__('Select One')}}</option>
                @foreach($source_collection as $source)
                    <option {{ isset($_GET['source_id']) && $source->id == $_GET['source_id'] ? 'selected' : '' }}
                        value="{{ $source->id }}">
                        {{ $source->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-6">
            <label> {{ __('words.money-receiver') }} </label>
            <select name="receiver_id" class="form-control js-example-basic-single">
                <option value="">{{__('Select One')}}</option>
                @foreach($employees as $employee)
                    <option {{ isset($_GET['receiver_id']) && $employee->id == $_GET['receiver_id'] ? 'selected' : '' }}
                        value="{{ $employee->id }}">
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-md-4">
            <label> {{ __('words.permission-status') }} </label>
            <div class="form-check">
                @foreach($statuses as $key => $value)
                    <input class="form-check-input" type="radio" name="permission_status" id="{{ $key }}" value="{{ $key }}"
                        {{ isset($_GET['permission_status']) && $_GET['permission_status'] == $key ? 'checked' : '' }}>
                    <label class="form-check-label" for="{{ $key }}" style="display: inline-block">
                        {{ $value }}
                    </label>
                @endforeach
            </div>
        </div>
        <div class="form-group col-md-4">
            <label>{{__('Date From')}}</label>
            <input type="date" name="date_from" {{ isset($_GET['date_from']) ? $_GET['date_from'] : '' }} class="form-control">
        </div>
        <div class="form-group col-md-4">
            <label>{{__('Date To')}}</label>
            <input type="date" name="date_to" {{ isset($_GET['date_to']) ? $_GET['date_to'] : '' }} class="form-control">
        </div>
    </div>
    @include('admin.btns.btn_search')
</form>
