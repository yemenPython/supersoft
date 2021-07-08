<tr class="text-center-inputs" id="tr_part_{{$index}}">
    <td>
        <span>{{$index}}</span>
    </td>
    <td>
        <span style="width: 150px !important;display:block">{{$asset?$asset->group->name:''}}</span>
    </td>

    <td class="inline-flex-span">
        <span>{{$asset?$asset->name:''}}</span>
        <input type="hidden" class="assetExist" value="{{$asset?$asset->id:''}}" name="items[{{$index}}][asset_id]">
    </td>


    <td>
        <div class="input-group">
            <input type="date" readonly class="form-control valid date_of_work_{{$index}} form-control"
                   value="{{$asset->date_of_work}}" name="items[{{$index}}][date_of_work]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text" style="width: 100px !important;"
                   readonly
                   class="form-control valid purchase_cost purchase_cost_{{$index}}"
                   value="{{$asset->purchase_cost}}"
                   onchange="totalPurchaseCost('{{$index}}');"
                   onkeyup="totalPurchaseCost('{{$index}}');"
                   name="items[{{$index}}][purchase_cost]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text" style="width: 100px !important;" class="form-control valid past_consumtion "
                   readonly
                   onchange="totalPastConsumtion('{{$index}}')" onkeyup="totalPastConsumtion('{{$index}}')"
                   value="{{$asset->past_consumtion}}"
                   name="items[{{$index}}][past_consumtion]">
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="text" readonly style="width: 100px !important;"
                   class="replacement_cost_{{$index}} form-control valid replacement_cost"
                   onchange="total_replacement_cost('{{$index}}')"
                   onkeyup="total_replacement_cost('{{$index}}')"
                   value="{{1 }}"
                   name="items[{{$index}}][replacement_cost]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text" readonly style="width: 100px !important;"
                   class="total_current_consumtion_{{$index}} form-control valid total_current_consumtion"
                   onchange="total_total_current_consumtion('{{$index}}')"
                   onkeyup="total_total_current_consumtion('{{$index}}')"
                   value="{{$asset->total_current_consumtion}}"
                   name="items[{{$index}}][total_current_consumtion]">
        </div>
    </td>


    <td>
        <div class="input-group">
            <input type="text" readonly style="width: 100px !important;"
                   class="final_total_consumtion_{{$index}} form-control valid final_total_consumtion"
                   onchange="total_final_total_consumtion('{{$index}}')"
                   onkeyup="total_final_total_consumtion('{{$index}}')"
                   value="{{$asset->past_consumtion + $asset->total_current_consumtion}}"
                   name="items[{{$index}}][final_total_consumtion]">
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="text" style="width: 100px !important;"
                   class="sale_amount_{{$index}} form-control valid sale_amount"
                   onchange="totalSaleAmount();"
                   onkeyup="totalSaleAmount();"
                   value="{{isset($saleAsset) ?$update_item->sale_amount:''}}"
                   required
                   name="items[{{$index}}][sale_amount]">
        </div>
    </td>

    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
        </div>
    </td>

    <input type="hidden" name="index" id="items_count"
           value="{{isset($saleAsset) ? $saleAsset->items->count() : 0}}">
</tr>


