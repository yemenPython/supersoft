<div class="col-md-12">
    <div class="table-responsive scroll-table">
        <table class="table table-responsive table-hover table-bordered">
            <thead>
            <tr>
                <th width="2%"> # </th>
                <th width="10%"> {{ __('Assets Groups') }} </th>
                <th width="10%"> {{ __('Assets') }} </th>
                <th width="10%"> {{ __('Operation Date') }} </th>
                <th width="10%"> {{ __('Purchase Cost') }} </th>
                <th width="10%"> {{ __('Cost Before Replacement') }} </th>
                <th width="10%"> {{ __('Cost Replacement') }} </th>
                <th width="10%"> {{ __('Cost After Replacement') }} </th>
                <th width="12%"> {{ __('Age') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </thead>
            <tbody id="items_data">
            @if(isset($assetReplacement))
                @foreach ($assetReplacement->assetReplacementItems as $index => $item)
                    <tr class="text-center-inputs" id="item_{{$index}}">

                        <td>
                            <span>{{$index}}</span>
                        </td>

                        <td>
                            <span>{{optional($item->asset->group)->name}}</span>
                        </td>

                        <td class="inline-flex-span">
                            <span>{{optional($item->asset)->name}}</span>
                            <input type="hidden" class="assetExist" value="{{optional($item->asset)->id}}" name="items[{{$index}}][asset_id]">
                        </td>

                        <td class="inline-flex-span">
                            <span>{{optional($item->asset)->date_of_work}}</span>
                        </td>

                        <td class="inline-flex-span">
                            <input type="number" class="purchase_cost" readonly  id="purchase_cost{{$index}}" name="items[{{$index}}][purchase_cost]" value="{{optional($item->asset)->purchase_cost}}" style="width: 100px" >

                        </td>

                        <td class="inline-flex-span">
                            <span>{{optional($item->asset)->annual_consumtion_rate}}</span>
                            <input type="hidden" class="replacement_before" id="replacement_before{{$index}}"
                                   value="{{optional($item->asset)->annual_consumtion_rate}}">
                        </td>

                        <td class="inline-flex-span">
                            <input type="number" class="value_replacement"
                                   id="value_replacement{{$index}}"
                                   name="items[{{$index}}][value_replacement]"
                                   onkeyup="addReplacementValue('{{$index}}')"
                                   onchange="addReplacementValue('{{$index}}')"
                                   value="{{$item->value_replacement}}" style="width: 100px">
                        </td>

                        <td class="inline-flex-span">
                            <input type="number" class="replacement_after" id="replacement_after{{$index}}"
                                   onkeyup="addReplacementValue('{{$index}}')"
                                   onchange="addReplacementValue('{{$index}}')"
                                   name="items[{{$index}}][value_after_replacement]"
                                   value="{{$item->value_after_replacement}}" style="width: 100px" >
                        </td>

                        <td class="inline-flex-span">
                            <input type="text" readonly style="width: 100px" id="age{{$index}}"
                                   name="items[{{$index}}][age]" value="{{$item->age}}">
                        </td>

                        <td>
                            <div class="input-group" id="stores">
                                <button type="button" class="btn btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th width="2%"> # </th>
                <th width="10%"> {{ __('Assets Groups') }} </th>
                <th width="10%"> {{ __('Assets') }} </th>
                <th width="10%"> {{ __('Operation Date') }} </th>
                <th width="10%"> {{ __('Purchase Cost') }} </th>
                <th width="10%"> {{ __('Cost Before Replacement') }} </th>
                <th width="10%"> {{ __('Cost Replacement') }} </th>
                <th width="10%"> {{ __('Cost After Replacement') }} </th>
                <th width="12%"> {{ __('Age') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
