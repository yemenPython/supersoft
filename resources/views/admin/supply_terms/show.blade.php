@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Show Supply Term') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">

        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item active"><a
                        href="{{route('admin:supply-terms.index')}}"> {{__('Supply & payments')}}</a></li>
                <li class="breadcrumb-item active"> {{__('Show Supply Term')}}</li>
            </ol>
        </nav>

        <div class="col-xs-12">

            <div class=" card box-content-wg-new bordered-all primary">

                <h4 class="box-title with-control" style="text-align: initial"><i class="fa fa-file-o"></i>
                    {{__('Show Supply Term')}}
                    <span class="controls hidden-sm hidden-xs pull-left">

							<button class="control text-white"
                                    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                </h4>

  
                <div class="box-content">

                    <div class="row">
                           
                    @if(authIsSuperAdmin())
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                                    <div class="input-group">

                                        <span class="input-group-addon fa fa-file"></span>

                                        <input type="text" class="form-control"
                                               value="{{optional($supplyTerm->branch)->name}}" disabled>

                                    </div>

                                </div>
                        </div>
                        @endif

                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label for="inputDescription" class="control-label">{{__('Term Ar')}}</label>
                                    <div class="input-group">
                                        <textarea name="term_ar" class="form-control" rows="4" cols="150" disabled
                                        >{{old('term_ar', isset($supplyTerm)? $supplyTerm->term_ar :'')}}</textarea>
                                    </div>
                                    {{input_error($errors,'term_ar')}}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="inputDescription" class="control-label">{{__('Term En')}}</label>
                                    <div class="input-group">
                                        <textarea name="term_en" class="form-control" rows="4" cols="150" disabled
                                        >{{old('term_en', isset($supplyTerm)? $supplyTerm->term_en :'')}}</textarea>
                                    </div>
                                    {{input_error($errors,'term_en')}}
                                </div>
                            </div>
                            </div>


                                                     
                        <div class="row top-data-wg"
                             style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">


                            <div class="col-md-6">
                                <table class="table table-bordered wg-inside-table">
                                    <tr>
                                        <th style="width:50%">
                                            {{__('Type')}}
                                        </th>
                                        <td>
                                            @if($supplyTerm->type == 'supply')
                                                <span class="label label-primary wg-label"> {{__('Supply')}} </span>
                                            @else
                                                <span class="label label-warning wg-label"> {{__('Payment')}} </span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>

                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered wg-inside-table">
                                    <tr>
                                        <th style="width:50%">
                                            {{__('Status')}}
                                        </th>
                                        <td>
                                            @if($supplyTerm->status)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>

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
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td>  {{__('Purchase Quotation')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                @if($supplyTerm->for_purchase_quotation)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                                </div>
                                            </td>
                                            <td>  {{__('Purchase Invoice')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                @if($supplyTerm->purchase_invoice)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                                </div>
                                            </td>
                                            <td>   {{__('Purchase Return')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                @if($supplyTerm->purchase_return)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                                </div>
                                            </td>

                                            <td>{{__('Supply Order')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                @if($supplyTerm->supply_order)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td> {{__('Sale Quotations')}}</td>
                                            <td>
                                                <div class="switch primary" style="margin:0">
                                                @if($supplyTerm->sale_quotation)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                                </div>
                                            </td>
                                           
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                </div>
                           

</div>


</div>
                                </div>
                                </div>
</div>
                                </div>
                         

                            <!-- <div class="col-md-4">
                                <table class="table table-bordered has-feedback">
                                    <tr>
                                        <th style="width:50%">
                                            {{__('Purchase Quotation')}}
                                        </th>
                                        <td>
                                            @if($supplyTerm->for_purchase_quotation)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div> -->

                            <!-- <div class="col-md-4">
                                <table class="table table-bordered has-feedback">
                                    <tr>
                                        <th style="width:50%">
                                            {{__('Purchase Invoice')}}
                                        </th>
                                        <td>
                                            @if($supplyTerm->purchase_invoice)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div> -->
<!-- 
                            <div class="col-md-4">
                                <table class="table table-bordered has-feedback">
                                    <tr>
                                        <th style="width:50%">
                                            {{__('Purchase Return')}}
                                        </th>
                                        <td>
                                            @if($supplyTerm->purchase_return)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div> -->
<!-- 
                            <div class="col-md-4">
                                <table class="table table-bordered has-feedback">
                                    <tr>
                                        <th style="width:50%">
                                            {{__('Supply Order')}}
                                        </th>
                                        <td>
                                            @if($supplyTerm->supply_order)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div> -->
<!-- 
                            <div class="col-md-4">
                                <table class="table table-bordered has-feedback">
                                    <tr>
                                        <th style="width:50%">
                                            {{__('Sale Quotations')}}
                                        </th>
                                        <td>
                                            @if($supplyTerm->sale_quotation)
                                                <span class="label label-success wg-label"> {{ __('Active') }} </span>
                                            @else
                                                <span class="label label-danger wg-label"> {{ __('inActive') }} </span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div> -->



@endsection

@section('js-validation')

    @include('admin.partial.sweet_alert_messages')

@endsection
