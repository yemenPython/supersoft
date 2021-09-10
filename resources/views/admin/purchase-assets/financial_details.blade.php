

<div class="col-md-6">

<table class="table table-bordered">
      <tr>
      <th style="width:40%;background:#F9EFB7 !important;color:black !important">{!! __('total purchase cost') !!}</th>
      <td style="background:#F9EFB7">
      <input type="text" class="form-control"
      style="background:#F9EFB7; border:none;text-align:center !important;"
      readonly name="total_purchase_cost" id="total_purchase_cost"
                       value=" {{isset($purchaseAsset) ? $purchaseAsset->total_purchase_cost : 0}}"
                >
                </td>
      </tr>
</table>
</div>



{{--<div class="col-md-6">--}}

{{--<table class="table table-bordered">--}}
{{--      <tr>--}}
{{--      <th style="width:40%;background:#F9EFB7 !important;color:black !important">{!! __('total past consumtion') !!}</th>--}}
{{--      <td style="background:#F9EFB7">--}}
{{--      <input type="text" class="form-control"--}}
{{--      style="background:#F9EFB7; border:none;text-align:center !important;"--}}
{{--       readonly name="total_past_consumtion" id="total_past_consumtion"--}}
{{--                       value=" {{isset($purchaseAsset) ? $purchaseAsset->total_past_consumtion : 0}}"--}}
{{--                >--}}

{{--                </td>--}}
{{--      </tr>--}}
{{--</table>--}}
{{--</div>--}}



<div class="col-md-6">

<table class="table table-bordered">
      <tr>
      <th style="width:40%;background:#D2F4F6 !important;color:black !important">{!! __('paid amount') !!}</th>
      <td style="background:#D2F4F6 !important;color:black!important">
      <input type="text"
      style="background:#D2F4F6; border:none;text-align:center !important;"
      readonly class="form-control"  name="paid_amount" id="paid_amount"
                       value="{{isset($purchaseAsset) ? $purchaseAsset->paid_amount : 0}}">

                       </td>
      </tr>
</table>
</div>



<div class="col-md-6">

<table class="table table-bordered">
      <tr>
      <th style="width:40%;background:#D2F4F6 !important;color:black !important">{!! __('remaining amount') !!}</th>
      <td style="background:#D2F4F6 !important;color:black!important">
      <input type="text"
      style="background:#D2F4F6; border:none;text-align:center !important;"
      readonly class="form-control"  name="remaining_amount" id="remaining_amount"
                       value="{{isset($purchaseAsset) ? $purchaseAsset->remaining_amount : 0}}">

                       </td>
      </tr>
</table>


<br>


</div>
