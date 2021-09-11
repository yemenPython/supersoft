<div class="row">
    <div class="">
        @if (authIsSuperAdmin())
            <div class="col-md-12">
                <div class="form-group">
                    <label> {{ __('Branches') }} </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                        <select class="form-control select2" name="branch_id" id="branch_id" onchange="changeBranch()">
                            <option value=""> {{ __('Select Branch') }} </option>
                            @foreach(\App\Models\Branch::all() as $branch)
                                <option
                                    {{request()->has('branch_id') && request()->branch_id == $branch->id? 'selected':''}}
                                    {{ (old('branch_id') == $branch->id) || (isset($item) && $item->branch_id == $branch->id) ? 'selected' : '' }}
                                    value="{{ $branch->id }}"> {{ $branch->name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
        @endif

        <div class="col-md-6">
            <div class="form-group">
                <label> {{ __('Bank Account Type') }} </label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                    <select class="form-control select2" name="main_type_bank_account_id" id="main_type_bank_account_id">
                        <option value=""> {{ __('Select') }} </option>
                        @foreach($mainTypes as $index=>$type)
                            <option value="{{$type->id}}" data-mainType="{{$type->name}}">{{$index + 1}} - {{ $type->name }} </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="col-md-6" style="display: none" id="current_account_type_section">
            <div class="form-group">
                <label> {{ __('Current Account Type') }} </label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-file-text"></i></span>
                    <select class="form-control select2" name="sub_type_bank_account_id" id="sub_type_bank_account_id">
                        <option value=""> {{ __('Select') }} </option>
                            @foreach($subTypes as $type)
                                <option value="{{$type->id}}" data-subType="{{$type->name}}">1. {{$index + 1}} {{ $type->name }} </option>
                            @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="">
       <div class="col-md-12" id="loaderSection" style="display: none">
           <div class="box-loader">
               <p>{{__('Loading')}}</p>
               <div class="loader-31"></div>
           </div>
       </div>
        <div class="col-md-12" id="dataResponse">

        </div>
    </div>
</div>


<div class="form-group col-sm-12">
    @include('admin.buttons._save_buttons')
</div>

@section('js-validation')
    <script>
        function checkBranchValidation() {
            let branch_id = $('#branch_id').find(":selected").val();
            let isSuperAdmin = '{{authIsSuperAdmin()}}';
            if (isSuperAdmin && !branch_id) {
                return true;
            }
            if (!isSuperAdmin && !branch_id) {
                return false;
            }
        }

        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:banks.banks_accounts.create')}}" + "?branch_id=" + branch_id;
        }

        $("#main_type_bank_account_id").change(function () {
            if (checkBranchValidation()) {
                alertWithMsg('{{__('sorry, please select branch first')}}');
                return false;
            }
            let main_bank_account_type = $(this).find(':selected').attr('data-mainType')
            if (!main_bank_account_type) {
                alertWithMsg('{{__('Please Select valid bank account type')}}')
                $("#current_account_type_section").hide();
                return false;
            }
            if (main_bank_account_type == 'حسابات جارية') {
                $("#current_account_type_section").show();
            } else {
                $("#current_account_type_section").hide();
            }
        });


        $("#sub_type_bank_account_id").change(function () {
            if (checkBranchValidation()) {
                alertWithMsg('{{__('sorry, please select branch first')}}');
                return false;
            }
            let branch_id = $('#branch_id').find(":selected").val();
            let sub_type_bank_account = $(this).find(':selected').attr('data-subType')
            if (!sub_type_bank_account) {
                alertWithMsg('{{__('Please Select valid bank account type')}}')
                return false;
            }
            if (sub_type_bank_account == 'حسابات جارية دائنة') {
                $('#loaderSection').show();
                $.ajax({
                    url: "{{ route('admin:banks.banks_accounts.getCreditForm') }}?branch_id=" + branch_id,
                    method: 'GET',
                    success: function (data) {
                        $('#loaderSection').hide();
                        $('#dataResponse').html(data.data);
                        $('.select2').select2();
                    }
                });
            }
        });


        function setBankData() {
            let bank_data_id = $("#bank_data_id").val();
            let bank_branch = $("#bank_data_id").find(':selected').attr('data-bankBranch')
            console.log($("#bank_data_id").find(':selected'), bank_data_id)
            if (!bank_branch) {
                $("#bankBranch").val('Not Fount')
            } else {
                $("#bankBranch").val(bank_branch)
            }
        }
    </script>
@endsection
