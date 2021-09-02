<div class="row">

    <div class="row top-data-wg"
         style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


        @if(authIsSuperAdmin())
            <div class="col-md-12">
                <div class="col-md-12">
                    <div class="form-group has-feedback">
                        <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                        <div class="input-group">

                            <span class="input-group-addon fa fa-file"></span>

                            <select class="form-control js-example-basic-single" name="branch_id" id="branch_id">
                                <option value="">{{__('Select Branch')}}</option>

                                @foreach($branches as $branch)
                                    <option
                                        value="{{$branch->id}}" {{isset($supplyTerm) && $supplyTerm->branch_id == $branch->id? 'selected':''}}>
                                        {{$branch->name}}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                        {{input_error($errors,'branch_id')}}

                    </div>

                </div>
            </div>
        @endif


        <div class="col-md-12">
            <div class="form-group">
                <label for="inputDescription" class="control-label">{{__('Term Ar')}}</label>
                <div class="input-group">
                <textarea name="term_ar" class="form-control" rows="4" cols="150"
                >{{old('term_ar', isset($supplyTerm)? $supplyTerm->term_ar :'')}}</textarea>
                </div>
                {{input_error($errors,'term_ar')}}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="inputDescription" class="control-label">{{__('Term En')}}</label>
                <div class="input-group">
                <textarea name="term_en" class="form-control" rows="4" cols="150"
                >{{old('term_en', isset($supplyTerm)? $supplyTerm->term_en :'')}}</textarea>
                </div>
                {{input_error($errors,'term_en')}}
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="row top-data-wg"
         style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Type')}}</label>
                <div class="input-group">

                    <span class="input-group-addon fa fa-file-text-o"></span>

                    <select class="form-control js-example-basic-single" name="type">
                        <option value="">{{__('Select')}}</option>
                        <option value="supply" {{isset($supplyTerm) && $supplyTerm->type == 'supply'? 'selected':''}}>
                            {{__('Supply')}}
                        </option>

                        <option value="payment" {{isset($supplyTerm) && $supplyTerm->type == 'payment'? 'selected':''}}>
                            {{__('Payment')}}
                        </option>

                    </select>
                </div>

                {{input_error($errors,'type')}}

            </div>
        </div>

        <div class="col-md-4">
        </div>

        <div class="col-md-4">
            <div class="form-group has-feedback">
                <label for="inputStore" class="control-label">{{__('Status')}}</label>

                <div class="switch primary" style="margin-top: 15px">
                    <input type="checkbox" id="switch-2" name="status" {{!isset($supplyTerm) ? 'checked':'' }}
                        {{isset($supplyTerm) && $supplyTerm->status ? 'checked':''}}>
                    <label for="switch-2">{{__('Active')}}</label>
                </div>

            </div>
        </div>

        <div class="col-md-12">


            <div class="table-responsive wg-inside-table">
                <table class="table">
                    <thead>

                    <tr>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Status')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>  {{__('Purchase Quotation')}}</td>
                        <td>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="checkbox" id="switch-3" name="for_purchase_quotation"
                                    {{isset($supplyTerm) && $supplyTerm->for_purchase_quotation ? 'checked':''}}>
                                <label for="switch-3">{{__('Active')}}</label>
                            </div>
                        </td>

                        <td>  {{__('Purchase Invoice')}} </td>
                        <td>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="checkbox" id="switch-purchase_invoice" name="purchase_invoice"
                                    {{isset($supplyTerm) && $supplyTerm->purchase_invoice ? 'checked':''}}>
                                <label for="switch-purchase_invoice">{{__('Active')}}</label>
                            </div>
                        </td>

                        <td>  {{__('Purchase Return')}} </td>
                        <td>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="checkbox" id="switch-purchase_return" name="purchase_return"
                                    {{isset($supplyTerm) && $supplyTerm->purchase_return ? 'checked':''}}>
                                <label for="switch-purchase_return">{{__('Active')}}</label>
                            </div>
                        </td>

                        <td>  {{__('Supply Order')}} </td>
                        <td>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="checkbox" id="switch-supply_order" name="supply_order"
                                    {{isset($supplyTerm) && $supplyTerm->supply_order ? 'checked':''}}>
                                <label for="switch-supply_order">{{__('Active')}}</label>
                            </div>
                        </td>

                        <td>  {{__('Sale Quotations')}} </td>
                        <td>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="checkbox" id="switch-sale_quotation" name="sale_quotation"
                                    {{isset($supplyTerm) && $supplyTerm->sale_quotation ? 'checked':''}}>
                                <label for="switch-sale_quotation">{{__('Active')}}</label>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>  {{__('Sales Invoice')}} </td>
                        <td>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="checkbox" id="switch-sales_invoice" name="sales_invoice"
                                    {{isset($supplyTerm) && $supplyTerm->sales_invoice ? 'checked':''}}>
                                <label for="switch-sales_invoice">{{__('Active')}}</label>
                            </div>
                        </td>

                        <td>  {{__('Sales Invoice Return')}} </td>
                        <td>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="checkbox" id="switch-sales_invoice_return" name="sales_invoice_return"
                                    {{isset($supplyTerm) && $supplyTerm->sales_invoice_return ? 'checked':''}}>
                                <label for="switch-sales_invoice_return">{{__('Active')}}</label>
                            </div>
                        </td>

                        <td>  {{__('Sale Supply Order')}} </td>
                        <td>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="checkbox" id="switch-sale_supply_order" name="sale_supply_order"
                                    {{isset($supplyTerm) && $supplyTerm->sale_supply_order ? 'checked':''}}>
                                <label for="switch-sale_supply_order">{{__('Active')}}</label>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

