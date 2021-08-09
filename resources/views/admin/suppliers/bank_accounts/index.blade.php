@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Bank Accounts') }} </title>
@endsection

<!-- Modal -->
<div class="modal fade" id="add-employee-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33">
    <div class="modal-dialog" role="document">
        <div class="modal-content wg-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel-1">{{__('Bank Accounts')}}</h4>
            </div>

            <form id="newAssetEmployee-form" method="post"
                  action="{{ route('admin:suppliers_bank_account.store', ['supplier'=>$supplier->id]) }}">
                <div class="modal-body">
                    <div class="row">
                        @csrf
                        <input type="hidden" value="{{$supplier->id}}" name="supplier_id">
                        <input type="hidden" value="" name="supplier_bank_account_id" id="supplier_bank_account_id">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('Bank Name')}}</label>
                                <span class="asterisk" style="color: #ff1d47"> * </span>
                                <input type="text" name="bank_name" id="bank_name" class="form-control">
                            </div>
                            {{input_error($errors,'bank_name')}}
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('Account Name')}}</label>
                                <span class="asterisk" style="color: #ff1d47"> * </span>
                                <input type="text" name="account_name" id="account_name" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('Branch Name')}} :</label>
                                <input type="text" name="branch" id="branch" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputPassword1"> {{__('Account Number')}} : </label>
                                <span class="asterisk" style="color: #ff1d47"> * </span>
                                <input ype="text" name="account_number" id="account_number" class="form-control"
                                       style=" height: 45px;">
                            </div>
                            {{input_error($errors,'account_number')}}
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('IBAN')}} :</label>
                                <input type="text" name="iban" id="iban" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">{{__('Swift Code')}} :</label>
                                <input type="text" name="swift_code" id="swift_code" class="form-control">
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
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="switch-1" name="status" value="1" CHECKED>
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
                <li class="breadcrumb-item active"> {{ __('Bank Accounts') }}</li>
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
                        <form onsubmit="filterFunction($(this));return false;">
                            <div class="list-inline margin-bottom-0 row">
                                <div class="form-group col-md-4">
                                    <label> {{ __('Bank Name') }} </label>
                                    <input type="hidden" name="supplierID" id="supplierID" value="{{$supplier->id}}">
                                    {!! drawSelect2ByAjax('bank_account','BankAccount', 'bank_name', 'bank_name',  __('opening-balance.select-one'),request()->bank_account) !!}
                                </div>

                                <div class="form-group col-md-4">
                                    <label> {{ __('words.date-from') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                        <input name="start_date" id="start"
                                               class="form-control date js-example-basic-single" type="date"/>
                                    </div>
                                </div>

                                <div class="form-group col-md-4">
                                    <label> {{ __('words.date-to') }} </label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                        <input name="end_date" id="end"
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
                    <i class="fa fa-cubes"></i> [{{count($bankAccounts)}}
                    ] {{ __('Bank Accounts')." : " .$supplier->name }}
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
                                'route' => 'admin:suppliers_bank_account.deleteSelected',
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
                                <th>{{__('Bank Name')}}</th>
                                <th>{{__('Account Name')}}</th>
                                <th>{{__('Branch Name')}}</th>
                                <th>{{__('Account Number')}}</th>
                                <th>{{__('IBAN')}}</th>
                                <th>{{__('Swift Code')}}</th>
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
                                <th>{{__('Bank Name')}}</th>
                                <th>{{__('Account Name')}}</th>
                                <th>{{__('Branch Name')}}</th>
                                <th>{{__('Account Number')}}</th>
                                <th>{{__('IBAN')}}</th>
                                <th>{{__('Swift Code')}}</th>
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
    {!! JsValidator::formRequest('App\Http\Requests\SupplierBankAccountRequest')->selector('#newAssetEmployee-form'); !!}
    <script type="application/javascript">
        $(document).ready(function () {

            $('#add-employee-modal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var supplier_bank_account_id = button.data('id');
                $('#supplier_bank_account_id').val(supplier_bank_account_id);

                var bank_name = button.data('bank_name');
                $('#bank_name').val(bank_name);

                var account_name = button.data('account_name');
                $('#account_name').val(account_name);

                var branch = button.data('branch');
                $('#branch').val(branch);

                var account_number = button.data('account_number');
                $('#account_number').val(account_number);

                var iban = button.data('iban');
                $('#iban').val(iban);

                var swift_code = button.data('swift_code');
                $('#swift_code').val(swift_code);

                var start_date = button.data('start_date');
                $('#start_date').val(start_date);
                var end_date = button.data('end_date');
                $('#end_date').val(end_date);

                var job_title = button.data('job_title');
                $('#job_title').val(job_title);
                var title = button.data('title');
                if (title === undefined) {
                    $('#myModalLabel-1').text('{{__('Add New Bank Account')}}');
                }
                $('#myModalLabel-1').text(title);
            });

            $('#add-employee-modal').on('hide.bs.modal', function (event) {
                $("#newAssetEmployee-form").get(0).reset();
                $(".error-help-block").each(function (index, element) {
                    element.remove();
                })
                $("form#newAssetEmployee-form .form-group").each(function () {
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
            setTimeout(function () {
                $("#loaderSearch").hide();
            }, 1000)
        }
    </script>
@stop
