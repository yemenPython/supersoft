@extends('admin.layouts.app')

@section('title')
    <title>{{ __('create assets') }} </title>
@endsection


@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin:assets.index') }}"> {{__('words.assets')}}</a></li>
                <li class="breadcrumb-item active"> {{__('create assets')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i
                        class="fa fa-cubes"></i> {{__('create assets')}}
                    <span class="controls hidden-sm hidden-xs pull-left">
                      <button class="control text-white"
                              style="background:none;border:none;font-size:14px;font-weight:normal !important;">{{__('Save')}}
                      <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                           src="{{asset('assets/images/f1.png')}}">
                  </button>
                        <button class="control text-white"
                                style="background:none;border:none;font-size:14px;font-weight:normal !important;">
                            {{__('Reset')}}
                            <img class="img-fluid" style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                 src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white"
                                    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>
                <div class="box-content for-error-margin-group">
                    <form method="post" action="{{ route('admin:assets.store') }}">
                        @csrf

                        <div class="row">

                            @if (authIsSuperAdmin())
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label> {{ __('Branches') }} </label>
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                            <select class="form-control select2" name="branch_id" id="branch_id">
                                                <option value=""> {{ __('Select Branch') }} </option>
                                                @foreach($branches as $branch)
                                                    <option {{ old('branch_id') == $branch->id ? 'selected' : '' }}
                                                            value="{{ $branch->id }}"> {{ $branch->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
                            @endif

                            <div class="col-md-4 ">
                                <div class="form-group">
                                    <label> {{ __('Assets Groups') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                        <select class="form-control select2" id="asset_group_id"
                                                name="asset_group_id">
                                            <option value="0"> {{ __('Select Group') }} </option>
                                            @if(!authIsSuperAdmin())
                                                @foreach($assetsGroups as $assetGroup)
                                                    <option
                                                        {{ old('assetsGroups_id') == $assetGroup->id ? 'selected' : '' }}
                                                        value="{{ $assetGroup->id }}"
                                                        rate="{{ $assetGroup->annual_consumtion_rate }}"> {{ $assetGroup->name_ar }} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    {{input_error($errors,'asset_group_id')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('Assets Types') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                                        <select class="form-control select2" name="asset_type_id"
                                                id="asset_type_id">
                                            <option value=""> {{ __('Select Type') }} </option>
                                            @if(!authIsSuperAdmin())
                                                @foreach($assetsTypes as $assetType)
                                                    <option
                                                        {{ old('asset_type_id') == $assetType->id ? 'selected' : '' }}
                                                        value="{{ $assetType->id }}"> {{ $assetType->name_ar }} </option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    {{input_error($errors,'asset_type_id')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('Asset Status') }} </label>
                                    <div class="input-group">
                                        <ul class="list-inline">
                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" id="radio_status_1" name="asset_status"
                                                           value="1" checked>
                                                    <label for="radio_status_1">{{ __('continues') }}</label>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="radio info">
                                                    <input id="radio_status_2" disabled type="radio" name="asset_status"
                                                           value="2">
                                                    <label for="radio_status_2">{{ __('sell') }}</label>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" disabled id="radio_status_3" name="asset_status"
                                                           value="3">
                                                    <label for="radio_status_3">{{ __('ignore') }}</label>
                                                </div>
                                            </li>

                                            <li>
                                                <div class="radio info">
                                                    <input type="radio" disabled id="radio_status_3" name="asset_status"
                                                           value="4">
                                                    <label for="radio_status_3">{{ __('stop') }}</label>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('asset name ar') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-cube"></i></span>
                                        <input class="form-control" name="name_ar"/>
                                    </div>
                                    {{input_error($errors,'name_ar')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('asset name en') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-cube"></i></span>
                                        <input class="form-control" name="name_en"/>
                                    </div>
                                    {{input_error($errors,'name_en')}}
                                </div>
                            </div>

                            <div class="col-md-4 type_manual">
                                <div class="form-group">
                                    <label> {{ __('annual consumption rate') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" value="0" name="annual_consumtion_rate" onchange="annual_consumtion_rate_value();"
                                               id="annual_consumtion_rate"/>
                                    </div>
                                    {{input_error($errors,'annual consumption rate')}}
                                </div>
                            </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('asset age') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input class="form-control " id="asset_age" disabled type="text" name="asset_age"/>
                                </div>
                                {{input_error($errors,'asset_age')}}
                            </div>
                        </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('asset purchase date') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="text" name="purchase_date"/>
                                    </div>
                                    {{input_error($errors,'purchase_date')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('cost of purchase') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                        <input class="form-control" id="purchase_cost" onchange="annual_consumtion_rate_value();" type="text" value="0" name="purchase_cost"/>
                                    </div>
                                    {{input_error($errors,'purchase_cost')}}
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label> {{ __('date of work') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input class="form-control datepicker" type="text" name="date_of_work"/>
                                    </div>
                                    {{input_error($errors,'date_of_work')}}
                                </div>
                            </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label> {{ __('Type') }} </label>
                                        <div class="input-group">
                                            <ul class="list-inline">
                                                <li>
                                                    <div class="radio info">
                                                        <input type="radio" id="radio_manual" name="consumption_type"
                                                               value="manual" checked onchange="checkType('manual')">
                                                        <label for="radio_manual">{{ __('Manual') }}</label>
                                                    </div>
                                                </li>

                                                <li>
                                                    <div class="radio info">
                                                        <input id="radio_automatic" type="radio" name="consumption_type"
                                                               value="automatic" onchange="checkType('automatic')">
                                                        <label
                                                            for="radio_automatic">{{ __('Automatic') }}</label>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        {{input_error($errors,'consumption_type')}}
                                    </div>

                                </div>

                                <div class="col-md-12">
                                    <div class="col-md-4 type_automatic" style="display: none">
                                        <div class="form-group has-feedback">
                                            <label for="age_years" class="control-label">{{__('Asset Age (Years)')}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                                <input type="number" name="age_years" step="1" class="form-control"
                                                       pattern="\d+"
                                                       id="age_years" placeholder="{{__('Asset Age (Years)')}}">
                                            </div>
                                            {{input_error($errors,'age_years')}}
                                        </div>
                                    </div>
                                    <div class="col-md-4 type_automatic" style="display: none">
                                        <div class="form-group has-feedback">
                                            <label for="age_months" class="control-label">{{__('Asset Age (Months)')}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                                <input type="number" name="age_months" step="1"  class="form-control"
                                                       pattern="\d+"
                                                       id="age_months" placeholder="{{__('Asset Age (Months)')}}">
                                            </div>
                                            {{input_error($errors,'age_months')}}
                                        </div>
                                    </div>
                                    <div class="col-md-4 type_automatic" style="display: none">
                                        <div class="form-group has-feedback">
                                            <label for="consumption_period"
                                                   class="control-label">{{__('Consumption Period (Months)')}}
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-percent"></i></span>
                                                <input type="number" name="consumption_period" step="1" class="form-control"
                                                       id="consumption_period"
                                                       pattern="\d+"
                                                       placeholder="{{__('Consumption Period (Months)')}}">
                                            </div>
                                            {{input_error($errors,'consumption_period')}}
                                        </div>
                                    </div>
{{--                                    <div class="col-md-6 type_automatic" style="display: none">--}}
{{--                                        <div class="form-group">--}}
{{--                                            <label> {{ __('Type') }} </label>--}}
{{--                                            <div class="input-group">--}}
{{--                                                <ul class="list-inline">--}}
{{--                                                    <li>--}}
{{--                                                        <div class="radio info">--}}
{{--                                                            <input type="radio" id="radio_status_sale" name="consumption_for"--}}
{{--                                                                   value="asset"--}}
{{--                                                                   checked--}}
{{--                                                            >--}}
{{--                                                            <label for="radio_status_sale">{{ __('Assets') }}</label>--}}
{{--                                                        </div>--}}
{{--                                                    </li>--}}

{{--                                                    <li>--}}
{{--                                                        <div class="radio info">--}}
{{--                                                            <input id="radio_status_exclusion" type="radio" name="consumption_for"--}}
{{--                                                                   value="expenses"--}}

{{--                                                            >--}}
{{--                                                            <label--}}
{{--                                                                for="radio_status_exclusion">{{ __('Expenses') }}</label>--}}
{{--                                                        </div>--}}
{{--                                                    </li>--}}
{{--                                                    <li>--}}
{{--                                                        <div class="radio info">--}}
{{--                                                            <input id="radio_status_all" type="radio" name="consumption_for"--}}
{{--                                                                   value='both'--}}

{{--                                                            >--}}
{{--                                                            <label--}}
{{--                                                                for="radio_status_all">{{ __('Both') }}</label>--}}
{{--                                                        </div>--}}
{{--                                                    </li>--}}
{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                            {{input_error($errors,'consumption_for')}}--}}
{{--                                        </div>--}}

{{--                                    </div>--}}
                                </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label> {{ __('Notes') }} </label>
                                    <textarea class="form-control" name="asset_details"
                                              placeholder="{{ __('Notes') }}">{{ old('notes') }}</textarea>
                                </div>
                                {{input_error($errors,'asset_details')}}
                            </div>

                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('previous consumption') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-hashed">#</i></span>
                                    <input class="form-control " type="text" name="past_consumtion"/>
                                </div>
                                {{input_error($errors,'past_consumtion')}}
                            </div>
                        </div> -->

                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('current consumption') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-hashed">#</i></span>
                                    <input class="form-control " type="text" name="current_consumtion"/>
                                </div>
                                {{input_error($errors,'current_consumtion')}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('total current consumption') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-hashed">#</i></span>
                                    <input class="form-control " type="text" name="total_current_consumtion"/>
                                </div>
                                {{input_error($errors,'total_current_consumtion')}}
                            </div>
                        </div> -->

                        <!-- <div class="col-md-4">
                            <div class="form-group">
                                <label> {{ __('book value') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-hashed">#</i></span>
                                    <input class="form-control " type="text" name="booko_value"/>
                                </div>
                                {{input_error($errors,'book_value')}}
                            </div>
                        </div> -->
{{--                                <input type="hidden" name="consumption_type"--}}
{{--                                       value="manaul">--}}


                        </div>

                        @include('admin.buttons._save_buttons')

                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\AssetRequest'); !!}
    <script type="application/javascript">
        $(document).ready(function () {
            $(".select2").select2();
            // $("#asset_group_id").on('change', function () {
            //     $("#annual_consumtion_rate").val($("#asset_group_id option:checked").attr('rate'))
            // });
        });


        $('#branch_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const branch_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsGroupsByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#asset_group_id').each(function () {
                        $(this).html(data.data)
                    });

                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        })
        $('#branch_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const branch_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsTypesByBranchId')}}",
                data: {
                    branch_id: branch_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#asset_type_id').each(function () {
                        $(this).html(data.data)
                    });
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        })

        $('#asset_group_id').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const asset_group_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsGroupsAnnualConsumtionRate')}}",
                data: {
                    asset_group_id: asset_group_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#annual_consumtion_rate').val(data.annual_consumtion_rate);
                    checkType(data.consumption_type);
                    $('#radio_'+data.consumption_type).prop('checked', true);
                    $('#age_years').val(data.age_years);
                    $('#age_months').val(data.age_months);
                    $('#consumption_period').val(data.consumption_period);
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });


        });
        function annual_consumtion_rate_value() {
            var annual_consumtion_rate = $('#annual_consumtion_rate').val();

            var purchase_cost = $('#purchase_cost').val();

            if (annual_consumtion_rate !='' && purchase_cost !=''){

                var asset_age = ( purchase_cost / annual_consumtion_rate) / 100;
                $('#asset_age').val(asset_age);
            }
        }
        function checkType(value) {
            //var value = $("input[name='consumption_type']:checked").val();
            if (value == 'manual') {
                $('.type_automatic').hide();
                $('.type_manual').show();
            } else if (value == 'automatic') {
                $('.type_manual').hide();
                $('.type_automatic').show();
            }
        }

        $(function () {
            $("input[type='radio'][name='consumption_type']").click(function () {
                checkType();
            });
        })
    </script>
@stop
