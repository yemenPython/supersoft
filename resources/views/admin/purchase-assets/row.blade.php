<tr class="text-center-inputs" id="tr_part_{{$index}}">

    <td>
        <span>{{$index}}</span>
    </td>


    <td>
        <span style="width: 150px !important;display:block">{{$asset->group->name}}</span>
    </td>

    <td class="inline-flex-span">
        <span>{{$asset->name}}</span>
        <input type="hidden" class="assetExist" value="{{$asset->id}}" name="items[{{$index}}][asset_id]">
    </td>

    <td>
        <div class="input-group">
            <input type="text"  style="width: 100px !important;" class="form-control valid purchase_cost purchase_cost_{{$index}}" value="{{$asset->purchase_cost}}" onchange="annual_consumtion_rate_value('{{$index}}');totalPurchaseCost('{{$index}}')"  onkeyup="totalPurchaseCost('{{$index}}');annual_consumtion_rate_value('{{$index}}')" name="items[{{$index}}][purchase_cost]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text"  style="width: 100px !important;" class="form-control valid past_consumtion " onchange="totalPastConsumtion('{{$index}}')" onkeyup="totalPastConsumtion('{{$index}}')" value="{{$asset->past_consumtion}}" name="items[{{$index}}][past_consumtion]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text" readonly  style="width: 100px !important;" class="form-control valid current_consumtion current_consumtion_{{$index}}" onchange="netTotal('{{$index}}')" onkeyup="netTotal('{{$index}}')" value="{{$asset->purchase_cost - $asset->past_consumtion}}" name="items[{{$index}}][current_consumtion]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="date" class="form-control valid purchase_date_{{$index}} form-control" value="{{$asset->purchase_date}}" name="items[{{$index}}][purchase_date]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="date" class="form-control valid date_of_work_{{$index}} form-control" value="{{$asset->date_of_work}}" name="items[{{$index}}][date_of_work]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text"  style="width: 100px !important;" class="annual_consumtion_rate_{{$index}} form-control valid" onchange="annual_consumtion_rate_value('{{$index}}')" onkeyup="annual_consumtion_rate_value('{{$index}}')" value="{{$asset->annual_consumtion_rate}}" name="items[{{$index}}][annual_consumtion_rate]">
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text"  style="width: 100px !important;" class="asset_age_{{$index}} form-control valid"  value="{{$asset->asset_age}}" name="items[{{$index}}][asset_age]">
        </div>
    </td>

    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
        </div>
    </td>

    <input type="hidden" name="index" id="items_count" value="{{isset($purchaseAsset) ? $purchaseAsset->items->count() : 0}}">
</tr>


