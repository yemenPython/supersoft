<div class="col-md-12 input-height">

<div class="col-md-6">
   
<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#F9EFB7 !important;color:black !important">{!! __('Total') !!}</th>
      <td style="background:#F9EFB7">
      <input type="text" 
      style="background:#F9EFB7; border:none;text-align:center !important;"
      class="form-control" readonly name="sub_total" id="sub_total"
                       value="{{isset($purchaseReturn) ? $purchaseReturn->sub_total : 0}}">

                       </td>
      </tr>
</table>
</div>

<div class="col-md-6">

<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#F9EFB7 !important;color:black !important">{!! __('Discount Type') !!}</th>
      <td style="background:#F9EFB7">
      <ul class="list-inline" style="margin: 0;padding:0">
                        <li>
                        <div class="radio primary remove-padd-marg">
                        <input type="radio" name="discount_type" id="discount_type_amount"
                           {{isset($purchaseReturn) && $purchaseReturn->discount_type == 'amount' ? 'checked': '' }}
                           value="amount"
                           {{!isset($purchaseReturn) ? 'checked':''}} onclick="calculateInvoiceDiscount()">
                    <label for="discount_type_amount">{{__('amount')}}</label>
                    </div>
                        </li>
                        <li>
                        <div class="radio primary remove-padd-marg">
                        <input type="radio" name="discount_type"
                           {{isset($purchaseInvoice) && $purchaseInvoice->discount_type == 'percent' ? 'checked': '' }}
                           id="discount_type_percent" value="percent"
                           onclick="calculateInvoiceDiscount()">
                    <label for="discount_type_percent">{{__('Percent')}}</label>

                    </div>
                        </li>
                    </ul>




            </td>
      </tr>
</table>
</div>

</div>


<div class="col-md-12 input-height">


<div class="col-md-6">

<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#D2F4F6 !important;color:black !important">{!! __('Supplier Discount') !!}</th>
      <td style="background:#D2F4F6 !important;color:black!important">
<ul class="list-inline flex-div-cen">
  <li>
  <div class="has-feedback">
  <input type="checkbox" id="supplier_discount_check" name="supplier_discount_active" onclick="calculateTotal()"
                                {{isset($purchaseReturn) && $purchaseReturn->supplier_discount_status ? 'checked' : ''}}>
                        
                       </div>
  </li>

  <li>
                        <input type="number" name="supplier_discount" min="0" readonly="readonly"
                        style="background:#D2F4F6; border:none;text-align:center !important;"
                               class="form-control supplier_discount"
                               value="{{isset($purchaseReturn) ? $purchaseReturn->supplier_discount : 0}}"
                        >
                        </li>


<li>
<input type="text" disabled="disabled" class="form-control supplier_discount_type"
                               value="{{isset($purchaseReturn) && $purchaseReturn->supplier_discount_type == 'percent' ? '%' : '$'}}"
                               style="width: 42px;">

                        <input type="hidden" name="supplier_discount_type"  class="supplier_discount_type_value"
                               value="{{isset($purchaseReturn) ? $purchaseReturn->supplier_discount_type : 'amount'}}"
                        >

                        </li>




</ul>


</td>
      </tr>
</table>
</div>



<div class="col-md-6">
      
<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#D2F4F6 !important;color:black !important">{!! __('Discount') !!}</th>
      <td style="background:#D2F4F6 !important;color:black!important">
      <input type="text" class="form-control text-center" readonly
                       value="{{isset($purchaseReturn) ? $purchaseReturn->total_after_discount : 0}}"
                       name="total_after_discount" id="total_after_discount">
                       </td>
      </tr>
</table>
</div>


</div>



<div class="col-md-12 input-height">

<div class="col-md-6">
<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#FFC5D7 !important;color:black !important">{!! __('Total After Discount') !!}</th>
      <td  style="background:#FFC5D7">
      <input type="text" class="form-control" readonly
      style="background:#FFC5D7; border:none;text-align:center !important;"
                       value="{{isset($purchaseReturn) ? $purchaseReturn->total_after_discount : 0}}"
                       name="total_after_discount" id="total_after_discount">
                       
                       </td>
      </tr>
</table>
</div>




<div class="col-md-6">
<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#FFC5D7 !important;color:black !important">
                <div class="btn-group ">

                    <span type="button" class="fa fa-eye eye-design-one dropdown-toggle" data-toggle="dropdown"

                          
                          aria-haspopup="true" aria-expanded="false">
