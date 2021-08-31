<tr id="price_segment_{{$key}}">
    <td>
        <div class="form-group">
            <div class="input-group">
                <div class="checkbox">
                    @if(isset($price) && !isset($formType))
                        <input type="hidden"  name="prices[{{$key}}][id]" value="{{$priceSegment->id}}">
                    @endif
                    <input type="checkbox" checked
                           id="price_segment_checkbox_{{$key}}"
                           value="{{isset($priceSegment) ? $priceSegment->id : ''}}"
                           onclick="openPriceSegment('{{$key}}')">
                    <label for="price_segment_checkbox_{{$key}}"></label>
                </div>
            </div>
        </div>
    </td>

    <td style="color: #0c0c0c">
        <input type="text" class="form-control"
               value="{{isset($priceSegment) ? $priceSegment->name : ''}}"
               id="segment_{{$key}}"
               name="prices[{{$key}}][name]"
        >
    </td>

    <td>
        <div class="input-group">
            <input type="text" class="form-control"
                   value="{{isset($priceSegment) ? $priceSegment->purchase_price : 0}}"
                   id="purchase_price_segment_{{$key}}"
                   name="prices[{{$key}}][purchase_price]"
            >
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text" class="form-control"
                   value="{{isset($priceSegment) ? $priceSegment->sales_price : 0}}"
                   id="sales_price_segment_{{$key}}"
                   name="prices[{{$key}}][sales_price]"
            >
        </div>
    </td>

    <td>
        <div class="input-group">
            <input type="text" class="form-control"
                   value="{{isset($priceSegment) ? $priceSegment->maintenance_price : 0}}"
                   id="maintenance_price_segment_{{$key}}"
                   name="prices[{{$key}}][maintenance_price]"
            >
        </div>
    </td>

    <td>
        <div class="input-group">

            @if(isset($priceSegment))

                <button type="button" title="remove price segment"
                        onclick="deleteOldPartPriceSegment('{{$key}}', '{{$priceSegment->id}}')"
                        class="btn btn-sm btn-danger">
                    <li class="fa fa-trash"></li>
                </button>
            @else
                <button type="button" title="remove price segment"
                        onclick="removePartPriceSegment('{{$key}}')"
                        class="btn btn-sm btn-danger">
                    <li class="fa fa-trash"></li>
                </button>
            @endif
        </div>
    </td>

</tr>
