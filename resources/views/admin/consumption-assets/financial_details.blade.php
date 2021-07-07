<div class="form-group has-feedback col-sm-12">
    <table class="table table-bordered" style="width:100%">
        <thead>
        <tr>
            <th scope="col">{!! __('total purchase cost') !!}</th>
            <th scope="col">{!! __('total past consumtion') !!}</th>
            <th scope="col">{!! __('total replacement') !!}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" class="form-control" readonly name="total_purchase_cost" id="total_purchase_cost"
                       value=" {{isset($consumptionAsset) ? $consumptionAsset->total_purchase_cost : 0}}"
                >
            </td>

            <td>
                <input type="text" class="form-control" readonly name="total_past_consumtion" id="total_past_consumtion"
                       value=" {{isset($consumptionAsset) ? $consumptionAsset->total_past_consumtion : 0}}"
                >
            </td>
            <td>
                <input type="text" class="form-control" readonly name="total_replacement" id="total_replacement"
                       value="{{isset($consumptionAsset) ? $consumptionAsset->total_replacement : 0}}">
            </td>


        </tr>
        </tbody>
    </table>
</div>
