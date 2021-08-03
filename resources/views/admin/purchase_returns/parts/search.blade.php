@if(filterSetting())
<div class="col-xs-12">
    <div class="box-content card bordered-all js__card top-search">
        <h4 class="box-title with-control">
            <i class="fa fa-search"></i>{{__('Search filters')}}
            <span class="controls">
							<button type="button" class="control fa fa-minus js__card_minus"></button>
							<button type="button" class="control fa fa-times js__card_remove"></button>
						</span>
            <!-- /.controls -->
        </h4>
        <!-- /.box-title -->
        <div class="card-content js__card_content">
            <form  onsubmit="filterFunction($(this));return false;">
                <div class="list-inline margin-bottom-0 row">
                    @if(authIsSuperAdmin())
                            <div class="form-group col-md-12">
                            <label> {{ __('Branch') }} </label>
                                <select name="branch_id" class="form-control js-example-basic-single" id="branch_id">
                                <option value="">{{__('Select Branch')}}</option>
                                    @foreach(\App\Models\Branch::all() as $branch)
                                        <option value="{{$branch->id}}">{{$branch->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="form-group col-md-4">
                            <label> {{ __('Invoice Purchase Number') }} </label>
                                <select name="purchase_invoice_id" class="form-control js-example-basic-single">
                                    <option value="">{{__('Select Invoice Purchase Number')}}</option>
                                    @foreach(\App\Models\PurchaseInvoice::all() as $invoice)
                                        <option value="{{$invoice->id}}">{{$invoice->invoice_number}}</option>
                                    @endforeach
                                </select>
                            </div>

                        <div class="form-group col-md-4">
                        <label> {{ __('Invoice Return Number') }} </label>
                            <select name="invoice_number" class="form-control js-example-basic-single">
                                <option value="">{{__('Select Invoice Return Number')}}</option>
                                @foreach(\App\Model\PurchaseReturn::all() as $invoice)
                                    <option value="{{$invoice->invoice_number}}">{{$invoice->invoice_number}}</option>
                                @endforeach
                            </select>
                        </div>

                            <div class="form-group col-md-4">
                            <label> {{ __('Supplier Name') }} </label>
                                <select name="supplier_id" class="form-control js-example-basic-single" id="expenseItems">
                                    <option value="">{{__('Select Supplier')}}</option>
                                    @foreach(\App\Models\Supplier::all() as $supplier)
                                        <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="form-group col-md-4">
                            <label> {{ __('Invoice Type') }} </label>
                                <select name="type" class="form-control  js-example-basic-single">
                                    <option value="">{{__('Select Invoice Type')}}</option>
                                    <option value="cash">{{__('Cash')}}</option>
                                    <option value="credit">{{__('Credit')}}</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="dateFrom" class="control-label">{{__('Date From')}}</label>
                                <div class="input-group">

<span class="input-group-addon fa fa-calendar"></span>
                                    <input type="text" name="dateFrom" class="form-control datepicker" value="" id="dateFrom" placeholder="{{__('Select Date')}}">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="dateTo" class="control-label">{{__('Date To')}}</label>
                                <div class="input-group">

<span class="input-group-addon fa fa-calendar"></span>
                                    <input type="text" name="dateTo" class="form-control datepicker" value="" id="dateTo" placeholder="{{__('Select Date')}}">
                                </div>
                            </div>

                            </div>

                @include('admin.btns.btn_search')
            </form>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
@endif
@section('js-ajax')
@endsection
