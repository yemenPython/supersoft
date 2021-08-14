<div class="col-md-12">
    <div class="table-responsive scroll-table">
        <table class="table table-responsive table-hover table-bordered">
            <thead>
            <tr>
                <th width="2%"> # </th>

                <th width="10%"> {{ __('Asset name') }} </th>
                <th width="10%"> {{ __('Asset group') }} </th>
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
                    <tr class="text-center-inputs" id="item_{{$index + 1}}">

                        <td>
                            <span>{{$index + 1}}</span>
                        </td>



                        <td class="inline-flex-span">
                            <span>{{optional($item->asset)->name}}</span>
                            <input type="hidden" class="assetExist" value="{{optional($item->asset)->id}}" name="items[{{$index + 1}}][asset_id]">
                        </td>

                        <td>
                            <span>{{optional($item->asset->group)->name}}</span>
                        </td>

                        <td class="inline-flex-span">
                            <span>{{optional($item->asset)->date_of_work}}</span>
                        </td>

                        <td class="inline-flex-span">
                            <input type="hidden" class="purchase_cost" id="purchase_cost{{$index + 1}}" value="{{optional($item->asset)->purchase_cost}}">
                            <span style="background:#D7FDF9 !important">{{optional($item->asset)->purchase_cost}}</span>
                        </td>

                        <td class="inline-flex-span">
                            <span class="part-unit-span">{{optional($item->asset)->annual_consumtion_rate}}</span>
                            <input type="hidden" class="replacement_before" id="replacement_before{{$index + 1}}"
                                   value="{{optional($item->asset)->annual_consumtion_rate}}">
                        </td>

                        <td class="inline-flex-span">
                            <input type="number" class="value_replacement border3"
                                   id="value_replacement{{$index + 1}}"
                                   name="items[{{$index + 1}}][value_replacement]"
                                   onkeyup="addReplacementValue('{{$index + 1}}')"
                                   onchange="addReplacementValue('{{$index + 1}}')"
                                   value="{{$item->value_replacement}}" style="width: 150px">
                        </td>

                        <td class="inline-flex-span">
                            <input type="number" class="replacement_after border4" id="replacement_after{{$index + 1}}"
                                   onkeyup="addReplacementValue('{{$index + 1}}')"
                                   onchange="addReplacementValue('{{$index + 1}}')"
                                   name="items[{{$index + 1}}][value_after_replacement]"
                                   value="{{$item->value_after_replacement}}" style="width: 150px" >
                        </td>

                        <td class="inline-flex-span">
                            <input type="text" readonly
                            class="border4"
                             style="width: 100px" id="age{{$index + 1}}"
                                   name="items[{{$index + 1}}][age]" value="{{$item->age}}">

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

                <th width="10%"> {{ __('Asset name') }} </th>
                <th width="10%"> {{ __('Asset group') }} </th>
                <th width="10%"> {{ __('Operation Date') }} </th>
                <th width="10%"> {{ __('Purchase Cost') }} </th>
                <th width="10%"> {{ __('Cost Before Replacement') }} </th>
                <th width="10%"> {{ __('Cost Replacement') }} </th>
                <th width="10%"> {{ __('Cost After Replacement') }} </th>
                <th width="12%"> {{ __('Age') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </tfoot>
            <input type="hidden" name="index" id="items_count" value="{{isset($assetReplacement) ? $assetReplacement->assetReplacementItems->count() : 0}}">

        </table>
    </div>
</div>
