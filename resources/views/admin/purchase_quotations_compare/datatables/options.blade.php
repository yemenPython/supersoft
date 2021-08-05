

@if (isset($withPurchaseRequest))
    <span class="part-unit-span">
        {{$item->purchaseQuotation && $item->purchaseQuotation->purchaseRequest ? $item->purchaseQuotation->purchaseRequest->number : __('Not determined')}}
    </span>
@endif



@if (isset($withActions))
    <div class="btn-group margin-top-10">

        <button type="button" class="btn btn-options dropdown-toggle"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ico fa fa-bars"></i>
            {{__('Options')}} <span class="caret"></span>

        </button>

        <ul class="dropdown-menu dropdown-wg">

            <li>
                <a style="cursor:pointer" class="btn btn-print-wg text-white  "
                   data-toggle="modal"
                   onclick="getPrintData({{$item->purchaseQuotation->id}})"
                   data-target="#boostrapModal" title="{{__('print')}}">
                    <i class="fa fa-print"></i> {{__('Show quotation')}}
                </a>
            </li>
        </ul>
    </div>
@endif

@if (isset($withOptions))

<div class="col-md-2" style="margin-top: 10px;">
    <div class="form-group has-feedback">
        <input type="checkbox" class="item_of_all" name="purchase_quotations_items[]" value="{{$item->id}}">
    </div>
</div>

@endif
