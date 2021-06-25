@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Super Car') }} - {{ __('Edit Expenses Items') }} </title>
@endsection

@section('content')
    <div class="row small-spacing">
    <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:expenses_items.index')}}"> {{__('Expenses Types')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Edit Expenses Items')}}</li>
            </ol>
        </nav>
        <div class="col-xs-12">
        <div class=" card box-content-wg-new bordered-all primary">
                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-money"></i>  {{__('Edit Expenses Items')}}
                <span class="controls hidden-sm hidden-xs pull-left">
                <button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Save')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f1.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px">{{__('Reset')}}<img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f2.png')}}"></button>
							<button class="control text-white" style="background:none;border:none;font-size:12px"> {{__('Back')}} <img class="img-fluid" style="width:50px;height:50px;margin-top:-20px;margin-bottom:-13px" src="{{asset('assets/images/f3.png')}}"></button>
						</span>
            </h4>
                    <div class="box-content">

                    <form  method="post" action="{{route('admin:expenses_items.update', ['id' => $expensesItem->id])}}" class="form">
                        @csrf
                        @method('put')


                        <div class="row">

                        <div class="col-md-12">
                            @if(authIsSuperAdmin())
                                <div class="col-md-12">

                                    <div class="form-group has-feedback">
                                        <label for="inputSymbolAR" class="control-label">{{__('Branch')}}</label>
                                        <select name="branch_id" class="form-control  js-example-basic-single"
                                                id="branch_id">
                                            <option value="">{{__('Select Branch')}}</option>
                                            @foreach(\App\Models\Branch::all() as $branch)
                                                <option
                                                    value="{{$branch->id}}" {{$branch->id == $expensesItem->branch_id ? 'selected' : ''}}>{{$branch->name}}</option>
                                            @endforeach
                                        </select>
                                        {{input_error($errors,'branch_id')}}
                                    </div>
                                </div>
                            @endif


                            <div class="col-md-4">


<div class="form-group has-feedback">
<label for="inputSymbolAR" class="control-label">{{__('Select Expense Type')}}</label>
<select name="expense_id" class="form-control  js-example-basic-single" id="expenseTypes">
<option value="">{{__('Select Expense Type')}}</option>
@foreach(\App\Models\ExpensesType::all() as $type)
    <option value="{{$type->id}}" {{ $type->id == $expensesItem->expense_id ? 'selected' : '' }}>{{$type->type}}</option>
@endforeach
</select>
{{input_error($errors,'expense_id')}}
</div>
</div>

                                <div class="col-md-4">
                                <div class="form-group">
                            <label for="inputNameAR" class="control-label">{{__('Item in Arabic')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                <input type="text" name="item_ar" class="form-control" id="inputNameAR"
                                       value="{{$expensesItem->item_ar}}" placeholder="{{__('Item in Arabic')}}">
                            </div>
                            {{input_error($errors,'item_ar')}}
                        </div>
                                </div>

                                <div class="col-md-4">


                                <div class="form-group">
                            <label for="type_en" class="control-label">{{__('Item in English')}}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-file-text-o"></i></span>
                                <input type="text" name="item_en" class="form-control" id="item_en"
                                       value="{{$expensesItem->item_en}}" placeholder="{{__('Item in English')}}">
                            </div>
                            {{input_error($errors,'item_en')}}
                        </div>
                                </div>

                            </div>


                  </div>




                  <div class="col-md-12">

                  <div class="form-group">
                            <label for="type_en" class="control-label">{{__('Description')}}</label>
                            <div class="input-group">
                                <textarea name="notes" class="form-control"  rows="4" cols="150">
                                    {{$expensesItem->notes}}
                                </textarea>
                            </div>
                        </div>
                  </div>


                        <div class="form-group col-sm-12">
                            @include('admin.buttons._save_buttons')
                        </div>
                    </form>
                </div>
                <!-- /.box-content -->
            </div>
        </div>
        <!-- /.col-xs-12 -->
    </div>
    <!-- /.row small-spacing -->
@endsection
@section('js-validation')
    {!! JsValidator::formRequest('App\Http\Requests\Admin\ExpenseItem\UpdateExpenseItemRequest', '.form'); !!}
    @include('admin.partial.sweet_alert_messages')
    <script type="application/javascript">
        $('#branch_id').on('change', function () {
            $.ajax({
                url: "{{ route('admin:getExpensesTypesByBranchID') }}?branch_id=" + $(this).val(),
                method: 'GET',
                success: function (data) {
                    $('#expenseTypes').html(data.types);
                }
            });
        });
    </script>
@endsection
