<label for="inputStore" class="control-label">{{__('Items')}}</label>
<div class="input-group">

    <span class="input-group-addon fa fa-file-text-o"></span>

    <select class="form-control js-example-basic-single" name="salesable_id" id="item_id" onchange="getTypeItems()">

        <option value="">{{__('Select')}}</option>

        @foreach($items as $item)
            <option value="{{$item->id}}"
                {{isset($purchaseReceipt) && $purchaseReceipt->supply_order_id == $supply_order->id? 'selected':''}}>
                {{$item->number}}
            </option>
        @endforeach

    </select>
</div>

{{input_error($errors,'salesable_id')}}
