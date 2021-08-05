@if(filterSetting())
    <div class="col-xs-12">
        <div class="box-content card bordered-all js__card top-search">
            <h4 class="box-title with-control">
                <i class="fa fa-search"></i>{{__('Search filters')}}
                <span class="controls">
                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                    <button type="button" class="control fa fa-times js__card_remove"></button>
                </span>
            </h4>

            <!-- /.box-title -->
            <div class="card-content js__card_content">
{{--                action="{{route('admin:purchase.quotations.compare.index')}}" method="get"--}}

                <form  onsubmit="filterFunction($(this));return false;">
                    <input type="hidden" name="filter" value="1">

                    <div class="list-inline margin-bottom-0 row">

                        <div class="form-group col-md-4">
                            <label> {{ __('Parts Types') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <select name="part_type_id" onchange="getParts()" id="spare_part_id">
                                    <option value="">{{__('Select')}}</option>
                                    @foreach ($partsTypes as $key=>$value)
                                        <option value="{{$key}}">
                                            {{$value}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Parts') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <select name="part_id" id="parts_options">
                                    <option value="">{{__('Select')}}</option>
                                    @foreach ($parts as $part)
                                        <option value="{{$part->id}}">
                                            {{$part->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <div class="form-group col-md-4">
                            <label> {{ __('Suppliers') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <select name="supplier_id" onchange="getPurchaseQuotations('suppliers')" id="supplier">
                                    <option value="">{{__('Select')}}</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">
                                            {{$supplier->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label> {{ __('Purchase Request Number') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <select name="purchase_request_id" onchange="getPurchaseQuotations('purchase_request')" id="purchase_request">
                                    <option value="">{{__('Select')}}</option>
                                    @foreach ($purchase_requests as $purchase_request)
                                        <option value="{{$purchase_request->id}}">
                                            {{$purchase_request->number}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-2">
                            <label> {{ __('Quotation Number') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-file"></span>
                                <select name="quotation_number" id="purchase_quotations">
                                    <option value="">{{__('Select')}}</option>
                                    @foreach ($purchase_quotations as $purchase_quotation)
                                        <option value="{{$purchase_quotation->id}}">
                                            {{$purchase_quotation->number}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Date From') }} </label>
                            <input type="date" name="date_from" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Date To') }} </label>
                            <input type="date" name="date_to" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Part Barcode') }} </label>
                            <input type="text" name="part_barcode" class="form-control">
                        </div>

                        <div class="form-group col-md-4">
                            <label> {{ __('Supplier Barcode') }} </label>
                            <input type="text" name="supplier_barcode" class="form-control">
                        </div>

                    </div>

                    @include('admin.btns.btn_search')

{{--                    <button type="submit" class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out">--}}
{{--                        <i class=" fa fa-search "></i>--}}
{{--                        {{__('Search')}}--}}
{{--                    </button>--}}

{{--                    <a href="{{route('admin:purchase.quotations.compare.index')}}"--}}
{{--                       class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out">--}}
{{--                        <i class=" fa fa-reply"></i> {{__('Back')}}--}}
{{--                    </a>--}}

                </form>
            </div>
            <!-- /.card-content -->
        </div>
        <!-- /.box-content -->
    </div>
@endif
