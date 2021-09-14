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


    <td class="type_asset">
            <input type="date" readonly class="form-control valid date_of_work_{{$index}} form-control"
                   value="{{$asset->date_of_work}}" name="items[{{$index}}][date_of_work]">
    </td>

    <td class="type_asset">
            <input type="text" style="width: 100px !important;"
                   readonly
                   class="form-control border2 valid purchase_cost purchase_cost_{{$index}}"
                   value="{{$asset->purchase_cost}}"
                   onchange="annual_consumtion_rate_value('{{$index}}');totalPurchaseCost('{{$index}}')"
                   onkeyup="totalPurchaseCost('{{$index}}');annual_consumtion_rate_value('{{$index}}')"
                   name="items[{{$index}}][purchase_cost]">
    </td>

    <td class="type_asset">
            <input type="text" style="width: 100px !important;" class="form-control border1 valid past_consumtion "
                   readonly
                   onchange="totalPastConsumtion('{{$index}}')" onkeyup="totalPastConsumtion('{{$index}}')"
                   value="{{$asset->past_consumtion}}"
                   name="items[{{$index}}][past_consumtion]">
    </td>
    <td class="type_asset">
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
    <td class="type_asset">
            <input type="text" readonly style="width: 100px !important;"
                   class="annual_consumtion_rate_{{$index}} border5 form-control valid"
                   onchange="annual_consumtion_rate_value('{{$index}}')"
                   onkeyup="annual_consumtion_rate_value('{{$index}}')"
                   value="{{$asset->annual_consumtion_rate}}"
                   name="items[{{$index}}][annual_consumtion_rate]">
    </td>


    <td class="type_asset">
            <input type="text" readonly style="width: 100px !important;"
                   class="consumption_amount_{{$index}} border6 form-control valid total_replacement"
                   value="{{isset($update_item)?$update_item->consumption_amount:0}}"
                   name="items[{{$index}}][consumption_amount]">
    </td>
        <td class="type_expenses">
            <div class="btn-group" style="display:flex !important;align-items:center">
    <span type="button" class="fa fa-eye dropdown-toggle eye-table-wg" data-toggle="dropdown"
          style="
    color: #a776e7;
    padding: 6px 10px;
    border-radius: 0;
    border: 1px solid #3f3f3f;
    cursor: pointer;
    font-size: 20px;
}"
          aria-haspopup="true" aria-expanded="false">
            </span>

                <ul class="dropdown-menu" style="margin-top: 19px;">
                    @if($asset->expenses()->whereHas('assetExpense',function ($q){
            $q->where('status','=','accept');
        })->get()->isNotEmpty())
                        @foreach($asset->expenses()->whereHas('assetExpense',function ($q){
            $q->where('status','=','accept');
        })->get() as $tax_index => $expense)
                            <li>
                                <span>
                               Price = {{$expense->price}}
                                <span>Consumtion Rate = {{$expense->annual_consumtion_rate}}</span>
                                    </span>
                            </li>
                            <input type="hidden" value="{{$expense->price}}" class="price_{{$tax_index}}" name="expenses[{{$tax_index}}][annual_consumtion_rate]">
                            <input type="hidden" value="{{$expense->annual_consumtion_rate}}" class="annual_consumtion_rate_{{$tax_index}}" name="expenses[{{$tax_index}}][annual_consumtion_rate]">
                            <input type="hidden" value="{{$expense->id}}" name="expenses[{{$tax_index}}][id]">
                            <input type="hidden"  name="expenses[{{$tax_index}}][total]"   id="expenses_total_hidden_{{$tax_index}}">
                        @endforeach
                    @else
                        <li>
                            <a>
                                <span>{{ __('No Expenses Founded') }}</span>
                            </a>
                        </li>

                    @endif
                </ul>
                <input style="width: 120px !important;  margin: 0 5px;" type="number" class="form-control border5 total_replacement_expenses"
                       id="expenses_total_{{$index}}"
                       value="{{isset($update_item) && $update_item->consumptionAssetItemExpenses->isNotEmpty()? $update_item->consumptionAssetItemExpenses->sum('consumption_amount') : 0 }}"
                       min="0" name="expenses[{{$index}}][total]" disabled>
            </div>
        </td>



        <td style="display: none" class="total_all">
            <input type="text" readonly style="width: 100px !important;"
                   class="total_all_{{$index}} border6 form-control valid total_all"
                   onchange="totalAll('{{$index}}')"
                   onkeyup="totalAll('{{$index}}')"

            value="{{isset($update_item)?  $update_item->consumptionAssetItemExpenses->isNotEmpty()?$update_item->consumptionAssetItemExpenses->sum('consumption_amount')+$update_item->consumption_amount :$update_item->consumption_amount : 0 }}"
            >
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


