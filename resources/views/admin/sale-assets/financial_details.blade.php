<div class="form-group has-feedback col-sm-12">
    <table class="table table-bordered" style="width:100%">
        <thead>
        <tr>
            <th scope="col">{!! __('total purchase cost') !!}</th>
            <th scope="col">{!! __('total past consumtion') !!}</th>
            <th scope="col">{!! __('total replacement') !!}</th>
            <th scope="col">{!! __('total  consumtion') !!}</th>
            <th scope="col">{!! __('final total consumtion') !!}</th>
            <th scope="col">{!! __('total sale amount') !!}</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" class="form-control" readonly name="total_purchase_cost" id="total_purchase_cost"
                       value=" {{isset($saleAsset) ? $saleAsset->total_purchase_cost : 0}}"
                >
            </td>

            <td>
                <input type="text" class="form-control" readonly name="total_past_consumtion" id="total_past_consumtion"
                       value=" {{isset($saleAsset) ? $saleAsset->total_past_consumtion : 0}}"
                >
            </td>
            <td>
                <input type="text" class="form-control" readonly name="total_replacement" id="total_replacement"
                       value="{{isset($saleAsset) ? $saleAsset->total_replacement : 0}}">
            </td>
            <td>
                <input type="text" class="form-control" readonly name="total_current_consumtion"
                       id="total_current_consumtion"
                       value="{{isset($saleAsset) ? $saleAsset->total_current_consumtion : 0}}">
            </td>
            <td>
                <input type="text" class="form-control" readonly name="final_total_consumtion"
                       id="final_total_consumtion"
                       value="{{isset($saleAsset) ? $saleAsset->final_total_consumtion : 0}}">
            </td>
            <td>
                <input type="text" class="form-control" readonly name="total_sale_amount"
                       id="total_sale_amount"
                       value="{{isset($saleAsset) ? $saleAsset->total_sale_amount : 0}}">
            </td>


        </tr>
        </tbody>
    </table>
</div>
