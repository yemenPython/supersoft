@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Create '.$status.' Asset') }} </title>
@endsection

@section('style')

@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active">
                    <a href="{{route('admin:stop_and_activate_assets.index')}}">{{ __('Stop And Activate Asset') }}</a></li>
                <li class="breadcrumb-item active"> {{ __('Create '.$status.' Asset') }}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            {{--  box-content-wg-new  --}}

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-text-o"></i>
                    {{ __('Create '.$status.' Asset') }}
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

                <div class="box-content">
                    <form method="post" action="{{route('admin:stop_and_activate_assets.store')}}" class="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('post')

                        @include('admin.stop_and_activate_assets.form')

                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>

                    </form>
                </div>

            </div>
            <!-- /.box-content -->
        </div>
        <!-- /.col-xs-12 -->
    </div>

    <!-- /.row small-spacing -->
@endsection


@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Asset\StopAndActivateAssetRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

@endsection

@section('js')
    <script type="application/javascript">
        function changeBranch() {
            let branch_id = $('#branch_id').find(":selected").val();
            window.location.href = "{{route('admin:stop_and_activate_assets.create')}}" + "?branch_id=" + branch_id + "&status="+ '{{$status}}';
        }
        function checkBranchValidation() {

            let branch_id = $('#branch_id').find(":selected").val();

            let isSuperAdmin = '{{authIsSuperAdmin()}}';

            if (!isSuperAdmin) {
                return true;
            }

            if (branch_id) {
                return true;
            }

            return false;
        }
        $('#assetsGroups').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
            let isSuperAdmin = '{{authIsSuperAdmin()}}';
            if (isSuperAdmin) {
                var branch_id = $('#branch_id').find(":selected").val();
            }else {
                var branch_id = $('#branch_id_hidden').val();
            }
            $.ajax({
                url: "{{ route('admin:stop_and_activate_assets.get_assets_by_asset_group') }}?asset_group_id=" + $(this).val()+"&branch_id="+branch_id+ "&status="+ '{{$status}}',
                method: 'GET',
                success: function (data) {
                    $('#assetsOptions').html(data.assets);
                }
            });
        });
        $('#assetsOptions').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
            if (checkIfAssetExists($(this).val())) {
                swal({text: '{{__('sorry, you have already add this asset before')}}', icon: "warning"});
                return false;
            }
            $.ajax({
                url: "{{ route('admin:stop_and_activate_assets.get_Assets_By_Asset_Id') }}?asset_id=" + $(this).val()+ "&status="+ '{{$status}}',
                method: 'GET',
                success: function (data) {
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;

                    {{--$('#assetsOptions').select2('data', {id: '0', text: '{{__('Select Assets')}}'});--}}
                    swal({text: errors, icon: "error"})
                    $('#assetsOptions').val(null);
                   // $("#assetsOptions").select2("val", "0");

                }
            });
        });

        function checkIfAssetExists(index) {
            var ids = [];
            $('.assetExist').each(function () {
                ids.push($(this).val())
            })
            return ids.includes(index);
        }

        $('#date').on('change', function () {
            if (!checkBranchValidation()) {
                swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
                return false;
            }
        });
    </script>


@endsection
