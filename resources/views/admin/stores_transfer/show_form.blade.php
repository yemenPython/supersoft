<div class="row">
    <div class="col-xs-12">

        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            @if(authIsSuperAdmin())

                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tbody>
                        <th style="width:50%;background:#ddd !important;color:black !important">{{__('Branch')}}</th>
                        <td>{{optional($storeTransfer->branch)->name}}</td>
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Added By')}}</th>
                    <td>{{isset($storeTransfer) ? optional($storeTransfer->user)->name : auth()->user()->name}}</td>
                    </tbody>
                    {{input_error($errors,'user')}}
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#ddd !important;color:black !important">{{ __('opening-balance.serial-number') }}</th>
                    <td>{{old('number', isset($storeTransfer)? $storeTransfer->number :'')}}</td>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Date')}}</th>
                    <td>{{old('date', isset($storeTransfer)? $storeTransfer->transfer_date : \Carbon\Carbon::now()->format('Y-m-d'))}}</td>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Time')}}</th>
                    <td>{{old('time', isset($storeTransfer)? $storeTransfer->time : \Carbon\Carbon::now()->format('H:i:s'))}}</td>
                    </tbody>
                </table>
            </div>


            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#D2F4F6 !important;color:black !important">{{ __('words.store-from') }}</th>
                    <td>{{isset($storeTransfer) ? optional($storeTransfer->store_from)->name : '--'}}</td>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#D2F4F6 !important;color:black !important">{{ __('words.store-to') }}</th>
                    <td>{{isset($storeTransfer) ? optional($storeTransfer->store_to)->name : '--'}}</td>
                    </tbody>
                </table>
            </div>


        </div>
    </div>


</div>

<div class="table-responsive center-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:10px 5px;padding:15px 15px 0">

    <table class="table table-responsive table-hover table-bordered remove-disabled text-center-inputs">
        <thead>
        <tr>
            <th width="2%"> #</th>
            <th width="16%"> {{ __('Name') }} </th>
            <th width="12%"> {{ __('Part Type') }} </th>
            <th width="12%"> {{ __('Unit Quantity') }} </th>
            <th width="12%"> {{ __('Unit') }} </th>
            <th width="13%"> {{ __('Price Segments') }} </th>
            <th> {{ __('Quantity') }} </th>
            <th> {{ __('Price') }} </th>
            <th> {{ __('Total') }} </th>
            <th width="25%"> {{ __('Barcode') }} </th>
            <th width="25%"> {{ __('Supplier Barcode') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">
        @if(isset($storeTransfer))

            @foreach ($storeTransfer->items as $index => $item)
                @php
                    $index +=1;
                    $part = $item->part;
                @endphp
                <tr id="tr_part_{{$index}}">
                    <td>
                        <spam>{{$index}}</spam>
                    </td>

                    <td>
                         <span style="width:180px; cursor: pointer"
                               data-img="{{$part->image}}" data-toggle="modal"
                               data-target="#part_img" title="Part image"
                               onclick="getPartImage('{{$index}}')"
                               id="part_img_id_{{$index}}">
                             {{$part->name}}
                         </span>
                    </td>
                    <td>
                        {{$item->sparePart ? $item->sparePart->type : '---'}}
                    </td>

                    <td class="inline-flex-span">
                        <span> {{optional($item->partPrice)->quantity}}  </span>
                        <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }} </span>

                    </td>

                    <td>
                        <span>
                        {{optional($item->partPrice->unit)->unit}}
                        </span>
                    </td>

                    <td>
                        <span class="price-span" id="price_segments_part_{{$index}}">
                                    {{ $item->partPriceSegment ? $item->partPriceSegment->name : __('Not determined')}}
                        </span>
                    </td>

                    <td class="text-danger">
                        <span>
                          {{isset($item) ? $item->quantity : 0}}
                        </span>
                    </td>

                    <td>
                        <span style="background:#F7F8CC !important">
                            {{isset($item) ? $item->price : 0}}
                        </span>
                    </td>

                    <td>
                        <span style="background:rgb(253, 215, 215) !important">
                            {{isset($item) ? ($item->price * $item->quantity) : 0}}
                        </span>
                    </td>

                    <td>
                        <span id="barcode_{{$index}}">
                            {{$item->partPrice ? $item->partPrice->barcode : '---'}}
                        </span>
                    </td>

                    <td>
                        <span id="supplier_barcode_{{$index}}">
                             {{ $item->partPrice ? $item->partPrice->supplier_barcode : '---' }}
                        </span>
                    </td>

                </tr>
            @endforeach
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th width="2%"> #</th>
            <th width="16%"> {{ __('Name') }} </th>
            <th width="12%"> {{ __('Part Type') }} </th>
            <th width="12%"> {{ __('Unit Quantity') }} </th>
            <th width="12%"> {{ __('Unit') }} </th>
            <th width="13%"> {{ __('Price Segments') }} </th>
            <th> {{ __('Quantity') }} </th>
            <th> {{ __('Price') }} </th>
            <th> {{ __('Total') }} </th>
            <th width="25%"> {{ __('Barcode') }} </th>
            <th width="25%"> {{ __('Supplier Barcode') }} </th>
        </tr>
        </tfoot>
    </table>
</div>


<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#FFC5D7 !important;color:black !important">{{__('Total quantity')}}</th>
        <td style="background:#FFC5D7">{{isset($storeTransfer) ? $storeTransfer->items->sum('quantity') : 0}}</td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</th>
        <td style="background:#F9EFB7">{{isset($storeTransfer) ? $storeTransfer->total : 0}}</td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</th>
        <td style="background:#D2F4F6">{{old('description', isset($storeTransfer)? $storeTransfer->description :'')}}</td>
        </tbody>
    </table>

</div>
