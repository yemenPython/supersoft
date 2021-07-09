<div class="col-md-12 input-height">

<div class="col-md-6">
   
<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#FFC5D7 !important;color:black !important">{!! __('Total') !!}</th>
      <td style="background:#FFC5D7">
                <input type="text" class="form-control" readonly name="sub_total" id="sub_total"
                style="background:#FFC5D7; border:none;text-align:center !important;"
                       value="{{isset($purchaseQuotation) ? $purchaseQuotation->sub_total : 0}}">
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
                           {{isset($purchaseQuotation) && $purchaseQuotation->discount_type == 'amount' ? 'checked': '' }}
                           value="amount"
                           {{!isset($purchaseQuotation) ? 'checked':''}} onclick="calculateInvoiceDiscount()">
                    <label for="discount_type_amount">{{__('amount')}}</label>
                </div>
                        </li>
                        <li>
                        <div class="radio primary remove-padd-marg">
                    <input type="radio" name="discount_type"
                           {{isset($purchaseQuotation) && $purchaseQuotation->discount_type == 'percent' ? 'checked': '' }}
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
            <input type="checkbox" 
            
            id="supplier_discount_check" name="supplier_discount_active" onclick="calculateTotal()"
                {{isset($purchaseQuotation) && $purchaseQuotation->supplier_discount_active ? 'checked' : ''}}>
        </div>
  </li>

  <li>
  <input type="number" name="supplier_discount" min="0" readonly="readonly"

               class="form-control text-center supplier_discount"
               
               value="{{isset($purchaseQuotation) ? $purchaseQuotation->supplier_discount : 0}}">
  </li>

  <li>
  <input type="text" disabled="disabled" 

  class="form-control text-center supplier_discount_type"
               value="{{isset($purchaseQuotation) && $purchaseQuotation->supplier_discount_type == 'percent' ? '%' : '$'}}"
               style="width: 42px;">

        <input type="hidden" name="supplier_discount_type" 

         class="supplier_discount_type_value"
               value="{{isset($purchaseQuotation) ? $purchaseQuotation->supplier_discount_type : 'amount'}}">
    
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
                <input type="number" class="form-control text-center"
                
                       value="{{isset($purchaseQuotation) ? $purchaseQuotation->discount : 0}}"
                       id="discount"
                       onchange="calculateInvoiceDiscount()"
                       onkeyup="calculateInvoiceDiscount()"
                       name="discount" min="0">
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
      <td>
                <input type="text" class="form-control" readonly
                       value="{{isset($purchaseQuotation) ? $purchaseQuotation->total_after_discount : 0}}"
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
                    <span type="button" class="eye-design-one btn-1 dropdown-toggle" data-toggle="dropdown"
                          
                          aria-haspopup="true" aria-expanded="false">                   <i class="fa fa-eye"></i>
</span>


                    <ul class="dropdown-menu for-design-eye" style="margin-top: 19px;">
                        @if($taxes->count())
                            @foreach($taxes as $tax_key => $tax)

                                @php
                                    $tax_key +=1;
                                @endphp

                                <li>
                                    <a>
                                        <input type="checkbox" id="checkbox_tax_{{$tax_key}}" name="taxes[]"
                                               onclick="calculateTax()"
                                               {{!isset($purchaseQuotation) ? 'checked':''}}
                                               {{isset($purchaseQuotation) && in_array($tax->id, $purchaseQuotation->taxes->pluck('id')->toArray())? 'checked':'' }}
                                               data-tax-value="{{$tax->value}}"
                                               data-tax-type="{{$tax->tax_type}}"
                                               data-tax-execution-time="{{$tax->execution_time}}"
                                               value="{{$tax->id}}"
                                        >
                                        <span>
                                            {{$tax->name}} - {{$tax->tax_type == 'amount' ? '$':'%'}} - {{ $tax->value }} -
                                             <span id="calculated_tax_value_{{$tax_key}}">
                                                  {{isset($purchaseQuotation) ? taxValueCalculated($purchaseQuotation->total_after_discount, $purchaseQuotation->sub_total, $tax) : 0}}
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

                    <input type="hidden" id="invoice_tax_count" value="{{$taxes->count()}}">
                </div>

                {!! __('Taxes') !!}
            </th>
            <td>
                <input type="text" class="form-control" readonly name="tax" id="tax"
                       value=" {{isset($purchaseQuotation) ? $purchaseQuotation->tax : 0}}"
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
      <th scope="col" style="width: 40%;height:50px">
                <div class="btn-group ">
                    <span type="button" class="eye-design-two btn-1 dropdown-toggle" data-toggle="dropdown"
                          
                          aria-haspopup="true" aria-expanded="false">
                          <i class="fa fa-eye"></i>

                        </span>

                    <ul class="dropdown-menu for-design-eye" style="margin-top: 19px;">
                        @if($additionalPayments->count())
                            @foreach($additionalPayments as $additional_key => $additionalPayment)

                                @php
                                    $additional_key +=1;
                                @endphp

                                <li>
                                    <a>
                                        <input type="checkbox" id="checkbox_additional_{{$additional_key}}" name="additional_payments[]"
                                               onclick="calculateTotal()"
                                               {{!isset($purchaseQuotation) ? 'checked':''}}
                                               {{isset($purchaseQuotation) && in_array($additionalPayment->id, $purchaseQuotation->taxes->pluck('id')->toArray())? 'checked':'' }}
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
                                                 {{isset($purchaseQuotation) ? taxValueCalculated($purchaseQuotation->total_after_discount, $purchaseQuotation->sub_total, $additionalPayment ) : 0}}
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

                    <input type="hidden" id="invoice_additional_count" value="{{$additionalPayments->count()}}">
                </div>

                {!! __('Additional Payments') !!}
            </th>
            <td>
                <input type="text" class="form-control" readonly name="additional_payments_value" id="additional_payments"
                       value=" {{isset($purchaseQuotation) ? $purchaseQuotation->additional_payments : 0}}"
                >
            </td>
      </tr>
</table>
</div>

<div class="col-md-6">
<table class="table table-bordered">
      <tr>
      <th scope="col" style="width: 40%;height:50px">{!! __('Final Total') !!}</th>
      <td>
                <input type="text" class="form-control" readonly name="total" id="total"
                       value="{{isset($purchaseQuotation) ? $purchaseQuotation->total : 0}}">
            </td>
      </tr>

</table>

</div>
</div>
