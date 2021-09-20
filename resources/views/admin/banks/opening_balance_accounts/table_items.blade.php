<div class="col-md-12">
    <div class="table-responsive scroll-table">
        <table class="table table-responsive table-hover table-bordered">
            <thead>
            <tr>
                <th width="2%"> # </th>
                <th width="20%"> {{ __('Bank Account') }} </th>
                <th width="10%"> {{ __('Current balance') }} </th>
                <th width="10%"> {{ __('Added balance') }} </th>
                <th width="10%"> {{ __('Total') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </thead>
            <tbody id="items_data">
            @if(isset($openingBalanceAccount))
                @foreach ($openingBalanceAccount->items as $index => $item)
                    <tr class="text-center-inputs" id="item_{{$index}}">

                        {{--  1    --}}
                        <td>
                            <span>{{$index + 1}}</span>
                        </td>

                        {{--  2    --}}
                        <td class="inline-flex-span">
                            {{ optional($item->bankAccount->mainType)->name }}
                            @if ($item->bankAccount->subType)
                                <strong class="text-danger">[   {{ optional($item->bankAccount->subType)->name }}  ]</strong>
                            @endif
                            @if ($item->bankAccount)
                                <strong class="text-danger">[   {{ optional($item->bankAccount->bankData)->name }}  ]</strong>
                            @endif
                            <input type="hidden" class="isItemExist" value="{{$item->bank_account_id}}" name="items[{{$index}}][bank_account_id]">
                        </td>

                        {{--  3    --}}
                        <td>
                            <span>{{ optional($item->bankAccount)->balance }}</span>
                            <input type="hidden" name="items[{{$index}}][balance]" value="{{optional($item->bankData)->balance}}"
                                   class="current_balance" id="balance_item{{$index}}">
                        </td>

                        {{--  4    --}}
                        <td>
                            <input type="number" class="added_balance border5" id="added_balance{{$index}}"
                                   onkeyup="updateBalance('{{$index}}')"
                                   onchange="updateBalance('{{$index}}')"
                                   name="items[{{$index}}][added_balance]"
                                   style="width: 150px" value="{{$item->total}}">
                        </td>

                        {{--  5    --}}
                        <td>
                            <input readonly type="number" class="total_balance border5" value="{{ optional($item->bankAccount)->balance + $item->total }}" id="total_item_balance{{$index}}" style="width: 150px">
                        </td>

                        {{--  6    --}}
                        <td>
                            <div class="input-group" id="stores">
                                <button type="button" class="btn btn-sm btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
                                <a style="cursor:pointer" onclick="loadDataWithModal('{{route('admin:banks.banks_accounts.show', [optional($item->bankAccount->bankData)->id])}}', '#showBankData', '#showBankDataResponse')"
                                   class="btn btn-terms-wg text-white hvr-radial-out" title="{{__('Show')}}">
                                    <i class="fa fa-eye"></i> {{__('Show')}}
                                </a>
                            </div>
                        </td>
                    </tr>

                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th width="2%"> # </th>
                <th width="20%"> {{ __('Bank Account') }} </th>
                <th width="10%"> {{ __('Current balance') }} </th>
                <th width="10%"> {{ __('Added balance') }} </th>
                <th width="10%"> {{ __('Total') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </tfoot>
            <input type="hidden" name="index" id="items_count" value="{{isset($openingBalanceAccount) ? $openingBalanceAccount->items->count() : 0}}">
        </table>
    </div>
</div>
