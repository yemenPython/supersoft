<div class="row small-spacing" id="assetDatatoPrint">


    <div class="print-wg-fatora">
        <div class="row">
            <div class="col-xs-4">

                <div style="text-align: right ">
                    <h5><i class="fa fa-home"></i> {{optional($assetReplacement->branch)->name_ar}}</h5>
                    <h5><i class="fa fa-phone"></i> {{optional($assetReplacement->branch)->phone1}} </h5>
                    <h5><i class="fa fa-globe"></i> {{optional($assetReplacement->branch)->address}} </h5>
                    <h5><i class="fa fa-fax"></i> {{optional($assetReplacement->branch)->fax}}</h5>
                    <h5><i class="fa fa-adjust"></i> {{optional($assetReplacement->branch)->tax_card}}</h5>
                </div>
            </div>

            <div class="col-xs-4">

                <img class="text-center center-block" style="width: 100px; height: 100px;margin-top:20px"
                     src="{{optional($assetReplacement->branch)->logo_img}}">
            </div>
            <div class="col-xs-4">

                <div style="text-align: left" class="my-1">
                    <h5>{{optional($assetReplacement->branch)->name_en}} <i class="fa fa-home"></i></h5>
                    <h5>{{optional($assetReplacement->branch)->phone1}} <i class="fa fa-phone"></i></h5>
                    <h5>{{optional($assetReplacement->branch)->address}} <i class="fa fa-globe"></i></h5>
                    <h5>{{optional($assetReplacement->branch)->fax}} <i class="fa fa-fax"></i></h5>
                    <h5>{{optional($assetReplacement->branch)->tax_card}} <i class="fa fa-adjust"></i></h5>
                </div>
            </div>
        </div>
    </div>

    <h4 class="text-center">{{__('Assets Replacements')}}</h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Number')}}</th>
                        <td>{{$assetReplacement->number }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$assetReplacement->time}} - {{$assetReplacement->date}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{optional($assetReplacement->user)->name}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div class="col-xs-12 wg-tb-snd">
        <div style="margin:10px 15px">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th width="2%"> # </th>
                        <th width="10%"> {{ __('Assets Groups') }} </th>
                        <th width="10%"> {{ __('Assets') }} </th>
                        <th width="10%"> {{ __('Operation Date') }} </th>
                        <th width="10%"> {{ __('Purchase Cost') }} </th>
                        <th width="10%"> {{ __('Cost Before Replacement') }} </th>
                        <th width="10%"> {{ __('Cost Replacement') }} </th>
                        <th width="10%"> {{ __('Cost After Replacement') }} </th>
                        <th width="12%"> {{ __('Age') }} </th>
                    </tr>
                    </thead>
                    <tbody id="items_data">
                    @if(isset($assetReplacement))
                        @foreach ($assetReplacement->assetReplacementItems as $index => $item)
                            <tr class="text-center-inputs" id="item_{{$index}}">

                                <td>
                                    <span>{{$index}}</span>
                                </td>

                                <td>
                                    <span>{{optional($item->asset->group)->name}}</span>
                                </td>

                                <td class="inline-flex-span">
                                    <span>{{optional($item->asset)->name}}</span>
                                    <input type="hidden" class="assetExist" value="{{optional($item->asset)->id}}" name="items[{{$index}}][asset_id]">
                                </td>

                                <td class="inline-flex-span">
                                    <span>{{optional($item->asset)->date_of_work}}</span>
                                </td>

                                <td class="inline-flex-span">
                                    <span>{{optional($item->asset)->purchase_cost}}</span>
                                    <input type="hidden" id="purchase_cost{{$index}}" value="{{optional($item->asset)->purchase_cost}}">

                                </td>

                                <td class="inline-flex-span">
                                    <span>{{optional($item->asset)->annual_consumtion_rate}}</span>
                                    <input type="hidden" class="replacement_before" id="replacement_before{{$index}}"
                                           value="{{optional($item->asset)->annual_consumtion_rate}}">
                                </td>

                                <td class="inline-flex-span">
                                    <input type="number" class="value_replacement"
                                           id="value_replacement{{$index}}"
                                           name="items[{{$index}}][value_replacement]"
                                           onkeyup="addReplacementValue('{{$index}}')"
                                           value="{{$item->value_replacement}}" style="width: 100px">
                                </td>

                                <td class="inline-flex-span">
                                    <input type="number" class="replacement_after"
                                           id="replacement_after{{$index}}"
                                           name="items[{{$index}}][value_after_replacement]"
                                           value="{{$item->value_after_replacement}}" style="width: 100px" >
                                </td>

                                <td class="inline-flex-span">
                                    <input type="text" readonly style="width: 100px" id="age{{$index}}"
                                           name="items[{{$index}}][age]" value="{{$item->age}}">
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
        </div>
    </div>

</div>

<div class="bottom-data-wg" style="width:100%;box-shadow: 0 0 7px 1px #DDD;margin:5px auto 10px;padding:7px 7px 3px">

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total Before Replacement')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_before_replacement"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($assetReplacement) ? $assetReplacement->total_before_replacement : 0}}" class="form-control">
        </td>
        </tbody>
    </table>

    <table class="table table-bordered">
        <tbody>
        <th style="width:30%;background:#F9EFB7 !important;color:black !important">{{__('Total After Replacement')}}</th>
        <td style="background:#F9EFB7">
            <input type="text" readonly id="total_after_replacement"
                   style="background:#F9EFB7;border:none;text-align:center !important;"
                   value="{{isset($assetReplacement) ? $assetReplacement->total_after_replacement : 0}}" class="form-control">
        </td>
        </tbody>
    </table>



</div>
