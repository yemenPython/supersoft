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
            <form id="filtration-form">
                <input type="hidden" name="invoker"/>
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
                            <label> {{ __('Invoice Number') }} </label>
                                <select name="invoice_number" class="form-control js-example-basic-single" id="expenseTypes">
                                    <option value="">{{__('Select Invoice Number')}}</option>
                                    @foreach(\App\Models\PurchaseAsset::all() as $invoice)
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
                                <label for="dateFrom" class="control-label">{{__('Date From')}}</label>
                                <div class="input-group">
                                    <input type="date" name="dateFrom" class="form-control" value="" id="dateFrom" placeholder="{{__('Select Date')}}">
                                </div>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="dateTo" class="control-label">{{__('Date To')}}</label>
                                <div class="input-group">
                                    <input type="date" name="dateTo" class="form-control" value="" id="dateTo" placeholder="{{__('Select Date')}}">
                                </div>
                            </div>

                            </div>

                <button type="submit"
                                class="btn sr4-wg-btn   waves-effect waves-light hvr-rectangle-out"><i
                                    class=" fa fa-search "></i> {{__('Search')}} </button>
                        <a href="{{route('admin:purchase-assets.index')}}"
                           class="btn bc-wg-btn   waves-effect waves-light hvr-rectangle-out"><i class=" fa fa-reply"></i> {{__('Back')}}
                        </a>

            </form>
        </div>
        <!-- /.card-content -->
    </div>
    <!-- /.box-content -->
</div>
@endif
@section('js-ajax')
@endsection
