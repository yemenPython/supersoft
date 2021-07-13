

<div class="col-md-6">
   
   <table class="table table-bordered">
         <tr>
         <th style="width:40%;background:#F9EFB7 !important;color:black !important">{!! __('total purchase cost') !!}</th>
         <td style="background:#F9EFB7">
         <input type="text" class="form-control"
         style="background:#F9EFB7; border:none;text-align:center !important;"
          readonly name="total_purchase_cost" id="total_purchase_cost"
                       value=" {{isset($saleAsset) ? $saleAsset->total_purchase_cost : 0}}"
                >
                </td>
      </tr>
</table>
</div>


<div class="col-md-6">

<table class="table table-bordered">
      <tr>
      <th style="width:40%;background:#F9EFB7 !important;color:black !important">{!! __('total past consumtion') !!}</th>
      <td style="background:#F9EFB7">
      <input type="text" class="form-control" 
      style="background:#F9EFB7; border:none;text-align:center !important;"
      readonly name="total_past_consumtion" id="total_past_consumtion"
                       value=" {{isset($saleAsset) ? $saleAsset->total_past_consumtion : 0}}"
                >
                </td>
      </tr>
</table>
</div>



<div class="col-md-6">

<table class="table table-bordered">
      <tr>
      <th style="width:40%;background:#D2F4F6 !important;color:black !important">
      {!! __('total replacement') !!}</th>
      <td style="background:#D2F4F6 !important;color:black!important">

      <input type="text" class="form-control" 
      style="background:#D2F4F6; border:none;text-align:center !important;"
      readonly name="total_replacement" id="total_replacement"
                       value="{{isset($saleAsset) ? $saleAsset->total_replacement : 0}}">

                       </td>
      </tr>
</table>
</div>




<div class="col-md-6">
      
<table class="table table-bordered">
      <tr>
      <th style="width:40%;background:#D2F4F6 !important;color:black !important">
      {!! __('total consumtion') !!}
      </th>
      <td style="background:#D2F4F6 !important;color:black!important">
      <input type="text" class="form-control" 
      style="background:#D2F4F6; border:none;text-align:center !important;"
      readonly name="total_current_consumtion"
                       id="total_current_consumtion"
                       value="{{isset($saleAsset) ? $saleAsset->total_current_consumtion : 0}}">
                       </td>
      </tr>
</table>
</div>

<div class="col-md-6">
      
<table class="table table-bordered">
      <tr>
      <th style="width:40%;background:#FFC5D7 !important;color:black !important">
      {!! __('final total consumption') !!}
      </th>
      <td style="background:#FFC5D7 !important;color:black!important">
      <input type="text" class="form-control"
      style="background:#FFC5D7; border:none;text-align:center !important;"
       readonly name="final_total_consumtion"
                       id="final_total_consumtion"
                       value="{{isset($saleAsset) ? $saleAsset->final_total_consumtion : 0}}">
                       </td>
      </tr>
</table>
</div>

<div class="col-md-6">
      
<table class="table table-bordered">
      <tr>
      <th style="width:40%;background:#FFC5D7 !important;color:black !important">
      {!! __('total sale amount') !!}
      </th>
      <td style="background:#FFC5D7 !important;color:black!important">
      <input type="text" class="form-control"
      style="background:#FFC5D7; border:none;text-align:center !important;"
       readonly name="total_sale_amount"
                       id="total_sale_amount"
                       value="{{isset($saleAsset) ? $saleAsset->total_sale_amount : 0}}">
                       </td>
      </tr>
</table>
<br>

</div>

