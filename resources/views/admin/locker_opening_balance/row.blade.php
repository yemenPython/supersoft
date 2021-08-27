<tr class="text-center-inputs" id="item_{{$index}}">

    <td>
        <span>{{$index}}</span>
    </td>

    <td class="inline-flex-span">
        <span>{{$locker->name}}</span>
        <input type="hidden" class="assetExist" value="{{$locker->id}}" name="items[{{$index}}][locker_id]">
    </td>

    @if ($setting->active_multi_currency)
        <td class="inline-flex-span">
            <div class="form-group has-feedback">
                <select class="form-control js-example-basic-single" name="items[{{$index}}][currency_id]" required>
                    <option value="">{{__('Select')}}</option>
                    @foreach($currencies as $currency)
                        <option class="bg-success" value="{{ $currency->id }}">{{ $currency->name }}</option>
                    @endforeach
                </select>
            </div>
        </td>
    @endif

    <td>
        <span>{{$locker->balance}}</span>
        <input type="hidden"  name="items[{{$index}}][current_balance]" value="{{$locker->balance}}" class="current_balance" id="current_balance_item{{$index}}">
    </td>

    <td>
        <input type="number" class="added_balance border5" id="added_balance{{$index}}"
               onkeyup="updateBalance('{{$index}}')"
               onchange="updateBalance('{{$index}}')"
               name="items[{{$index}}][added_balance]"
               style="width: 150px">
    </td>

    <td>
        <input readonly type="number" class="total_balance border5" value="0" id="total_item_balance{{$index}}" style="width: 150px">
    </td>

    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-sm btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
        </div>
    </td>
</tr>


