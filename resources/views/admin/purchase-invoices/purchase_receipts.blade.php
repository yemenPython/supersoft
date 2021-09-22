@foreach($purchaseReceipts as $purchaseReceipt)

    <tr>
        <td>
            <input type="checkbox" name="purchase_receipts[]" value="{{$purchaseReceipt->id}}"
                   onclick="selectPurchaseQuotation('{{$purchaseReceipt->id}}')"
                   class="purchase_quotation_box_{{$purchaseReceipt->id}} quotations_boxes"
                {{isset($purchase_return_receipts) && in_array($purchaseReceipt->id, $purchase_return_receipts) ? 'checked':''}}
            >
        </td>
        <td>
            <span>{{$purchaseReceipt->number}}</span>
        </td>
        <td>
            <span>{{optional($purchaseReceipt->supplier)->name}}</span>
        </td>
    </tr>

@endforeach