</span>


                    <ul class="dropdown-menu for-design-eye" style="margin-top: 19px;">
                    @if($data['taxes']->count())
                            @foreach($data['taxes'] as $tax_key => $tax)

                                @php
                                    $tax_key +=1;
                                @endphp
                                <li>
                                    <a>
                                        <input type="checkbox" id="checkbox_tax_{{$tax_key}}" name="taxes[]"
                                               onclick="calculateTax()"
                                               {{!isset($purchaseReturn) ? 'checked':''}}
                                               {{isset($purchaseReturn) && in_array($tax->id, $purchaseReturn->taxes->pluck('id')->toArray())? 'checked':'' }}
                                               data-tax-value="{{$tax->value}}"
                                               data-tax-type="{{$tax->tax_type}}"
                                               data-tax-execution-time="{{$tax->execution_time}}"
                                               value="{{$tax->id}}"
                                        >
                                        <span>
                                            {{$tax->name}} - {{$tax->tax_type == 'amount' ? '$':'%'}} - {{ $tax->value }} -
                                             <span id="calculated_tax_value_{{$tax_key}}">
                                                  {{isset($purchaseReturn) ? taxValueCalculated($purchaseReturn->total_after_discount, $purchaseReturn->sub_total, $tax) : 0}}
                                             </span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a>
                                    <span>{{ __('No Taxes Founded') }}</span>
                                </a>
                            </li>

                        @endif
                    </ul>

                    <input type="hidden" id="invoice_tax_count" value="{{$data['taxes']->count()}}">
                </div>

                {!! __('Taxes') !!}
            </th>
            <td  style="background:#FFC5D7">

            <input type="text" class="form-control"
            style="background:#FFC5D7; border:none;text-align:center !important;"
             readonly name="tax" id="tax"
                       value=" {{isset($purchaseReturn) ? $purchaseReturn->tax : 0}}"
                >

            </td>
      </tr>
</table>
</div>

</div>




<div class="col-md-12 input-height">
<div class="col-md-6">

<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#D2CCF8 !important;color:black !important">
                <div class="btn-group ">
                    <span type="button" class="fa fa-eye eye-design-two dropdown-toggle" data-toggle="dropdown"
                          
                          aria-haspopup="true" aria-expanded="false">
                        

                        </span>

                    <ul class="dropdown-menu for-design-eye" style="margin-top: 19px;">
                    @if( $data['additionalPayments']->count())
                            @foreach( $data['additionalPayments'] as $additional_key => $additionalPayment)

                                @php
                                    $additional_key +=1;
                                @endphp

                                <li>
                                    <a>
                                        <input type="checkbox" id="checkbox_additional_{{$additional_key}}" name="additional_payments[]"
                                               onclick="calculateTotal()"
                                               {{!isset($purchaseReturn) ? 'checked':''}}
                                               {{isset($purchaseReturn) && in_array($additionalPayment->id, $purchaseReturn->taxes->pluck('id')->toArray())? 'checked':'' }}
                                               data-additional-value="{{$additionalPayment->value}}"
                                               data-additional-type="{{$additionalPayment->tax_type}}"
                                               data-additional-execution-time="{{$additionalPayment->execution_time}}"
                                               value="{{$additionalPayment->id}}"
                                        >
                                        <span>
                                            {{$additionalPayment->name}} -
                                            {{$additionalPayment->tax_type == 'amount' ? '$':'%'}} -
                                            {{ $additionalPayment->value }} -
                                             <span id="calculated_additional_value_{{$additional_key}}">
                                                 {{isset($purchaseReturn) ? taxValueCalculated($purchaseReturn->total_after_discount, $purchaseReturn->sub_total, $additionalPayment ) : 0}}
                                             </span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a>
                                    <span>{{ __('No Additional Founded') }}</span>
                                </a>
                            </li>

                        @endif
                    </ul>

                    <input type="hidden" id="invoice_additional_count" value="{{ $data['additionalPayments']->count()}}">
                </div>

                {!! __('Additional Payments') !!}
            </th>

            <td style="background:#D2CCF8 !important">
            <input type="text" class="form-control" 
            style="background:#D2CCF8; border:none;text-align:center !important;"
            readonly name="additional_payments_value" id="additional_payments"
                       value=" {{isset($purchaseReturn) ? $purchaseReturn->additional_payments : 0}}"
                >

            </td>
            </tr>
