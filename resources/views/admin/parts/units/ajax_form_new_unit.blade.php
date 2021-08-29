

<div class="col-md-2">
    <div class="form-group">

        <label for="inputQuantity" class="control-label">{{__('Quantity')}}</label>

        <div class="input-group">

            <span class="input-group-addon">
                <li class="fa fa-cube"></li>
            </span>

            <input type="text" name="quantity" class="form-control" placeholder="{{__('Quantity')}}" id="unit_quantity"
                   value="{{isset($price) && $price ? $price->quantity : 1}}"
                   {{ $isFirstUnit ? 'readonly' : '' }}
                   onkeyup="calculatePrice()"
            >
        </div>
    </div>
</div>

<div class="col-md-1" style="padding-top: 33px;">
    <span style="color: white; margin-top:-5px; margin-right:-10px;" class="default_unit_title part-unit-span" id="default_Unit_title">
        {{isset($defaultUnit) ? $defaultUnit : ''}}
    </span>
</div>

<div class="col-md-3">
    <div class="form-group has-feedback">
        <label for="inputUnits" class="control-label">{{__('Unit')}}</label>
        <div class="input-group">
            <span class="input-group-addon fa fa-clone"></span>

            <select class="form-control js-example-basic-single"  name="unit_id" onchange="getUnitName()" id="select_unit_id">
                <option value="">{{__('Select unit')}}</option>
                @foreach($partUnits as $partsUnit)
                    <option
                        value="{{$partsUnit->id}}" {{isset($price) && !isset($formType) && $price->unit_id == $partsUnit->id ? 'selected':''}}>
                        {{$partsUnit->unit}}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">

        <label for="inputQuantity" class="control-label">{{__('Selling Price')}}</label>

        <div class="input-group">
            <span class="input-group-addon">
                <li class="fa fa-money"></li>
            </span>

            <input type="text" name="selling_price" id="selling_price" class="form-control"
                   placeholder="{{__('selling price')}}"
                   value="{{isset($price) ? $price->selling_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputPurchasePrice" class="control-label">{{__('purchase price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text" name="purchase_price" id="purchase_price"
                   class="form-control" placeholder="{{__('purchase price')}}"
                   value="{{isset($price) ? $price->purchase_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLess" class="control-label">{{__('Less Selling Price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text" name="less_selling_price"
                   class="form-control" id="less_selling_price"
                   placeholder="{{__('less selling price')}}"
                   value="{{isset($price) ? $price->less_selling_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputServices" class="control-label">{{__('Service selling price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   name="service_selling_price"
                   class="form-control" id="service_selling_price"
                   placeholder="{{__('Services selling price')}}"
                   value="{{isset($price) ? $price->service_selling_price : 0}}">
        </div>

    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputServices" class="control-label">{{__('Less Service selling price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text" name="less_service_selling_price" class="form-control"
                   id="less_service_selling_price"
                   placeholder="{{__('Less services selling price')}}"
                   value="{{isset($price) ? $price->less_service_selling_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputMaxQuantity" class="control-label">{{__('Maximum sale amount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="number" name="maximum_sale_amount" class="form-control"
                   placeholder="{{__('maximum sale amount of quantity')}}" id="maximum_sale_amount"
                   value="{{isset($price) ? $price->maximum_sale_amount : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputMinimumQuantity" class="control-label">{{__('Minimum for order')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-cube"></li></span>
            <input type="number" name="minimum_for_order"
                   class="form-control" id="minimum_for_order"
                   placeholder="{{__('minimum for order')}}"
                   value="{{isset($price) ? $price->minimum_for_order : 0}}">
        </div>
    </div>

</div>

<div class="col-md-3">

    <div class="form-group">
        <label for="inputBigDiscount" class="control-label">{{__('Biggest percent discount')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   name="biggest_percent_discount"
                   class="form-control"
                   placeholder="{{__('Biggest percent discount')}}" id="biggest_percent_discount"
                   value="{{isset($price) ? $price->biggest_percent_discount : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLastSelling" class="control-label">{{__('Last selling price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text"
                   name="last_selling_price"
                   class="form-control"
                   placeholder="{{__('Last selling price')}}" disabled="disabled" id="last_selling_price"
                   value="{{isset($price) ? $price->last_selling_price : 0}}">
        </div>
    </div>

</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputLastPurchase" class="control-label">{{__('Last purchase price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text" name="last_purchase_price"
                   class="form-control"
                   placeholder="{{__('Last purchase price')}}" disabled="disabled" id="last_purchase_price"
                   value="{{isset($price) ? $price->last_purchase_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputBarcodeSupplier" class="control-label">{{__('Damage Price')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-money"></li></span>
            <input type="text" class="form-control"
                   name="damage_price" id="damage_price"
                   value="{{isset($price) ? $price->damage_price : 0}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputBarcode" class="control-label">{{__('Barcode')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-barcode"></li></span>
            <input type="text" class="form-control"
                   name="barcode"
                   placeholder="{{__('Barcode')}}" id="barcode"
                   value="{{isset($price) && !isset($formType)  ? $price->barcode : ''}}">
        </div>
    </div>
</div>

<div class="col-md-3">
    <div class="form-group">
        <label for="inputBarcodeSupplier" class="control-label">{{__('Supplier Barcode')}}</label>
        <div class="input-group">
            <span class="input-group-addon"><li class="fa fa-barcode"></li></span>
            <input type="text" class="form-control" name="supplier_barcode"
                   placeholder="{{__('Supplier Barcode')}}" id="supplier_barcode"
                   value="{{isset($price) && !isset($formType)  ? $price->supplier_barcode : ''}}">
        </div>
    </div>
</div>

{{--<div class="col-md-9">--}}
{{--    <div class="form-group">--}}
{{--        <div class="radio primary col-md-3">--}}
{{--            <input type="radio" name="default_purchase" id="radio-purchase"--}}
{{--                {{isset($price) && $price->default_purchase ? 'checked':''}}>--}}
{{--            <label for="radio-purchase">{{__('Default Purchase')}}</label>--}}
{{--        </div>--}}

{{--        <div class="radio primary col-md-3" style="margin-top: 10px;">--}}
{{--            <input type="radio" name="default_sales" {{isset($price) && $price->default_sales ? 'checked':''}}--}}
{{--            id="radio-sales">--}}
{{--            <label for="radio-sales">{{__('Default Sales')}}</label>--}}
{{--        </div>--}}

{{--        <div class="radio primary col-md-3" style="margin-top: 10px;">--}}
{{--            <input type="radio"--}}
{{--                   name="default_maintenance"--}}
{{--                   {{isset($price) && $price->default_maintenance ? 'checked':''}}--}}
{{--                   id="radio-maintenance">--}}
{{--            <label for="radio-maintenance">{{__('Default Maintenance')}}</label>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="col-md-8">
    <div class="form-group">
    <!-- <label for="inputBarcode" class="control-label">{{__('Default')}}</label> -->
        <div class="input-group">

            <div class="row">


            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <hr>
</div>

{{-- PRICES BUTTONS--}}
<div class="col-md-12">
    <div class="form-group">
        <p>
            <button class="btn hvr-rectangle-in resetAdd-wg-btn maintenance_type_active_maintenance_form"
                    data-toggle="collapse"
                    href="#collapseExample"
                    role="button"
                    aria-expanded="false" aria-controls="collapseExample">
                <i class="ico ico-left fa fa-usd"></i>
                {{__('Prices')}}
            </button>
        </p>
    </div>

    {{-- PRICES BUTTONS--}}
    <div class=" collapse {{isset($price) && $price->partPriceSegments->count() ? 'show in':''}}" id="collapseExample">
        <div class="card card-body " style="padding: 10px;">

            <div id="ajax_service_box">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 products-details-wg">

                        @include('admin.parts.price_segments.index')

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
