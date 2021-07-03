<div class="form-group has-feedback col-sm-12">
    <table class="table table-bordered" style="width:100%">
        <thead>
        <tr>
            <th scope="col">{!! __('total purchase cost') !!}</th>
            <th scope="col">{!! __('total past consumtion') !!}</th>
            <th scope="col">{!! __('net total') !!}</th>
            <th scope="col">{!! __('paid amount') !!}</th>
            <th scope="col">{!! __('remaining amount') !!}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" class="form-control" readonly name="total_purchase_cost" id="total_purchase_cost"
                       value=" {{isset($purchaseAsset) ? $purchaseAsset->total_purchase_cost : 0}}"
                >
            </td>

            <td>
                <input type="text" class="form-control" readonly name="total_past_consumtion" id="total_past_consumtion"
                       value=" {{isset($purchaseAsset) ? $purchaseAsset->total_past_consumtion : 0}}"
                >
            </td>
            <td>
                <input type="text" class="form-control" readonly name="net_total" id="net_total"
                       value="{{isset($purchaseAsset) ? $purchaseAsset->net_total : 0}}">
            </td>
            <td>
                <input type="text" readonly class="form-control"  name="remaining_amount" id="remaining_amount"
                       value="{{isset($purchaseAsset) ? $purchaseAsset->remaining_amount : 0}}">
            </td>
            <td>
                <input type="text" readonly class="form-control"  name="paid_amount" id="paid_amount"
                       value="{{isset($purchaseAsset) ? $purchaseAsset->paid_amount : 0}}">
            </td>
        </tr>
        </tbody>
    </table>
</div>