</table>
</div>


<div class="col-md-6">
<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#D2CCF8 !important;color:black !important">{!! __('Final Total') !!}</th>
      <td style="background:#D2CCF8 !important">

      <input type="text" class="form-control"
      style="background:#D2CCF8; border:none;text-align:center !important;"
       readonly name="total" id="total"
                       value="{{isset($purchaseReturn) ? $purchaseReturn->total : 0}}">

                       </td>
      </tr>

</table>

</div>
</div>




<!-- 

<div class="form-group has-feedback col-sm-12">
    <table class="table table-bordered" style="width:100%">
        <thead>
        <tr> -->
            <!-- <th scope="col">{!! __('Total') !!}</th> -->
            <!-- <th scope="col">{!! __('Discount Type') !!}</th> -->
            <!-- <th scope="col">{!! __('Discount') !!}</th> -->
            <!-- <th scope="col">{!! __('Supplier Discount') !!}</th> -->
            <!-- <th scope="col">{!! __('Total After Discount') !!}</th> -->
            <!-- <th scope="col">
                <div class="btn-group ">
                    <span type="button" class="fa fa-usd  dropdown-toggle" data-toggle="dropdown" style="background-color: rgb(244, 67, 54); color: white; padding: 3px; border-radius: 5px; cursor: pointer"
                          aria-haspopup="true" aria-expanded="false"> </span>

                    <ul class="dropdown-menu" style="margin-top: 19px;">
                        @if($data['taxes']->count())
                            @foreach($data['taxes'] as $tax_key => $tax)

                                @php
                                    $tax_key +=1;
                                @endphp

                                <li>
                                    <a>
                                        <input type="checkbox" id="checkbox_tax_{{$tax_key}}" name="taxes[]"
                                               onclick="calculateTax()"
                                               {{!isset($purchaseReturn) ? 'checked':''}}
                                               {{isset($purchaseReturn) && in_array($tax->id, $purchaseReturn->taxes->pluck('id')->toArray())? 'checked':'' }}
                                               data-tax-value="{{$tax->value}}"
                                               data-tax-type="{{$tax->tax_type}}"
                                               data-tax-execution-time="{{$tax->execution_time}}"
                                               value="{{$tax->id}}"
                                        >
                                        <span>
                                            {{$tax->name}} - {{$tax->tax_type == 'amount' ? '$':'%'}} - {{ $tax->value }} -
                                             <span id="calculated_tax_value_{{$tax_key}}">
                                                  {{isset($purchaseReturn) ? taxValueCalculated($purchaseReturn->total_after_discount, $purchaseReturn->sub_total, $tax) : 0}}
                                             </span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a>
                                    <span>{{ __('No Taxes Founded') }}</span>
                                </a>
                            </li>

                        @endif
                    </ul>

                    <input type="hidden" id="invoice_tax_count" value="{{$data['taxes']->count()}}">
                </div>

                {!! __('Taxes') !!}
            </th> -->

            <!-- <th scope="col">
                <div class="btn-group ">
                    <span type="button" class="fa fa-usd  dropdown-toggle" data-toggle="dropdown"
                          style="background-color: rgb(244, 67, 54); color: white; padding: 3px; border-radius: 5px; cursor: pointer"
                          aria-haspopup="true" aria-expanded="false"> </span>

                    <ul class="dropdown-menu" style="margin-top: 19px;"> -->
                        <!-- @if( $data['additionalPayments']->count())
                            @foreach( $data['additionalPayments'] as $additional_key => $additionalPayment)

                                @php
                                    $additional_key +=1;
                                @endphp

                                <li>
                                    <a>
                                        <input type="checkbox" id="checkbox_additional_{{$additional_key}}" name="additional_payments[]"
                                               onclick="calculateTotal()"
                                               {{!isset($purchaseReturn) ? 'checked':''}}
                                               {{isset($purchaseReturn) && in_array($additionalPayment->id, $purchaseReturn->taxes->pluck('id')->toArray())? 'checked':'' }}
                                               data-additional-value="{{$additionalPayment->value}}"
                                               data-additional-type="{{$additionalPayment->tax_type}}"
                                               data-additional-execution-time="{{$additionalPayment->execution_time}}"
                                               value="{{$additionalPayment->id}}"
                                        >
                                        <span>
                                            {{$additionalPayment->name}} -
                                            {{$additionalPayment->tax_type == 'amount' ? '$':'%'}} -
                                            {{ $additionalPayment->value }} -
                                             <span id="calculated_additional_value_{{$additional_key}}">
                                                 {{isset($purchaseReturn) ? taxValueCalculated($purchaseReturn->total_after_discount, $purchaseReturn->sub_total, $additionalPayment ) : 0}}
                                             </span>
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <a>
                                    <span>{{ __('No Additional Founded') }}</span>
                                </a>
                            </li>

                        @endif
                    </ul>

                    <input type="hidden" id="invoice_additional_count" value="{{ $data['additionalPayments']->count()}}">
                </div>

                {!! __('Additional Payments') !!}
            </th> -->

            <!-- <th scope="col">{!! __('Final Total') !!}</th> -->
        <!-- </tr>
        </thead>
        <tbody>
        <tr> -->
            <!-- <td>
                <input type="text" class="form-control" readonly name="sub_total" id="sub_total"
                       value="{{isset($purchaseReturn) ? $purchaseReturn->sub_total : 0}}">
            </td> -->
            <!-- <td>
                <div class="radio primary">
                    <input type="radio" name="discount_type" id="discount_type_amount"
                           {{isset($purchaseReturn) && $purchaseReturn->discount_type == 'amount' ? 'checked': '' }}
                           value="amount"
                           {{!isset($purchaseReturn) ? 'checked':''}} onclick="calculateInvoiceDiscount()">
                    <label for="discount_type_amount">{{__('amount')}}</label>
                </div>

                <div class="radio primary">
                    <input type="radio" name="discount_type"
                           {{isset($purchaseInvoice) && $purchaseInvoice->discount_type == 'percent' ? 'checked': '' }}
                           id="discount_type_percent" value="percent"
                           onclick="calculateInvoiceDiscount()">
                    <label for="discount_type_percent">{{__('Percent')}}</label>
                </div>

            </td> -->
            <!-- <td>
                <input type="number" class="form-control"
                       value="{{isset($purchaseReturn) ? $purchaseReturn->discount : 0}}"
                       id="discount"
                       onchange="calculateInvoiceDiscount()"
                       onkeyup="calculateInvoiceDiscount()"
                       name="discount" min="0">
            </td> -->

            <!-- <td>
                <div class="row">
                    <div class="col-md-2" style="margin-top: 4px;">
                        <div class="form-group has-feedback">
                            <input type="checkbox" id="supplier_discount_check" name="supplier_discount_active" onclick="calculateTotal()"
                                {{isset($purchaseReturn) && $purchaseReturn->supplier_discount_status ? 'checked' : ''}}>
                        </div>
                    </div>

                    <div class="col-md-3">

                        <input type="text" disabled="disabled" class="form-control supplier_discount_type"
                               value="{{isset($purchaseReturn) && $purchaseReturn->supplier_discount_type == 'percent' ? '%' : '$'}}"
                               style="width: 42px;">

                        <input type="hidden" name="supplier_discount_type"  class="supplier_discount_type_value"
                               value="{{isset($purchaseReturn) ? $purchaseReturn->supplier_discount_type : 'amount'}}"
                        >
                    </div>

                    <div class="col-md-7">
                        <input type="number" name="supplier_discount" min="0" readonly="readonly"
                               class="form-control supplier_discount"
                               value="{{isset($purchaseReturn) ? $purchaseReturn->supplier_discount : 0}}"
                        >
                    </div>
                </div>

            </td> -->
<!-- 
            <td>
                <input type="text" class="form-control" readonly
                       value="{{isset($purchaseReturn) ? $purchaseReturn->total_after_discount : 0}}"
                       name="total_after_discount" id="total_after_discount">
            </td> -->

            <!-- <td>
                <input type="text" class="form-control" readonly name="tax" id="tax"
                       value=" {{isset($purchaseReturn) ? $purchaseReturn->tax : 0}}"
                >
            </td> -->

            <!-- <td>
                <input type="text" class="form-control" readonly name="additional_payments_value" id="additional_payments"
                       value=" {{isset($purchaseReturn) ? $purchaseReturn->additional_payments : 0}}"
                >
            </td> -->
<!-- 
            <td>
                <input type="text" class="form-control" readonly name="total" id="total"
                       value="{{isset($purchaseReturn) ? $purchaseReturn->total : 0}}">
            </td>
        </tr>
        </tbody>
    </table>
</div> -->
