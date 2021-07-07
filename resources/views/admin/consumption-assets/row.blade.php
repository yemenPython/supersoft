<tr class="text-center-inputs" id="tr_part_{{$index}}">

    @if(isset($update_item->id))
        <input type="hidden" name="items[{{$index}}][consumption_asset_item_id]" value="{{$update_item->id}}">
    @else
        <input type="hidden" name="items[{{$index}}][consumption_asset_item_id]" value="new">
    @endif
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
                   onchange="annual_consumtion_rate_value('{{$index}}');totalPurchaseCost('{{$index}}')"
                   onkeyup="totalPurchaseCost('{{$index}}');annual_consumtion_rate_value('{{$index}}')"
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
                   class="net_purchase_cost_{{$index}} form-control valid"
                   onchange="annual_consumtion_rate_value('{{$index}}')"
                   onkeyup="annual_consumtion_rate_value('{{$index}}')"
                   value="{{$asset->purchase_cost - $asset->past_consumtion }}"
                   name="items[{{$index}}][net_purchase_cost]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text" readonly style="width: 100px !important;"
                   class="annual_consumtion_rate_{{$index}} form-control valid"
                   onchange="annual_consumtion_rate_value('{{$index}}')"
                   onkeyup="annual_consumtion_rate_value('{{$index}}')"
                   value="{{$asset->annual_consumtion_rate}}"
                   name="items[{{$index}}][annual_consumtion_rate]">
        </div>
    </td>


    <td>
        <div class="input-group">
            <input type="text" disabled style="width: 100px !important;"
                   class="consumption_amount_{{$index}} form-control valid"
                   onchange="annual_consumtion_rate_value('{{$index}}')"
                   onkeyup="annual_consumtion_rate_value('{{$index}}')"
                   value="{{isset($update_item)?$update_item->consumption_amount:0}}"
                   name="items[{{$index}}][consumption_amount]">
        </div>
    </td>

    {{--    <td>--}}
    {{--        <div class="input-group">--}}
    {{--            <input type="text" readonly  style="width: 100px !important;" class="form-control valid current_consumtion current_consumtion_{{$index}}" onchange="netTotal('{{$index}}')" onkeyup="netTotal('{{$index}}')" value="{{isset($update_item)?$update_item->purchase_cost - $update_item->past_consumtion: $asset->purchase_cost - $asset->past_consumtion}}" name="items[{{$index}}][current_consumtion]">--}}
    {{--        </div>--}}
    {{--    </td>--}}

    {{--    <td>--}}
    {{--        <div class="input-group">--}}
    {{--            <input type="date" class="form-control valid purchase_date_{{$index}} form-control"--}}
    {{--                   value="{{$asset?$asset->purchase_date:''}}" name="items[{{$index}}][purchase_date]">--}}
    {{--        </div>--}}
    {{--    </td>--}}


    {{--    <td>--}}
    {{--        <div class="input-group">--}}
    {{--            <input type="text" readonly style="width: 100px !important;" class="asset_age_{{$index}} form-control valid"  value="{{isset($update_item)?$update_item->asset_age:$asset->asset_age}}" name="items[{{$index}}][asset_age]">--}}
    {{--        </div>--}}
    {{--    </td>--}}

    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
        </div>
    </td>

    <input type="hidden" name="index" id="items_count"
           value="{{isset($consumptionAsset) ? $consumptionAsset->items->count() : 0}}">
</tr>


