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
        <input type="number" class="priceItem border2 price_{{$index}}" name="items[{{$index}}][price]" value="0" onkeyup="addPriceToTotal('{{$index}}');annual_consumtion_rate_value('{{$index}}')" onchange="annual_consumtion_rate_value('{{$index}}')">
    </td>
    <td>
        <div class="col-md-12">
            <div class="form-group">
                <label> {{ __('Type') }} </label>
                <div class="input-group">
                    <ul class="list-inline">
                        <li>
                            <div class="radio info">
                                <input type="radio"   id="radio_status_sale_{{$index}}" name="items[{{$index}}][consumption_type]"
                                       value="manual" disabled {{$assetGroup->consumption_type =='manual'?'checked':''}} onchange="checkType('{{$index}}','manual')">
                                <label for="radio_status_sale_{{$index}}">{{ __('Manual') }}</label>
                            </div>
                        </li>

                        <li>
                            <div class="radio info">
                                <input id="radio_status_exclusion_{{$index}}"  type="radio" name="items[{{$index}}][consumption_type]"
                                       value="automatic" disabled {{$assetGroup->consumption_type =='automatic'?'checked':''}} onchange="checkType('{{$index}}','automatic')">
                                <label
                                    for="radio_status_exclusion_{{$index}}">{{ __('Automatic') }}</label>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <input type="number" name="items[{{$index}}][age_years]" step="1" class="type_automatic{{$index}}"
                   id="age_years" placeholder="{{__('Asset Age (Years)')}}"
                   pattern="\d+"
                   value="{{$assetGroup->age_years}}"
                   @if($assetGroup->consumption_type =='manual')style="display: none" @endif>
            <input type="number" name="items[{{$index}}][age_months]" step="1"  class="type_automatic{{$index}}"
                   id="age_months" placeholder="{{__('Asset Age (Months)')}}"
                   pattern="\d+"
                   value="{{$assetGroup->age_months??0}}"
                   @if($assetGroup->consumption_type =='manual')style="display: none" @endif>
            <input type="number" name="items[{{$index}}][consumption_period]" step="1" class="type_automatic{{$index}}"
                   id="consumption_period"
                   pattern="\d+"
                   value="{{$assetGroup->consumption_period}}"
                   placeholder="{{__('Consumption Period (Months)')}}"
                   @if($assetGroup->consumption_type =='manual')style="display: none" @endif>
        </div>
        <input type="hidden"  class="group_consumption_for{{$index}}"
              value="{{$assetGroup->consumption_for}}">
        <input   type="hidden" name="items[{{$index}}][consumption_type]"
               value="{{$assetGroup->consumption_type}}">
    </td>
    <td class="">
        <input type="text"   @if($assetGroup->consumption_type =='automatic')style="display: none" @else style="width: 100px !important;" @endif class="border4 annual_consumtion_rate_{{$index}} form-control valid type_manual{{$index}}" onchange="annual_consumtion_rate_value('{{$index}}')" onkeyup="annual_consumtion_rate_value('{{$index}}')" value="{{$asset->annual_consumtion_rate}}" name="items[{{$index}}][annual_consumtion_rate]">
    </td>

    <td class="">
        <div class="input-group">
            <input type="text" readonly @if($assetGroup->consumption_type =='automatic')style="display: none" @else style="width: 100px !important;"@endif class="border5 asset_age_{{$index}} form-control valid type_manual{{$index}}"  value="" name="items[{{$index}}][expense_age]">
        </div>
    </td>
    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
            <a class="btn btn-sm btn-success" onclick="openModalWithShowAsset('{{$asset->id}}')"><i class="fa fa-eye"></i></a>

        </div>
    </td>
</tr>


