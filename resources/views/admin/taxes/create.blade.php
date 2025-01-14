@extends('admin.layouts.app')
@section('title')
    <title>{{ __('Create Taxes') }} </title>
@endsection
@section('content')
    <div class="row small-spacing">
        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:taxes.index')}}"> {{__('Taxes And Fees')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Create Taxes')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
            <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>
                    {{__('Create Taxes')}}
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
                    <form method="post" action="{{route('admin:taxes.store')}}" class="form">
                        @csrf
                        @method('post')
                        <div class="row">
                            <div class="col-xs-12">

                                <div class="row top-data-wg"
                                     style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                                    @if(authIsSuperAdmin())
                                        <div class="col-xs-12">
                                            <div class="form-group has-feedback">
                                                <label for="inputSymbolAR"
                                                       class="control-label">{{__('Select Branch')}}</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon fa fa-file"></span>
                                                    <select name="branch_id"
                                                            class="form-control  js-example-basic-single">
                                                        @foreach(\App\Models\Branch::all() as $branch)
                                                            <option value="{{$branch->id}}">{{$branch->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    {{input_error($errors,'branch_id')}}
                                                </div>
                                            </div>
                                        </div>
                                    @endif


                                    <div class="">


                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="inputNameAR"
                                                       class="control-label">{{__('Name in Arabic')}}</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><li
                                                            class="fa fa-file-text"></li></span>
                                                    <input type="text" name="name_ar" class="form-control"
                                                           id="inputNameAR"
                                                           placeholder="{{__('Name in Arabic')}}">
                                                </div>
                                                {{input_error($errors,'name_ar')}}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="inputNameEN"
                                                       class="control-label">{{__('Name in English')}}</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><li
                                                            class="fa fa-file-text"></li></span>
                                                    <input type="text" name="name_en" class="form-control"
                                                           id="inputNameEN"
                                                           placeholder="{{__('Name in English')}}">
                                                </div>
                                                {{input_error($errors,'name_en')}}
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="inputSymbolAR"
                                                       class="control-label">{{__('Tax /Fee Type')}}</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><li
                                                            class="fa fa-dollar"></li></span>
                                                    <select name="tax_type"
                                                            class="form-control  js-example-basic-single">
                                                        <option value="">{{__('Select Tax and Fee Type')}}</option>
                                                        <option value="amount">{{__('Amount')}}</option>
                                                        <option value="percentage">{{__('Percentage')}}</option>
                                                    </select>
                                                    {{input_error($errors,'tax_type')}}
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="">

                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="inputSymbolAR" class="control-label">{{__('Type')}}</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><li
                                                            class="fa fa-dollar"></li></span>
                                                    <select name="type" class="form-control  js-example-basic-single"
                                                            id="tax_type"
                                                            onchange="showForParts()">
                                                        <option value="">{{__('Select Type')}}</option>
                                                        <option value="tax">{{__('Tax')}}</option>
                                                        <option
                                                            value="additional_payments">{{__('Additional Payments')}}</option>
                                                    </select>
                                                    {{input_error($errors,'type')}}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group has-feedback">
                                                <label for="inputValue"
                                                       class="control-label">{{__('Tax /Fee Value')}}</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><li
                                                            class="fa fa-dollar"></li></span>
                                                    <input type="text" name="value" class="form-control" id="inputValue"
                                                           placeholder="{{__('Tax /Fee Value')}}"
                                                           value="{{old('value') ?? 0}}">
                                                </div>
                                                {{input_error($errors,'value')}}
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row top-data-wg"
                             style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

                            <div class="col-md-12">


                                <div class="table-responsive wg-inside-table">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Status')}}</th>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Status')}}</th>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Status')}}</th>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>{{__('Invoices')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                    <input type="checkbox" id="switch-1" name="active_invoices">
                                                    <label for="switch-1">{{__('Active')}}</label>
                                                </div>
                                            </td>
                                            <td>{{__('Quotations')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                    <input type="checkbox" id="switch-2" name="active_offers">
                                                    <label for="switch-2">{{__('Active')}}</label>
                                                </div>
                                            </td>
                                            <td>{{__('Services')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                    <input type="checkbox" id="switch-3" name="active_services">
                                                    <label for="switch-3">{{__('Active')}}</label>
                                                </div>
                                            </td>

                                            <td>{{__('Supply Order')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                    <input type="checkbox" id="switch-supply" name="supply_order"
                                                           VALUE="1">
                                                    <label for="switch-supply">{{__('Active')}}</label>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>{{__('Purchase Invoice')}} </td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                    <input type="checkbox" id="switch-4" name="active_purchase_invoice">
                                                    <label for="switch-4">{{__('Active')}}</label>
                                                </div>
                                            </td>
                                            <td>{{__('Purchase Quotation')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                    <input type="checkbox" id="switch-6" name="purchase_quotation">
                                                    <label for="switch-6">{{__('Active')}}</label>
                                                </div>
                                            </td>
                                            <td>{{__('For Parts')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                    <input type="checkbox" id="switch-5" name="on_parts">
                                                    <label for="switch-5">{{__('Active')}}</label>
                                                </div>
                                            </td>

                                            <td>{{__('Purchase Return')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                    <input type="checkbox" id="switch-return" name="purchase_return">
                                                    <label for="switch-return">{{__('Active')}}</label>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>

                                            <td>{{__('Sale Supply Order')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                    <input type="checkbox" id="switch-sale_supply_order" name="sale_supply_order">
                                                    <label for="switch-sale_supply_order">{{__('Active')}}</label>
                                                </div>
                                            </td>

                                        </tr>

                                        </tbody>
                                    </table>


                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>{{__('Tax /Execution Time')}}</th>
                                                <td>

                                                    <div class="radio primary">
                                                        <input type="radio" name="execution_time"
                                                               id="execution_time_after" value="after_discount" checked>
                                                        <label
                                                            for="execution_time_after">{{__('After Discount')}}</label>

                                                        <input type="radio" name="execution_time"
                                                               value="before_discount" id="execution_time_before">
                                                        <label
                                                            for="execution_time_before">{{__('Before Discount')}}</label>
                                                    </div>

                                                </td>

                                            </tr>
                                            </thead>
                                            <tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row">

                                <!-- <div class="col-md-4">
  <table class="table">
     <tr>
       <th>{{__('Invoices')}}</th>
     </tr>
     <tr>
       <td>
       <div class="switch primary" style="margin:0">
          <input type="checkbox" id="switch-1" name="active_invoices"
                  VALUE="1">
          <label for="switch-1">{{__('Active')}}</label>
      </div>
        </td>
     </tr>
  </table>
</div> -->

                                <!-- <div class="col-md-4">
  <table class="table">
     <tr>
       <th>{{__('Quotations')}}</th>
     </tr>
     <tr>
       <td>
       <div class="switch primary" style="margin:0">
       <input type="checkbox" id="switch-2" name="active_offers" VALUE="1">
                                                  <label for="switch-2">{{__('Active')}}</label>
      </div>
        </td>
     </tr>
  </table>
</div> -->

                                <!-- <div class="col-md-4">
  <table class="table">
     <tr>
       <th>{{__('Services')}}</th>
     </tr>
     <tr>
       <td>
       <div class="switch primary" style="margin:0">
       <input type="checkbox" id="switch-3" name="active_services"
                                                         VALUE="1">
                                                  <label for="switch-3">{{__('Active')}}</label>
      </div>
        </td>
     </tr>
  </table>
</div> -->

                                <!-- <div class="col-md-4">
  <table class="table">
     <tr>
       <th>{{__('Purchase Invoice')}}</th>
     </tr>
     <tr>
       <td>
       <div class="switch primary" style="margin:0">
       <input type="checkbox" id="switch-4" name="active_purchase_invoice"
                                                         VALUE="1">
                                                  <label for="switch-4">{{__('Active')}}</label>
      </div>
        </td>
     </tr>
  </table>
</div> -->

                                <!-- <div class="col-md-4">
  <table class="table">
     <tr>
       <th>{{__('Purchase Quotation')}}</th>
     </tr>
     <tr>
       <td>
       <div class="switch primary" style="margin:0">
       <input type="checkbox" id="switch-6" name="purchase_quotation" >
                                                  <label for="switch-6">{{__('Active')}}</label>
      </div>
        </td>
     </tr>
  </table>
</div> -->

                                <!-- <div class="col-md-4">
  <table class="table">
     <tr>
       <th>{{__('For Parts')}}</th>
     </tr>
     <tr>
       <td>
       <div class="switch primary" style="margin:0">
       <input type="checkbox" id="switch-5" name="on_parts" >
                                                  <label for="switch-5">{{__('Active')}}</label>
      </div>
        </td>
     </tr>
  </table>
</div> -->
                                <!--
<div class="col-md-4">
  <table class="table">
     <tr>
       <th>{{__('Tax /Execution Time')}}</th>
     </tr>
     <tr>
   <td>
   <div class="form-group has-feedback">
                                      <div class="radio primary">
                                          <input type="radio" name="execution_time"  id="execution_time_after" value="after_discount" checked>
                                          <label for="execution_time_after">{{__('After Discount')}}</label>
                                      </div>
                                      <div class="radio primary">
                                          <input type="radio" name="execution_time" value="before_discount" id="execution_time_before">
                                          <label for="execution_time_before">{{__('Before Discount')}}</label>
                                      </div>
                                  </div>
   </td>
     </tr>
  </table>
</div> -->

                                    <!-- </div> -->


                                    <div class="form-group has-feedback">
                                    <!-- <ul class="list-inline list-group-ww">

                                            <li style="">
                                                <label for="inputNameEN"
                                                       class="control-label">{{__('Invoices')}}</label>
                                                <input type="hidden" name="active_invoices" VALUE="0">
                                                <div class="switch primary" style="margin-top: 15px">
                                                    <input type="checkbox" id="switch-1" name="active_invoices"
                                                           VALUE="1">
                                                    <label for="switch-1">{{__('Active')}}</label>
                                                </div>
                                            </li>

                                            <li>
                                                <label for="inputNameEN"
                                                       class="control-label">{{__('Quotations')}}</label>
                                                <input type="hidden" name="active_offers" VALUE="0">
                                                <div class="switch primary" style="margin-top: 15px">
                                                    <input type="checkbox" id="switch-2" name="active_offers" VALUE="1">
                                                    <label for="switch-2">{{__('Active')}}</label>
                                                </div>
                                            </li>

                                            <li style="">
                                                <label for="inputNameEN"
                                                       class="control-label">{{__('Services')}}</label>
                                                <input type="hidden" name="active_services" VALUE="0">
                                                <div class="switch primary" style="margin-top: 15px">
                                                    <input type="checkbox" id="switch-3" name="active_services"
                                                           VALUE="1">
                                                    <label for="switch-3">{{__('Active')}}</label>
                                                </div>
                                            </li>

                                            <li style="">
                                                <label for="inputNameEN"
                                                       class="control-label">{{__('Purchase Invoice')}}</label>
                                                <input type="hidden" name="active_purchase_invoice" VALUE="0">
                                                <div class="switch primary" style="margin-top: 15px">
                                                    <input type="checkbox" id="switch-4" name="active_purchase_invoice"
                                                           VALUE="1">
                                                    <label for="switch-4">{{__('Active')}}</label>
                                                </div>
                                            </li>

                                            <li >
                                                <label for="inputNameEN" class="control-label">{{__('Purchase Quotation')}}</label>
                                                <div class="switch primary" style="margin-top: 15px">
                                                    <input type="checkbox" id="switch-6" name="purchase_quotation" >
                                                    <label for="switch-6">{{__('Active')}}</label>
                                                </div>
                                            </li>

                                            <li style="" class="for_parts">
                                                <label for="inputNameEN"
                                                       class="control-label">{{__('For Parts')}}</label>
                                                <div class="switch primary" style="margin-top: 15px">
                                                    <input type="checkbox" id="switch-5" name="on_parts" >
                                                    <label for="switch-5">{{__('Active')}}</label>
                                                </div>
                                            </li>


                                        </ul> -->
                                    </div>
                                </div>

                            <!-- <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="inputValue" class="control-label">{{__('Tax /Execution Time')}}</label>
                                        <div class="radio primary">
                                            <input type="radio" name="execution_time"  id="execution_time_after" value="after_discount" checked>
                                            <label for="execution_time_after">{{__('After Discount')}}</label>
                                        </div>
                                        <div class="radio primary">
                                            <input type="radio" name="execution_time" value="before_discount" id="execution_time_before">
                                            <label for="execution_time_before">{{__('Before Discount')}}</label>
                                        </div>
                                    </div>
                                </div> -->

                            </div>
                        </div>


                        @include('admin.buttons._save_buttons')

                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')

    {!! JsValidator::formRequest('App\Http\Requests\Admin\Tax\TaxRequest', '.form'); !!}

    @include('admin.partial.sweet_alert_messages')

    <script>

        function showForParts() {

            let selectedTaxType = $("#tax_type option:selected").val();

            if (selectedTaxType == 'additional_payments') {

                $(".for_parts").hide();
                $("#switch-5").prop('checked', false);

            } else {

                $(".for_parts").show();
            }
        }

    </script>

@endsection
