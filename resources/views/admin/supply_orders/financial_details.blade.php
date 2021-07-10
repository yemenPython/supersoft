<div class="col-md-12 input-height">

<div class="col-md-6">
   
<table class="table table-bordered">
      <tr>
      <th style="width:40%;height:50px;background:#F9EFB7 !important;color:black !important">{!! __('Total') !!}</th>
      <td style="background:#F9EFB7">
                <input type="text" class="form-control"
                style="background:#F9EFB7; border:none;text-align:center !important;"
                 readonly name="sub_total" id="sub_total"
                       value="{{isset($supplyOrder) ? $supplyOrder->sub_total : 0}}">
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
                           {{isset($supplyOrder) && $supplyOrder->discount_type == 'amount' ? 'checked': '' }}
                           value="amount"
                           {{!isset($supplyOrder) ? 'checked':''}} onclick="calculateInvoiceDiscount()">
                    <label for="discount_type_amount">{{__('amount')}}</label>
                </div>
                        </li>
                        <li>
                        <div class="radio primary remove-padd-marg">
                    <input type="radio" name="discount_type"
                           {{isset($supplyOrder) && $supplyOrder->discount_type == 'percent' ? 'checked': '' }}
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
                                {{isset($supplyOrder) && $supplyOrder->supplier_discount_active ? 'checked' : ''}}>
                                </div>
  </li>

  <li>

<input type="number" name="supplier_discount" min="0" readonly="readonly"
style="background:#D2F4F6; border:none;text-align:center !important;"
       class="form-control supplier_discount"
       value="{{isset($supplyOrder) ? $supplyOrder->supplier_discount : 0}}"
>

</li>

  <li>

                        <input type="text" disabled="disabled" class="form-control supplier_discount_type"
                               value="{{isset($supplyOrder) && $supplyOrder->supplier_discount_type == 'percent' ? '%' : '$'}}"
                               style="width: 42px;">

                        <input type="hidden" name="supplier_discount_type" 
                        style="width: 42px;"
                         class="supplier_discount_type_value"
                               value="{{isset($supplyOrder) ? $supplyOrder->supplier_discount_type : 'amount'}}"
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
      <input type="number" class="form-control text-center"
                       value="{{isset($supplyOrder) ? $supplyOrder->discount : 0}}"
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
      <td  style="background:#FFC5D7">
      <input type="text" class="form-control" readonly
      style="background:#FFC5D7; border:none;text-align:center !important;"
                       value="{{isset($supplyOrder) ? $supplyOrder->total_after_discount : 0}}"
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
                                               {{!isset($supplyOrder) ? 'checked':''}}
                                               {{isset($supplyOrder) && in_array($tax->id, $supplyOrder->taxes->pluck('id')->toArray())? 'checked':'' }}
                                               data-tax-value="{{$tax->value}}"
                                               data-tax-type="{{$tax->tax_type}}"
                                               data-tax-execution-time="{{$tax->execution_time}}"
                                               value="{{$tax->id}}"
                                        >
                                        <span>
                                            {{$tax->name}} ( {{ $tax->value }} {{$tax->tax_type == 'amount' ? '$':'%'}} ) =
                                             <span id="calculated_tax_value_{{$tax_key}}">
                                                  {{isset($supplyOrder) ? taxValueCalculated($supplyOrder->total_after_discount, $supplyOrder->sub_total, $tax) : 0}}
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
                       value=" {{isset($supplyOrder) ? $supplyOrder->tax : 0}}"
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
                    <span type="button" class="fa fa-eye eye-design-two  dropdown-toggle" data-toggle="dropdown"
                          
                          aria-haspopup="true" aria-expanded="false"> </span>

                          <ul class="dropdown-menu" style="margin-top: 19px;">
                        @if( $data['additionalPayments']->count())
                            @foreach( $data['additionalPayments'] as $additional_key => $additionalPayment)

                                @php
                                    $additional_key +=1;
                                @endphp

                                <li>
                                    <a>
                                    <input type="checkbox" id="checkbox_additional_{{$additional_key}}" name="additional_payments[]"
                                               onclick="calculateTotal()"
                                               {{!isset($supplyOrder) ? 'checked':''}}
                                               {{isset($supplyOrder) && in_array($additionalPayment->id, $supplyOrder->taxes->pluck('id')->toArray())? 'checked':'' }}
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
                                                 {{isset($supplyOrder) ? taxValueCalculated($supplyOrder->total_after_discount, $supplyOrder->sub_total, $additionalPayment ) : 0}}
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
                       value=" {{isset($supplyOrder) ? $supplyOrder->additional_payments : 0}}"
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
                       value="{{isset($supplyOrder) ? $supplyOrder->total : 0}}">
            </td>
      </tr>

</table>

</div>
</div>

