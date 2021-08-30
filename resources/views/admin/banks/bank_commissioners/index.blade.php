@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Bank Commissioners') }} </title>
@endsection

@include('admin.banks.bank_commissioners.modal')

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:banks.bank_data.index')}}"> {{__('Managing bank accounts')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('Bank Commissioners') }}</li>
            </ol>
        </nav>

      @include('admin.banks.bank_commissioners.search_form')

        <div class="col-xs-12">
            <div class="box-content card bordered-all js__card">
                <h4 class="box-title bg-secondary with-control">
                    <i class="fa fa-cubes"></i>  [{{count($items)}}] {{ __('Bank Commissioners')." : " .$bankData->name }}
                </h4>

                <div class="card-content js__card_content" style="">
                    <ul class="list-inline pull-left top-margin-wg">
                        <li class="list-inline-item">
                            <a style=" margin-bottom: 12px; border-radius: 5px"
                               type="button"
                               data-toggle="modal" data-target="#add-employee-modal"
                               class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
                                {{__('Add new')}}
                                <i class="ico fa fa-plus"></i>

                            </a>
                        </li>
                        <li class="list-inline-item">
                            @component('admin.buttons._confirm_delete_selected',[
                                'route' => 'admin:assets.delete_selected',
                                ])
                            @endcomponent
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                    <div class="table-responsive">
                        <table id="datatable-with-btns" class="table table-bordered wg-table-print table-hover"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th scope="col"> {{ __('#') }} </th>
                                <th scope="col"> {{ __('status') }} </th>
                                <th scope="col"> {{ __( 'Name') }} </th>
                                <th scope="col"> {{ __( 'E-Mail') }} </th>
                                <th scope="col"> {{ __('Contact Phones') }} </th>
                                <th scope="col"> {{ __('start date') }} </th>
                                <th scope="col"> {{ __('end date') }} </th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">
                                    <div class="checkbox danger">
                                        <input type="checkbox" id="select-all">
                                        <label for="select-all"></label>
                                    </div>{!! __('Select') !!}
                                </th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th scope="col"> {{ __('#') }} </th>
                                <th scope="col"> {{ __('status') }} </th>
                                <th scope="col"> {{ __( 'Name') }} </th>
                                <th scope="col"> {{ __( 'E-Mail') }} </th>
                                <th scope="col"> {{ __('Contact Phones') }} </th>
                                <th scope="col"> {{ __('start date') }} </th>
                                <th scope="col"> {{ __('end date') }} </th>
                                <th scope="col">{!! __('Options') !!}</th>
                                <th scope="col">{!! __('Select') !!}</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
@stop

@section('js')
    {!! JsValidator::formRequest('App\Http\Requests\BankCommissionerRequest')->selector('#newAssetEmployee-form'); !!}
    <script type="application/javascript">
        $(document).ready(function () {
            $('#add-employee-modal').on('show.bs.modal', function (event) {
                $('#empId').select2({
                    dropdownParent: $('#add-employee-modal')
                });
                var button = $(event.relatedTarget);
                var old_bank_commissioner_id = button.data('old_bank_commissioner_id');
                $('#old_bank_commissioner_id').val(old_bank_commissioner_id);
                var phone = button.data('phone');
                $('#phone').val(phone);
                var date_from = button.data('date_from');
                $('#date_from').val(date_from);
                var date_to = button.data('date_to');
                $('#date_to').val(date_to);
                var status = button.data('status');
                var employee_id = button.data('employee_id');
                $('#empId').val(employee_id);
                if (status == 1) {
                    $( "#switch-1" ).prop( "checked", true );
                }
                if (status == 0) {
                    $( "#switch-1" ).prop( "checked", false );
                }
                if (employee_id && employee_id != '') {
                    $('#empId').val(employee_id).trigger('change');
                } else {
                    $('#empId').val(0).trigger('change');
                    $("#empId").select2("val", '');
                }
                var title = button.data('title');
                if (title === undefined){
                    $('#myModalLabel-1').text('{{__('Add new asset employee')}}');
                }
                $('#myModalLabel-1').text(title);
            });

            $('#add-employee-modal').on('hide.bs.modal', function (event) {
                $("#newAssetEmployee-form").get(0).reset();
                $(".error-help-block").each(function (index , element) {
                   element.remove();
                })
                $("form#newAssetEmployee-form .form-group").each(function(){
                   $(this).removeClass('has-error');
                });
            });
        });

        server_side_datatable('#datatable-with-btns');
        function filterFunction($this) {
            $("#loaderSearch").show();
            $url = '{{url()->full()}}?&isDataTable=true&' + $this.serialize();
            $datatable.ajax.url($url).load();
            $(".js__card_minus").trigger("click");
            setTimeout( function () {
                $("#loaderSearch").hide();
            }, 1000)
        }

        $('#empId').on('change', function () {
            const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            const employee_id = $(this).val();
            $.ajax({
                type: 'post',
                url: "{{ route('admin:assets.getAssetsEmployeePhone')}}",
                data: {
                    employee_id: employee_id,
                    _token: CSRF_TOKEN,
                },
                success: function (data) {
                    $('#phone').val(data.phone);
                },
                error: function (jqXhr, json, errorThrown) {
                    var errors = jqXhr.responseJSON;
                    swal({text: errors, icon: "error"})
                }
            });
        });
    </script>
@stop
