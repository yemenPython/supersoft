<tr class="text-center-inputs" id="item_{{$index}}">

    {{--  1    --}}
    <td>
        <span>{{$index}}</span>
    </td>

    {{--  2    --}}
    <td class="inline-flex-span">
        {{ optional($bankAccount->mainType)->name }}
        @if ($bankAccount->subType)
            <strong class="text-danger">[   {{ optional($bankAccount->subType)->name }}  ]</strong>
        @endif
        @if ($bankAccount->bankData)
            <strong class="text-danger">[   {{ optional($bankAccount->bankData)->name }}  ]</strong>
        @endif
        <input type="hidden" class="isItemExist" value="{{$bankAccount->id}}" name="items[{{$index}}][bank_account_id]">
    </td>

    {{--  3    --}}
    <td>
        <span>{{$bankAccount->balance}}</span>
        <input type="hidden" name="items[{{$index}}][balance]" value="{{$bankAccount->balance}}"
               class="current_balance" id="balance_item{{$index}}">
    </td>

    {{--  4    --}}
    <td>
        <input type="number" class="added_balance border5" id="added_balance{{$index}}"
               onkeyup="updateBalance('{{$index}}')"
               onchange="updateBalance('{{$index}}')"
               name="items[{{$index}}][added_balance]"
               style="width: 150px">
    </td>

    {{--  5    --}}
    <td>
        <input readonly type="number" class="total_balance border5" value="0" id="total_item_balance{{$index}}" style="width: 150px">
    </td>

    {{--  6    --}}
    <td>
        <div class="input-group" id="stores">
            <button type="button" class="btn btn-sm btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
            <a style="cursor:pointer" onclick="loadDataWithModal('{{route('admin:banks.banks_accounts.show', [$bankAccount->id])}}', '#showBankData', '#showBankDataResponse')" data-id="{{$bankAccount->id}}"
               class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Show')}}">
                <i class="fa fa-eye"></i> {{__('Show')}}
            </a>
        </div>
    </td>
</tr>


