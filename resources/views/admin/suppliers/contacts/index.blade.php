@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Contacts') }} </title>
@endsection

<!-- Modal -->
<div class="modal fade" id="add-employee-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33">
    <div class="modal-dialog" role="document">
        <div class="modal-content wg-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel-1">{{__('Contacts')}}</h4>
            </div>

            <form id="newAssetEmployee-form" method="post" action="{{ route('admin:suppliers_contacts.store', ['supplier'=>$supplier->id]) }}">
                <div class="modal-body">
                        <div class="row">
                            @csrf
                            <input type="hidden" value="{{$supplier->id}}" name="supplier_id">
                            <input type="hidden" value="" name="supplier_contact_id" id="supplier_contact_id">

                            <div class="form-group col-md-6">
                                <label> {{ __('Name') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-user"></span>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('Name') }}">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label> {{ __('Address') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-user"></span>
                                    <input type="text" name="address" id="address" class="form-control" placeholder=" {{ __('Address') }}">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label> {{ __('phone 1') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-phone"></span>
                                    <input type="text" name="phone_1" id="phone_1" class="form-control" placeholder="{{ __('phone 1') }}">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label> {{ __('phone 2') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-phone"></span>
                                    <input type="text" name="phone_2" id="phone_2" class="form-control" placeholder=" {{ __('phone 2') }}">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label> {{ __('Job title') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-user"></span>
                                    <input type="text" name="job_title" id="job_title" class="form-control" placeholder=" {{ __('Job title') }}">
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label> {{ __('E-Mail') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-mail-forward"></span>
                                    <input type="text" name="email" id="email" class="form-control" placeholder=" {{ __('E-Mail') }}">
                                </div>
                            </div>


                            <div class="form-group col-md-6">
                                <label> {{ __('words.date-from') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                    <input name="start_date" id="start_date"
                                           class="form-control date js-example-basic-single" type="date"/>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label> {{ __('words.date-to') }} </label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                    <input name="end_date" id="end_date"
                                           class="form-control date js-example-basic-single" type="date"/>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="status" class="control-label">{{__('Status')}}</label>
                                <div class="switch primary" style="margin-top: 15px">
                                    <input type="hidden"  name="status" value="0">
                                    <input type="checkbox" id="switch-1" name="status" value="1" CHECKED >
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

@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:stores.index')}}"> {{__('Suppliers')}}</a></li>
                <li class="breadcrumb-item active"> {{ __('Contacts') }}</li>
            </ol>
        </nav>

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
                            <div class="list-inline margin-bottom-0 row">
                                <div class="form-group col-md-6">
                                    <label> {{ __('Employee name') }} </label>
                                    <input type="hidden" name="supplierID" id="supplierID" value="{{$supplier->id}}">
                                    {!! drawSelect2ByAjax('supplier_contact','SupplierContact', 'name', 'name',  __('opening-balance.select-one'),request()->supplier_contact) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    <label> {{ __('Phone') }} </label>
                                    {!! drawSelect2ByAjax('phoneNumber','SupplierContact', 'phone_1', 'phone_1',  __('opening-balance.select-one'),request()->phoneNumber) !!}
                                </div>

                                <div class="form-group col-md-6">
                                    <label> {{ __('words.date-from') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                        <input name="start_date" id="start_date"
                                               class="form-control date js-example-basic-single" type="date"/>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label> {{ __('words.date-to') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                        <input name="end_date" id="end_date"
                                               class="form-control date js-example-basic-single" type="date"/>
                                    </div>
                                </div>


                                <div class="switch primary col-md-1">
                                    <input type="checkbox" id="switch-slam" name="active">
                                    <label for="switch-slam">{{__('Active')}}</label>
                                </div>
                                <div class="switch primary col-md-2">
                                    <input type="checkbox" id="switch-ali" name="inactive">
                                    <label for="switch-ali">{{__('inActive')}}</label>
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
                    <i class="fa fa-cubes"></i>  [{{count($contacts)}}] {{ __('Contacts')." : " .$supplier->name }}
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
                                'route' => 'admin:suppliers_contacts.deleteSelected',
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
                                <th scope="col"> {{ __('Name') }} </th>
                                <th scope="col"> {{ __('Job title') }} </th>
                                <th scope="col"> {{ __('Phone 1') }} </th>
                                <th scope="col"> {{ __('Phone 2') }} </th>
                                <th scope="col"> {{ __('address') }} </th>
                                <th scope="col"> {{ __('E-Mail') }} </th>
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
                                <th scope="col"> {{ __('Name') }} </th>
                                <th scope="col"> {{ __('Job title') }} </th>
                                <th scope="col"> {{ __('Phone 1') }} </th>
                                <th scope="col"> {{ __('Phone 2') }} </th>
                                <th scope="col"> {{ __('address') }} </th>
                                <th scope="col"> {{ __('E-Mail') }} </th>
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
    {!! JsValidator::formRequest('App\Http\Requests\SupplierContactRequest')->selector('#newAssetEmployee-form'); !!}
    <script type="application/javascript">
        $(document).ready(function () {

            $('#add-employee-modal').on('show.bs.modal', function (event) {
                $('#empId').select2({
                    dropdownParent: $('#add-employee-modal')
                });
                var button = $(event.relatedTarget);
                var supplier_contact_id = button.data('id');
                $('#supplier_contact_id').val(supplier_contact_id);
                var name = button.data('name');
                $('#name').val(name);
                var address = button.data('address');
                $('#address').val(address);
                var phone1 = button.data('phone1');
                $('#phone_1').val(phone1);
                var phone2 = button.data('phone2');
                $('#phone_2').val(phone2);
                var email = button.data('email');
                $('#email').val(email);
                var start_date = button.data('start_date');
                $('#start_date').val(start_date);
                var end_date = button.data('end_date');
                $('#end_date').val(end_date);
                var job_title = button.data('job_title');
                $('#job_title').val(job_title);
                var title = button.data('title');
                if (title === undefined){
                    $('#myModalLabel-1').text('{{__('Add New Contact')}}');
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
