<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{__('Total quantity')}}</th>
        <td style="background:#FFC5D7">
            <input type="text" disabled id="item_quantity"
                   style="background:#FFC5D7; border:none;text-align:center !important;" class="form-control"
                   value="{{isset($concession->total_quantity) ? $concession->total_quantity : 0}}">
        </td>
        </tbody>
    </table>


    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</label>
        <td style="background:#F9EFB7">
            <input type="text" disabled id="total_price"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($concession) ? $totalPrice : 0}}" class="form-control">
        </td>
        </tbody>
    </table>


    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</label>
        <td style="background:#D2F4F6">
                <textarea name="description" style="background:#D2F4F6;border:none;" class="form-control" rows="4" disabled
                          cols="150"
                >{{old('description', isset($concession)? $concession->description :'')}}</textarea>
</div>
{{input_error($errors,'description')}}
</td>
</tbody>
</table>

</div>
