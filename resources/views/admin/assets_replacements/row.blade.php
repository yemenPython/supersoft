<tr class="text-center-inputs" id="item_{{$index}}">

    <td>
        <span>{{$index}}</span>
    </td>

    <td class="inline-flex-span">
        <span>{{$asset->name}}</span>
        <input type="hidden" class="assetExist" value="{{$asset->id}}" name="items[{{$index}}][asset_id]">
    </td>

    <td>
        <span>{{$assetGroup->name}}</span>
    </td>

    <td class="inline-flex-span">
        <span>{{$asset->date_of_work}}</span>
    </td>

    <td class="inline-flex-span">
        <!-- <input type="number" class="purchase_cost" readonly id="purchase_cost{{$index}}"  name="items[{{$index}}][purchase_cost]" value="{{$asset->purchase_cost}}" style="width: 100px" > -->
        <span id="purchase_cost{{$index}}"  style="background:#D7FDF9 !important">{{$asset->purchase_cost}}</span>
    </td>

    <td class="inline-flex-span">
        <span class="part-unit-span">{{$asset->annual_consumtion_rate}}</span>
        <input type="hidden" class="replacement_before" id="replacement_before{{$index}}" value="{{$asset->annual_consumtion_rate}}">
    </td>

    <td class="inline-flex-span">
        <input type="number" class="value_replacement border2"
               id="value_replacement{{$index}}"
               name="items[{{$index}}][value_replacement]"
               onkeyup="addReplacementValue('{{$index}}')"
               onchange="addReplacementValue('{{$index}}')"
               value="0" style="width: 150px">
    </td>

    <td class="inline-flex-span">
        <input type="number" max="100" min="0" class="replacement_after border5" id="replacement_after{{$index}}"
               onkeyup="addReplacementValue('{{$index}}')"
               onchange="addReplacementValue('{{$index}}')"
               name="items[{{$index}}][value_after_replacement]" value="{{$asset->annual_consumtion_rate}}" style="width: 150px" >
    </td>

    <td class="inline-flex-span">
        <input type="text" readonly
        class="border4"
         style="width: 100px" id="age{{$index}}"  name="items[{{$index}}][age]" value="0">
   
    </td>

    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
        </div>
    </td>
</tr>


