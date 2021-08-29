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
                                <th scope="col"> {{ __( 'Job') }} </th>
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
                                <th scope="col"> {{ __( 'Job') }} </th>
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
    {!! JsValidator::formRequest('App\Http\Requests\BankOfficialRequest')->selector('#newAssetEmployee-form'); !!}
    <script type="application/javascript">
        $(document).ready(function () {
            $('#add-employee-modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);


                var old_bank_commissioner_id = button.data('old_bank_commissioner_id');
                $('#old_bank_commissioner_id').val(old_bank_commissioner_id);

                var name_ar = button.data('name_ar');
                $('#name_ar').val(name_ar);

                var name_en = button.data('name_en');
                $('#name_en').val(name_en);

                var phone1 = button.data('phone1');
                $('#phone1').val(phone1);

                var phone2 = button.data('phone2');
                $('#phone2').val(phone2);

                var phone3 = button.data('phone3');
                $('#phone3').val(phone3);

                var email = button.data('email');
                $('#email').val(email);

                var job = button.data('job');
                $('#job').val(job);

                var date_from = button.data('date_from');
                $('#date_from').val(date_from);
                var date_to = button.data('date_to');
                $('#date_to').val(date_to);
                var status = button.data('status');
                if (status == 1) {
                    $( "#switch-1" ).prop( "checked", true );
                } else {
                    $( "#switch-1" ).prop( "checked", false);
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
    </script>
@stop
