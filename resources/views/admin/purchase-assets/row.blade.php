<tr class="text-center-inputs" id="tr_part_{{$index}}">

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
            <input type="text"  style="width: 150px !important;" class="form-control border2 valid purchase_cost purchase_cost_{{$index}}" value="{{isset($update_item)?$update_item->purchase_cost:$asset->purchase_cost}}" onchange="annual_consumtion_rate_value('{{$index}}');totalPurchaseCost('{{$index}}')"  onkeyup="totalPurchaseCost('{{$index}}');annual_consumtion_rate_value('{{$index}}')" name="items[{{$index}}][purchase_cost]">

    </td>

{{--    <td>--}}
{{--            <input type="text"  style="width: 150px !important;" class="form-control border1 valid past_consumtion " onchange="totalPastConsumtion('{{$index}}')" onkeyup="totalPastConsumtion('{{$index}}')" value="{{isset($update_item)?$update_item->past_consumtion:$asset->past_consumtion}}" name="items[{{$index}}][past_consumtion]">--}}

{{--    </td>--}}


    <td>
            <input type="date" class="form-control valid purchase_date_{{$index}} form-control" value="{{$asset?$asset->purchase_date:''}}" name="items[{{$index}}][purchase_date]">

    </td>

    <td>
            <input type="date" class="form-control  date_of_work_{{$index}} form-control border5"
                   id="date_of_work{{$index}}"
                   name="items[{{$index}}][date_of_work]">
    </td>


    <td>
            <input type="text"  style="width: 100px !important;" class="border4 annual_consumtion_rate_{{$index}} form-control valid" onchange="annual_consumtion_rate_value('{{$index}}')" onkeyup="annual_consumtion_rate_value('{{$index}}')" value="{{isset($update_item)?$update_item->annual_consumtion_rate:$asset->annual_consumtion_rate}}" name="items[{{$index}}][annual_consumtion_rate]">
    </td>

    <td>
        <div class="input-group">
            <input type="text" readonly style="width: 100px !important;" class="border5 asset_age_{{$index}} form-control valid"  value="{{isset($update_item)?number_format($update_item->asset_age,2):number_format($asset->asset_age,2)}}" name="items[{{$index}}][asset_age]">
        </div>
    </td>

    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-sm btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
            <a class="btn btn-sm btn-success" onclick="openModalWithShowAsset('{{$asset->id}}')"><i class="fa fa-eye"></i></a>
        </div>
    </td>


    <input type="hidden" name="index" id="items_count" value="{{isset($purchaseAsset) ? $purchaseAsset->items->count() : 0}}">
</tr>


