
<div id="concession_to_print">
<div class="top-logo-print">
    <div class="logo-print text-center">
        <ul class="list-inline" style="margin:0">
            <li>
            <h5>{{optional($branchToPrint)->name_ar}}</h5>
            </li>
            <li>
            <img
            src="{{isset($branchToPrint->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}"
            style="width: 50px;
    height: 50px;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 21px;">
            </li>
        </ul>
    </div>
</div>


<div class="row row-right-data">
    <div class="col-xs-6"></div>
    <div class="col-xs-6 right-top-detail">
        <h3>
        {{__($purchaseRequest->type . 'Purchase Request')}}
</h3>
        
    </div>
</div>

<div class="invoice-to">
    <div clas="row">
        <div class="col-xs-6">
        <h5>{{__('Purchase Request data')}}</h5>
        </div>
        <div class="col-xs-6" style="padding-right: 50px;">
            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-time-user">
                        <tr>
                        <th style="font-weight: normal !important;">{{__('Time & Date')}}</th>
                        <td style="font-weight: normal !important;">{{$purchaseRequest->time}} - {{$purchaseRequest->date}}</td>
                        </tr>
                        <tr>
                        <th style="font-weight: normal !important;">{{__('User Name')}}</th>
                        <td style="font-weight: normal !important;">{{optional($purchaseRequest->user)->name}}</td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="col-xs-12">

<table class="table static-table-wg">
                           <tbody>
                               <tr>
                               <th>{{__('Purchase request Number')}}</th>
                               <td>{{$purchaseRequest->special_number }}</td>
                               <th>{{__('Type')}}</th>
                               <td>{{__($purchaseRequest->type)}}</td>
                               <th>{{__('Status')}}</th>
                               <td>{{__($purchaseRequest->status)}}</td>
                             </tr>
                             <tr>
                             <th>{{__('Period of request from')}}</th>
                               <td>{{__($purchaseRequest->date_from)}}</td>
                               <th>{{__('Period of request to')}}</th>
                               <td>{{__($purchaseRequest->date_to )}}</td>
                               <th>{{__('request days')}}</th>
                               <td>{{__($purchaseRequest->different_days)}} </td>
                             </tr>
                             <!-- <tr>
                               <th>{{__('Requesting Party')}}</th>
                               <td colspan="6">{{__($purchaseRequest->requesting_party)}}</td>
                             </tr>
                             <tr>
<th>{{__('Requesting For')}}</th>
<td colspan="6">{{__($purchaseRequest->request_for)}}</td>
</tr> -->

                        </tbody></table>

</div>


<div class="invoice-to">
<h5>{{__('Purchase Request items')}}</h5>
</div>


<div  style="padding:0 20px;">
<table class="table print-table-wg table-borderless">
  <thead>

    <tr class="spacer" style="border-radius: 30px;">
      <th scope="col">#</th>
      <th scope="col">{{__('Name')}}</th>
      <th scope="col">{{__('Unit')}}</th>
      <!-- <th scope="col">{{__('Requested Qty')}}</th> -->
      <th scope="col">{{__('Requested Qty')}}</th>
    </tr>

  </thead>
  <tbody>
  @foreach($purchaseRequest->items as $index=>$item)

    <tr class="spacer">
    <td>{{$index + 1}}</td>
    <td>{{optional($item->part)->name}}</td>
    <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : __('Not determined')}}</td>
    <!-- <td>{{$item->quantity}}</td> -->
    <td>{{$item->approval_quantity}}</td>
    </tr>
    @if($item->spareParts->count())
                    <tr class="item">
                        <td>{{__('Additional types')}}</td>
                        
                        <td colspan="5">
                            @foreach($item->spareParts as $sparePart)
                               <span> {{ $sparePart->type }} </span>
                            @endforeach
                        </td>

                    </tr>
                @endif
    @endforeach
  </tbody>
</table>
</div>


<div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">
    <div class="col-xs-12">
         <h5 class="title">{{__('Notes')}}</h5>
         <p style="width: 80%;font-size:12px">
         {!! $purchaseRequest->description !!}
        
    </p>

</div>
</div>


