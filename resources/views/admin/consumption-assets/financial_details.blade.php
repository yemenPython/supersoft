
<table class="table table-bordered">
      <tr>
      <th style="width:30%;background:#FFC5D7 !important;color:black !important">{!! __('total purchase cost') !!}</th>
      <td style="background:#FFC5D7">
      <input type="text" class="form-control"
      style="background:#FFC5D7; border:none;text-align:center !important;"
       readonly name="total_purchase_cost" id="total_purchase_cost"
                       value=" {{isset($consumptionAsset) ? $consumptionAsset->total_purchase_cost : 0}}"
                >
                </td>
      </tr>
</table>




<table class="table table-bordered">
      <tr>
      <th style="width:30%;background:#F9EFB7 !important;color:black !important">{!! __('total past consumtion') !!}</th>
      <td style="background:#F9EFB7">
      <input type="text" class="form-control"
      style="background:#F9EFB7; border:none;text-align:center !important;"

      readonly name="total_past_consumtion" id="total_past_consumtion"
                       value=" {{isset($consumptionAsset) ? $consumptionAsset->total_past_consumtion : 0}}"
                >

                </td>
      </tr>
</table>


<table class="table table-bordered">
      <tr>
      <th style="width:30%;background:#D2F4F6 !important;color:black !important">{!! __('total consumtion') !!}</th>
      <td style="background:#D2F4F6 !important;color:black!important">
      <input type="text" class="form-control"
      style="background:#D2F4F6; border:none;text-align:center !important;"
       readonly name="total_replacement" id="total_replacement"
                       value="{{isset($consumptionAsset)? number_format($consumptionAsset->total_replacement,2) : 0}}">

                       </td>
      </tr>
</table>


