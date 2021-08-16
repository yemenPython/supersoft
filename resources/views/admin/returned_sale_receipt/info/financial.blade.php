<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{__('Total receipts')}}</label>
        <td style="background:#FFC5D7">
            <input type="text" disabled id="total" style="background:#FFC5D7; border:none;text-align:center !important;" class="form-control"
                   value="{{isset($purchaseReceipt) ? $purchaseReceipt->total : 0}}">
        </td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Accepted')}}</label>
        <td style="background:#F9EFB7">
            <input type="text" disabled id="total_accepted" style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($purchaseReceipt) ? $purchaseReceipt->total_accepted : 0}}" class="form-control">
        </td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Rejected')}}</label>
        <td style="background:#F9EFB7">
            <input type="text" disabled id="total_rejected" style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($purchaseReceipt) ? $purchaseReceipt->total_rejected : 0}}" class="form-control">
        </td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</label>
        <td style="background:#D2F4F6">
                <textarea name="notes" style="background:#D2F4F6;border:none;" class="form-control" rows="4"
                          cols="150"
                >{{old('description', isset($purchaseReceipt) ? $purchaseReceipt->notes : '')}}</textarea>

            {{input_error($errors,'description')}}
        </td>
        </tbody>
    </table>

</div>
