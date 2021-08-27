<div class="col-md-12">
    <div class="table-responsive scroll-table">
        <table class="table table-responsive table-hover table-bordered">
            <thead>
            <tr>
                <th width="2%"> # </th>

                <th width="16%"> {{ __('Asset name') }} </th>

                <th width="10%"> {{ __('Asset group') }} </th>
                <th width="10%"> {{ __('Expenses Types') }} </th>
                <th width="10%"> {{ __('Expenses Items') }} </th>
                <th width="12%"> {{ __('Expense Cost') }} </th>
                <th scope="col"> {{ __('consumtion rate') }} </th>
                <th scope="col"> {{ __('asset age') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </thead>
            <tbody id="items_data">
            @if(isset($assetExpense))
                @foreach ($assetExpense->assetExpensesItems as $index => $item)
                    <tr class="text-center-inputs" id="item_{{$index + 1}}">
                        <input type="hidden" name="asset_expense_id" value="{{$assetExpense->id}}">

                        <td>
                            <span>{{$index + 1}}</span>
                        </td>



                        <td class="inline-flex-span">
                            <span>{{optional($item->asset)->name}}</span>
                            <input type="hidden" class="assetExist" value="{{optional($item->asset)->id}}" name="items[{{$index + 1}}][asset_id]">
                        </td>

                        <td>
                            <span style="width: 150px !important;display:block">{{optional($item->asset->group)->name}}</span>
                        </td>

                        <td>
                            <div class="input-group">
                                <select style="width: 150px !important;" class="form-control js-example-basic-single"
                                        id="asset_expense_type_index{{$index + 1}}"
                                onchange="getAssetItemsByAssetTypeId('{{$index + 1}}')">
                                    <option value="*">{{__('Select')}}</option>
                                    @foreach($assetExpensesTypes as $type)
                                        <option value="{{$type->id}}"
                                            {{optional($item->assetExpenseItem)->assets_type_expenses_id === $type->id ? 'selected' : ''}}>{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>

                        <td>
                            <div class="input-group">
                                <select style="width: 150px !important;" class="form-control js-example-basic-single"
                                        name="items[{{$index + 1}}][asset_expense_item_id]" id="assetExpensesItemsSelect{{$index + 1}}">
                                    @foreach($assetExpensesItems as $assetExpensesItem)
                                        <option value="{{$assetExpensesItem->id}}"
                                        {{$item->asset_expense_item_id === $assetExpensesItem->id ? 'selected' : ''}}>{{$assetExpensesItem->item}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>

                        <td>
                            <input type="number" class="priceItem border2 price_{{$index}}" name="items[{{$index + 1}}][price]" value="{{$item->price ?? 0}}" onkeyup="addPriceToTotal('{{$index + 1}}');annual_consumtion_rate_value('{{$index}}')" onchange="annual_consumtion_rate_value('{{$index}}')">

                        </td>
                        <td>
                            <input type="text"  style="width: 100px !important;" class="border4 annual_consumtion_rate_{{$index}} form-control valid" onchange="annual_consumtion_rate_value('{{$index}}')" onkeyup="annual_consumtion_rate_value('{{$index}}')" value="{{$item->annual_consumtion_rate}}" name="items[{{$index}}][annual_consumtion_rate]">
                        </td>

                        <td>
                            <div class="input-group">
                                <input type="text" readonly style="width: 100px !important;" class="border5 asset_age_{{$index}} form-control valid"  value="{{$item->price?number_format(($item->price / $item->annual_consumtion_rate) /100,2):''}}" name="items[{{$index}}][expense_age]">
                            </div>
                        </td>

                        <td>
                            <div class="input-group" id="stores">
                                <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index + 1}}')"></button>
                                <a class="btn btn-sm btn-success" onclick="openModalWithShowAsset('{{optional($item->asset)->id}}')"><i class="fa fa-eye"></i></a>

                            </div>
                        </td>
                    </tr>



                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th width="2%"> # </th>

                <th width="16%"> {{ __('Asset name') }} </th>

                <th width="10%"> {{ __('Asset group') }} </th>
                <th width="10%"> {{ __('Expenses Types') }} </th>
                <th width="10%"> {{ __('Expenses Items') }} </th>
                <th width="12%"> {{ __('Expense Cost') }} </th>
                <th scope="col"> {{ __('consumtion rate') }} </th>
                <th scope="col"> {{ __('asset age') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </tfoot>
            <input type="hidden" name="index" id="items_count" value="{{isset($assetExpense) ?$assetExpense->assetExpensesItems->count() : 0}}">
        </table>
    </div>
</div>
