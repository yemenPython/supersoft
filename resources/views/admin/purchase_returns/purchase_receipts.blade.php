@foreach($purchaseReceipts as $purchaseReceipt)

    <tr>
        <td>
            <input type="checkbox" name="purchase_receipts[]" value="{{$purchaseReceipt->id}}"
                   onclick="selectPurchaseReceipt('{{$purchaseReceipt->id}}')"
                   class="purchase_quotation_box_{{$purchaseReceipt->id}}"
                {{isset($purchase_invoice_receipts) && in_array($purchaseReceipt->id, $purchase_invoice_receipts) ? 'checked':''}}
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

<tr style="display: none">
    <td>
        <input type="hidden" id="p_r_supplier_discount" value="{{$supplier->group_discount}}">
        <input type="hidden" id="p_r_supplier_discount_type" value="{{$supplier->group_discount_type}}">
    </td>
    <td style="display: none"></td>
    <td style="display: none"></td>
</tr>