<div class="print-foot-wg position-relative ml-0" >
        <div class="row" style="display: flex;
    align-items: flex-end;">
            <div class="col-xs-7">
                <div class="row">
                    <div class="col-xs-12">

                        <div class="media">
                            <div class="media-left">
                                <h6 class="media-heading" style="line-height:30px;">{{__('address')}} </h6>
                            </div>

                            <div class="media-body">
                                <h6 style="padding:0 15px">{{optional($branchToPrint)->address_ar}} </h6>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-6">

                    </div>
                    <div class="col-xs-6">

                    </div>
                </div>

            </div>
            <div class="col-xs-5 small-data-wg">
                <div class="row">
                    <div class="col-xs-4">
                        <h6>{{__('contact numbers')}} : </h6>
                    </div>
                    <div class="col-xs-4">
                        <h6>{{optional($branchToPrint)->phone1}}</h6>
                    </div>

                    <div class="col-xs-4">
                        <h6>{{optional($branchToPrint)->phone2}}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</div>


</section>


<!-- 
<div class="row small-spacing" id="concession_to_print">

    <div class="row" style="padding:0px 10px !important;">

        <div class="col-xs-4" style="{{ $lang == 'ar' ? 'float: left !important' : '' }}">
            <div style="text-align: left" class="my-1">
                <h5><b>{{optional($branchToPrint)->name_en}} </b></h5>
                <h5><b>Phone 1 : </b> {{optional($branchToPrint)->phone1}} </h5>
                <h5><b>Phone 2 : </b> {{optional($branchToPrint)->phone2}} </h5>
            <h5><b>Address : </b> {{optional($branchToPrint)->address_en}} </h5>
                <h5><b>Fax : </b> {{optional($branchToPrint)->fax}} </h5>
            <h5><b>Tax Number : </b> {{optional($branchToPrint)->tax_card}} </h5>
            </div>
        </div>
        <div class="col-xs-4 text-center" style="{{ $lang == 'ar' ? 'float: left !important' : '' }}">
            <img style="width: 200px; height: 100px"
                 src="{{isset($branchToPrint->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}">

        </div>
        <div class="col-xs-4">
            <div style="text-align: right">
                <h5><b> {{optional($branchToPrint)->name_ar}}</b></h5>
                <h5><b>{{__('phone1')}} : </b> {{optional($branchToPrint)->phone1}} </h5>
                <h5><b>{{__('phone2')}} : </b> {{optional($branchToPrint)->phone2}} </h5>
            <h5><b>{{__('address')}} : </b> {{optional($branchToPrint)->address_ar}} </h5>
                <h5><b>{{__('fax' )}} : </b> {{optional($branchToPrint)->fax}}</h5>
            <h5><b>{{__('Tax Card')}} : </b> {{optional($branchToPrint)->tax_card}}</h5>
            </div>
        </div>
    </div>

    <hr>

    <h4 class="text-center">{{__($purchaseRequest->type . ' Purchase Request')}}</h4>

    <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Purchase request Number')}}</th>
                        <td>{{$purchaseRequest->special_number }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Date')}}</th>
                        <td>{{$purchaseRequest->time}} - {{$purchaseRequest->date}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Username')}}</th>
                        <td>{{optional($purchaseRequest->user)->name}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Status')}}</th>
                        <td>{{__($purchaseRequest->status)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Type')}}</th>
                        <td>{{__($purchaseRequest->type)}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Requesting Party')}}</th>
                        <td>{{__($purchaseRequest->requesting_party)}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Period of request from')}}</th>
                        <td>{{__($purchaseRequest->date_from)}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Different Days')}}</th>
                        <td>{{__($purchaseRequest->different_days)}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Period of request to')}}</th>
                        <td>{{__($purchaseRequest->date_to )}}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black"
                            scope="row">{{__('Remaining Days')}}</th>
                        <td>{{__($purchaseRequest->remaining_days)}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Requesting For')}}</th>
                        <td>{{__($purchaseRequest->request_for)}}</td>
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
                <tr class="heading">
                    <th style="background:#CCC !important;color:black">{{__('#')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Name')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Unit')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Requested Qty')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Approval Quantity')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($purchaseRequest->items as $index=>$item)

                    <tr class="item">
                        <td>{{$index + 1}}</td>
                        <td>{{optional($item->part)->name}}</td>
                        <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : '---'}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->approval_quantity}}</td>
                    </tr>

                    @if($item->spareParts->count())
                        <tr class="item">
                            <td>{{__('types')}}</td>
                            <td colspan="5">

                                @foreach($item->spareParts as $sparePart)
                                    <button class="btn btn-primary btn-xs">{{ $sparePart->type }}</button>
                                @endforeach
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

    @if($purchaseRequest->description)
        <div class="col-xs-12 wg-tb-snd">
            <div class="wg-tb-snd" style="border:1px solid #AAA;margin:5px 20px 20px;padding:10px;border-radius:5px">
                {!! $purchaseRequest->description !!}
            </div>
        </div>
    @endif
</div> -->
