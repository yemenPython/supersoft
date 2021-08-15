<tr class="text-center-inputs" id="tr_part_{{$index}}">

    @if(isset($update_item->id))
        <input type="hidden" name="items[{{$index}}][consumption_asset_item_id]" value="{{$update_item->id}}">
    @else
        <input type="hidden" name="items[{{$index}}][consumption_asset_item_id]" value="new">
    @endif
    <td>
        <span>{{$index}}</span>
    </td>

    <td class="inline-flex-span">
        <span style="width: 150px !important;display:block">{{$asset?$asset->name:''}}</span>
        <input type="hidden" class="assetExist" value="{{$asset?$asset->id:''}}" name="items[{{$index}}][asset_id]">
    </td>

    <td>
        <span style="width: 150px !important;display:block">{{$asset?$asset->group->name:''}}</span>
    </td>


    <td>
            <input type="date" readonly class="form-control valid date_of_work_{{$index}} form-control"
                   value="{{$asset->date_of_work}}" name="items[{{$index}}][date_of_work]">
    </td>

    <td>
            <input type="text" style="width: 100px !important;"
                   readonly
                   class="form-control border2 valid purchase_cost purchase_cost_{{$index}}"
                   value="{{$asset->purchase_cost}}"
                   onchange="annual_consumtion_rate_value('{{$index}}');totalPurchaseCost('{{$index}}')"
                   onkeyup="totalPurchaseCost('{{$index}}');annual_consumtion_rate_value('{{$index}}')"
                   name="items[{{$index}}][purchase_cost]">
    </td>

    <td>
            <input type="text" style="width: 100px !important;" class="form-control border1 valid past_consumtion "
                   readonly
                   onchange="totalPastConsumtion('{{$index}}')" onkeyup="totalPastConsumtion('{{$index}}')"
                   value="{{$asset->past_consumtion}}"
                   name="items[{{$index}}][past_consumtion]">
    </td>
    <td>
            <input type="text" readonly style="width: 100px !important;"
                   class="net_purchase_cost_{{$index}} border4 form-control valid"
                   onchange="annual_consumtion_rate_value('{{$index}}')"
                   onkeyup="annual_consumtion_rate_value('{{$index}}')"
                   value="{{$asset->purchase_cost - $asset->past_consumtion }}"
                   name="items[{{$index}}][net_purchase_cost]">
    </td>
        <input type="hidden" readonly style="width: 100px !important;"
               class="total_replacements_{{$index}} border5 form-control valid"
               onchange="annual_consumtion_rate_value('{{$index}}')"
               onkeyup="annual_consumtion_rate_value('{{$index}}')"
               value="{{$asset->total_replacements }}"
               name="items[{{$index}}][total_replacements]">
    <td>
            <input type="text" readonly style="width: 100px !important;"
                   class="annual_consumtion_rate_{{$index}} border5 form-control valid"
                   onchange="annual_consumtion_rate_value('{{$index}}')"
                   onkeyup="annual_consumtion_rate_value('{{$index}}')"
                   value="{{$asset->annual_consumtion_rate}}"
                   name="items[{{$index}}][annual_consumtion_rate]">
    </td>


    <td>
            <input type="text" readonly style="width: 100px !important;"
                   class="consumption_amount_{{$index}} border6 form-control valid total_replacement"
                   value="{{isset($update_item)?$update_item->consumption_amount:0}}"
                   name="items[{{$index}}][consumption_amount]">
    </td>


    <td>
        <div id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
            <a class="btn btn-sm btn-success" onclick="openModalWithShowAsset('{{$asset->id}}')"><i class="fa fa-eye"></i></a>
        </div>
    </td>

    <input type="hidden" name="index" id="items_count"
           value="{{isset($consumptionAsset) ? $consumptionAsset->items->count() : 0}}">
</tr>


