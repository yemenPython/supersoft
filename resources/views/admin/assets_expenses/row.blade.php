<tr class="text-center-inputs" id="item_{{$index}}">

    <td>
        <span>{{$index}}</span>
    </td>

    <td class="inline-flex-span">
        <span>{{$asset->name}}</span>
        <input type="hidden" class="assetExist" value="{{$asset->id}}" name="items[{{$index}}][asset_id]">
    </td>

    <td>
        <span style="width: 150px !important;display:block">{{$assetGroup->name}}</span>
    </td>



    <td>
        <div class="input-group">
            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    id="asset_expense_type_index{{$index}}"
                    onchange="getAssetItemsByAssetTypeId('{{$index}}')">
                <option value="*">{{__('Select')}}</option>
                @foreach($assetExpensesTypes as $type)
                    <option value="{{$type->id}}">{{$type->name}}</option>
                @endforeach
            </select>
        </div>
    </td>

    <td>
        <div class="input-group">
            <select style="width: 150px !important;" class="form-control js-example-basic-single"
                    name="items[{{$index}}][asset_expense_item_id]" id="assetExpensesItemsSelect{{$index}}">
                @foreach($assetExpensesItems as $item)
                    <option value="{{$item->id}}">{{$item->item}}</option>
                @endforeach
            </select>
        </div>
    </td>

    <td>
        <input type="number" class="priceItem border2" name="items[{{$index}}][price]" value="0" onkeyup="addPriceToTotal('{{$index}}')">
    </td>
    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
            <a class="btn btn-sm btn-success" onclick="openModalWithShowAsset('{{$asset->id}}')"><i class="fa fa-eye"></i></a>

        </div>
    </td>
</tr>


