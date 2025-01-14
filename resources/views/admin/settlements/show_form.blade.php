<div class="row">
    <div class="col-xs-12">

        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">

            @if(authIsSuperAdmin())

                <div class="col-md-12">
                    <table class="table table-bordered">
                        <tbody>
                        <th style="width:50%;background:#ddd !important;color:black !important">{{__('Branches')}}</th>
                        <td>{{optional($settlement->branch)->name}}</td>
                        </tbody>
                    </table>
                </div>
            @endif

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Added By')}}</th>
                    <td>{{isset($settlement) ? optional($settlement->user)->name : auth()->user()->name}}</td>
                    </tbody>
                </table>
                {{input_error($errors,'user')}}

            </div>


            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Number')}}</th>
                    <td>{{old('number', isset($settlement)? $settlement->special_number :'')}}</td>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Date')}}</th>
                    <td>{{old('date', isset($settlement)? $settlement->date : \Carbon\Carbon::now()->format('Y-m-d'))}}</td>
                    </tbody>
                </table>
                {{input_error($errors,'date')}}
                </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#ddd !important;color:black !important">{{__('Time')}}</th>
                    <td>{{old('time', isset($settlement)? $settlement->time : \Carbon\Carbon::now()->format('H:i:s'))}}</td>
                    </tbody>
                </table>
                {{input_error($errors,'time')}}

                </tbody>
                </table>
            </div>


            <div class="col-md-6">
                <table class="table table-bordered">
                    <tbody>
                    <th style="width:50%;background:#ddd !important;color:black !important">{{__('settlement type')}}</th>
                    <td>

                        @if($settlement->type == 'positive' )
                            <span> {{__('Positive')}} </span>
                        @else
                            <span> {{__('Negative')}} </span>
                @endif

            </div>
            </td>
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
            <th width="2%">#</th>
            <th width="16%"> {{ __('Name') }} </th>
            <th width="16%"> {{ __('Part Type') }} </th>
            <th width="10%"> {{ __('Unit Quantity') }} </th>
            <th width="12%"> {{ __('Unit') }} </th>
            <th width="13%"> {{ __('Price Segments') }} </th>
            <th> {{ __('Quantity') }} </th>
            <th> {{ __('Price') }} </th>
            <th> {{ __('Total') }} </th>
            <th width="5%"> {{ __('Store') }} </th>
            <th width="25%"> {{ __('Barcode') }} </th>
            <th width="25%"> {{ __('Supplier Barcode') }} </th>
        </tr>
        </thead>
        <tbody id="parts_data">
        @if(isset($settlement))

            @foreach ($settlement->items as $index => $item)
                @php
                    $index +=1;
                    $part = $item->part;
                @endphp
                <tr id="tr_part_{{$index}}">

                    <td>
                        <span>{{$index}}</span>
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
                        <span id="unit_quantity_{{$index}}">
                        {{isset($item) && $item->partPrice ? $item->partPrice->quantity : $part->first_price_quantity}}
                        </span>
                        <span class="part-unit-span"> {{ $part->sparePartsUnit->unit }}  </span>
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

                        <span class="label wg-label" style="background:#60B28E !important">
                        {{optional($item->store)->name}}
                        </span>

                    </td>


                    <td>
                        <span id="barcode_{{$index}}">
                            {{$item->partPrice ? $item->partPrice->barcode : '---' }}
                        </span>
                    </td>

                    <td>
                        <span id="supplier_barcode_{{$index}}">
                             {{$item->partPrice ? $item->partPrice->supplier_barcode : '---' }}
                        </span>
                    </td>

                </tr>
            @endforeach
        @endif
        </tbody>
        <tfoot>
        <tr>
            <th width="2%">#</th>
            <th width="16%"> {{ __('Name') }} </th>
            <th width="16%"> {{ __('Part Type') }} </th>
            <th width="10%"> {{ __('Unit Quantity') }} </th>
            <th width="12%"> {{ __('Unit') }} </th>
            <th width="13%"> {{ __('Price Segments') }} </th>
            <th> {{ __('Quantity') }} </th>
            <th> {{ __('Price') }} </th>
            <th> {{ __('Total') }} </th>
            <th width="5%"> {{ __('Store') }} </th>
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
        <td style="background:#FFC5D7">{{isset($settlement) ? $totalQuantity : 0}}</td>
        </tbody>
    </table>


    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Price')}}</th>
        <td style="background:#F9EFB7">{{isset($settlement) ? $settlement->total : 0}}</td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#D2F4F6 !important;color:black !important">{{__('Description')}}</th>
        <td style="background:#D2F4F6">{{old('description', isset($settlement)? $settlement->description :'')}}</td>
        </tbody>
        {{input_error($errors,'description')}}

        </tbody>
    </table>

</div>


<div class="" id="employees_percent">

    <div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

        <div class="form-group">

            <div class="col-md-12" style="color: white; margin-bottom: 75px; margin-right: -8px;top:16px">
                <div class="ribbon ribbon-r bg-secondary show-ribbon" style="background: rgb(86, 133, 204) !important;">
                    <p class="mb-0">{{__('Settlement Employees')}}</p></div>
            </div>

            <div id="employees_data">


                @if(isset($settlement))

                    @foreach($settlement->employees as $employee)


                        <table class="table table-bordered wg-inside-table">
                            <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                            </tr>
                            </thead>
                            <ttbody>
                                <tr>
                                    <td>{{$employee->name}}</td>
                                </tr>
                            </ttbody>
                        </table>

                    @endforeach

                @endif
            </div>

        </div>
    </div>
</div>

