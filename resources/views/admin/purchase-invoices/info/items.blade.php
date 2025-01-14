<tr id="tr_part_{{$index}}" class="remove_on_change_branch text-center-inputs">

    <td>
        <span id="item_number_{{$index}}">{{$index}}</span>
    </td>

    <td>

        <span style="width: 150px !important;display:block; cursor: pointer" data-img="{{$part->image}}"
              data-toggle="modal" data-target="#part_img" title="Part image" onclick="getPartImage('{{$index}}')"
              id="part_img_id_{{$index}}">
            {{$part->name}}
        </span>
    </td>

    <td>
        <span>{{$update_item->sparePart ? $update_item->sparePart->name : '---' }}</span>
    </td>

    <td class="inline-flex-span">

        <span id="unit_quantity_{{$index}}">

            @if (isset($update_item))
                {{ $update_item->partPrice ? $update_item->partPrice->quantity : $part->first_price_quantity}}
            @endif
        </span>

        <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span>
    </td>

    <td>
        <div class="input-group" style="width: 90px !important;">
            <span>{{$update_item->partPrice && $update_item->partPrice->unit ? $update_item->partPrice->unit->unit : '---'}}</span>
        </div>
    </td>


    <td>
        <div class="input-group" id="price_segments_part_{{$index}}" style="width: 150px !important;">
            <span>{{$update_item->partPriceSegment ? $update_item->partPriceSegment->name : '---'}}</span>
        </div>
    </td>


    <td>
        <span>{{$update_item->quantity }}</span>
    </td>

    <td>
        <span>{{$update_item->price }}</span>
    </td>

    <td>
        <span>{{__($update_item->discount_type) }}</span>
    </td>

    <td>
        <span>{{$update_item->discount}}</span>
    </td>

    <td>
        <span>{{$update_item->subtotal }}</span>
    </td>

    <td>
        <span>{{$update_item->total_after_discount - $update_item->tax }}</span>
    </td>


    <td>
        <div class="btn-group" style="display:flex !important;align-items:center">
    <span type="button" class="fa fa-eye dropdown-toggle eye-table-wg" data-toggle="dropdown"
          style="
    color: #a776e7;
    padding: 6px 10px;
    border-radius: 0;
    border: 1px solid #3f3f3f;
    cursor: pointer;
    font-size: 20px;
}"
          aria-haspopup="true" aria-expanded="false">
            </span>

            <ul class="dropdown-menu" style="margin-top: 19px;">
                @if($part->taxes->count())
                    @foreach($part->taxes as $tax_index => $tax)

                        @php
                            $tax_index +=1;

                        @endphp

                        <li>
                            <a>
                                <input type="checkbox" id="checkbox_tax_{{$tax_index}}_{{$index}}"
                                       name="items[{{$index}}][taxes][]" value="{{$tax->id}}" disabled
                                       data-tax-value="{{$tax->value}}"
                                       data-tax-type="{{$tax->tax_type}}"
                                       data-tax-execution-time="{{$tax->execution_time}}"
                                       onclick="calculateItem('{{$index}}')"
                                    {{!isset($update_item) ? 'checked':''}}
                                    {{isset($update_item) && in_array($tax->id, $update_item->taxes->pluck('id')->toArray()) ? 'checked':''}}
                                >
                                <span>
                                    {{$tax->name}} ( {{ $tax->value }} {{$tax->tax_type == 'amount' ? '$':'%'}} ) =
                                    <span id="calculated_tax_value_{{$tax_index}}_{{$index}}">
                                         {{isset($update_item) ? taxValueCalculated($update_item->total_after_discount - $update_item->tax, $update_item->subtotal, $tax ) : 0}}
                                    </span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                @else
                    <li>
                        <a>
                            <span>{{ __('No Taxes Founded') }}</span>
                        </a>
                    </li>

                @endif
            </ul>

            <input type="hidden" id="tax_count_{{$index}}" value="{{$part->taxes->count()}}">

            <input style="width: 120px !important;  margin: 0 5px;" type="number" class="form-control border5"
                   id="tax_{{$index}}"
                   value="{{isset($update_item) ? $update_item->tax : 0 }}"
                   min="0" name="items[{{$index}}][tax]" disabled>
        </div>
    </td>

    <td>
        <input style="width: 150px !important;" type="number" class="form-control border3" id="total_{{$index}}"
               value="{{isset($update_item) ? $update_item->total_after_discount : 0}}" min="0"
               name="items[{{$index}}][total]" disabled>
    </td>

    <td>
        <div class="input-group">
           {{$update_item->store ?  $update_item->store->name : '---'}}
        </div>
    </td>

    <td>
        <span id="barcode_{{$index}}">
            {{ $update_item->partPrice ? $update_item->partPrice->barcode : '---' }}
        </span>
    </td>

    <td>
        <span id="supplier_barcode_{{$index}}">
             {{ $update_item->partPrice ? $update_item->partPrice->supplier_barcode : '---' }}
        </span>
    </td>


</tr>




