<div class="col-md-12">
    <div class="table-responsive scroll-table">
        <table class="table table-responsive table-hover table-bordered">
            <thead>
            <tr>
                <th width="2%"> # </th>
                <th width="10%"> {{ __('Locker name') }} </th>
                @if ($setting->active_multi_currency)
                    <th width="10%"> {{ __('Select Currency') }} </th>
                @endif
                <th width="10%"> {{ __('Current balance') }} </th>
                <th width="10%"> {{ __('Added balance') }} </th>
                <th width="10%"> {{ __('Total') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </thead>
            <tbody id="items_data">
            @if(isset($lockerOpeningBalance))
                @foreach ($lockerOpeningBalance->items as $index => $item)
                    <tr class="text-center-inputs" id="item_{{$index}}">
                        <input type="hidden" name="items[{{$index}}][id]" value="{{$item->id}}">
                        <td>
                            <span>{{$index + 1}}</span>
                        </td>

                        <td class="inline-flex-span">
                            <span>{{optional($item->locker)->name}}</span>
                            <input type="hidden" class="assetExist" value="{{optional($item->locker)->id}}" name="items[{{$index}}][locker_id]">
                        </td>

                        @if ($setting->active_multi_currency)
                            <td class="inline-flex-span">
                                <div class="form-group has-feedback">
                                    <select class="form-control js-example-basic-single" name="items[{{$index}}][currency_id]" required>
                                        <option value="">{{__('Select')}}</option>
                                        @foreach($currencies as $currency)
                                            <option {{$item->currency_id == $currency->id ? 'selected' : ''}} value="{{ $currency->id }}">{{ $currency->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                        @endif

                        <td>
                            <span>{{$item->current_balance}}</span>
                            <input type="hidden"  name="items[{{$index}}][current_balance]" value="{{$item->current_balance}}" class="current_balance" id="current_balance_item{{$index}}">
                        </td>

                        <td>
                            <input type="number" class="added_balance border5" id="added_balance{{$index}}"
                                   onkeyup="updateBalance('{{$index}}')"
                                   onchange="updateBalance('{{$index}}')"
                                   name="items[{{$index}}][added_balance]"
                                   value="{{$item->added_balance}}"
                                   style="width: 150px">
                        </td>

                        <td>
                            <input readonly type="number"  class="total_balance border5" value="{{$item->total}}" id="total_item_balance{{$index}}" style="width: 150px">
                        </td>

                        <td>
                            <div class="input-group" id="stores">
                                <button type="button" class="btn btn-sm btn-danger fa fa-trash" onclick="removeItem('{{$index}}')"></button>
                            </div>
                        </td>
                    </tr>

                @endforeach
            @endif
            </tbody>
            <tfoot>
            <tr>
                <th width="2%"> # </th>
                <th width="10%"> {{ __('Locker name') }} </th>
                @if ($setting->active_multi_currency)
                    <th width="10%"> {{ __('Select Currency') }} </th>
                @endif
                <th width="10%"> {{ __('Current balance') }} </th>
                <th width="10%"> {{ __('Added balance') }} </th>
                <th width="10%"> {{ __('Total') }} </th>
                <th width="5%"> {{ __('Action') }} </th>
            </tr>
            </tfoot>
            <input type="hidden" name="index" id="items_count" value="{{isset($lockerOpeningBalance) ? $lockerOpeningBalance->items->count() : 0}}">

        </table>
    </div>
</div>
