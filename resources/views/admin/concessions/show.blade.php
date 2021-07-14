<section id="concession_to_print">

<div class="top-logo-print">
    <div class="logo-print text-center">
        <h5>{{optional($branchToPrint)->name_ar}}</h5>
    </div>
</div>

<div class="row row-right-data">
    <div class="col-xs-8"></div>
    <div class="col-xs-4 right-top-detail">
        <h3>
            @if($concession->type == 'add' )
  {{ __('Add Concession') }}
@else
{{ __('Withdrawal Concession') }} </span>
@endif
</h3>
        <div class="row">
        <div class="col-xs-6"><h6>Lorem Ipsum..:</h6></div>
            <div class="col-xs-6"><h6>Lorem Ipsum..</h6></div>
        </div>
        <div class="row">
        <div class="col-xs-6"><h6>Lorem Ipsum..:</h6></div>
        <div class="col-xs-6"><h6>Lorem Ipsum..</h6></div>
        </div>
        <div class="row">
            <div class="col-xs-6"><h6>Lorem Ipsum..:</h6></div>
            <div class="col-xs-6"><h6>Lorem Ipsum..</h6></div>
        </div>
    </div>
</div>

<div class="invoice-to">
    <div class="row">
        <div class="col-xs-6">
           <h4>INVOICE TO</h4>
           <h2>JHONE DOE.</h2>
           <h6>Managing Director xyz.</h6>
           <br>
           <ul class="list-inline">
               <li><h6>Phone:</h6></li>
               <li><h6>Lorem Ipsum..</h6></li>
           </ul>
           <ul class="list-inline">
               <li><h6>Phone:</h6></li>
               <li><h6>Lorem Ipsum..</h6></li>
           </ul>
           <ul class="list-inline">
               <li><h6>Phone:</h6></li>
               <li><h6>Lorem Ipsum..</h6></li>
           </ul>
        </div>
        <div class="col-xs-6">
        <h4>Payement Method</h4>
        <ul class="list-inline">
               <li><h6>Account No:</h6></li>
               <li><h6>123456789</h6></li>
           </ul>
           <ul class="list-inline">
               <li><h6>Account Name:</h6></li>
               <li><h6>JHONE DOE.</h6></li>
           </ul>
           <ul class="list-inline">
               <li><h6>Branch Name:</h6></li>
               <li><h6>XYZ</h6></li>
           </ul>
        </div>
    </div>
</div>

<div  style="padding:0 20px;">
<table class="table print-table-wg table-borderless">
  <thead>
    <tr class="spacer" style="border-radius: 30px;">
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Handle</th>
    </tr>
  </thead>
  <tbody>
    <tr class="spacer">
      <td scope="row">1</td>
      <td>Mark</td>
      <td>Otto</td>
      <td>@mdo</td>
    </tr>
    <tr class="spacer">
      <td scope="row">2</td>
      <td>Jacob</td>
      <td>Thornton</td>
      <td>@fat</td>
    </tr>
    <tr class="spacer">
      <td scope="row">3</td>
      <td>Larry</td>
      <td>the Bird</td>
      <td>@twitter</td>
    </tr>
  </tbody>
</table>
</div>

<div class="row right-peice-wg" style="padding:0 30px 50px 30px;margin-bottom:30px">
    <div class="col-xs-9">
         <h4 class="title">Lorem upsum is a demo</h4>
         <p style="width: 80%;font-size:12px">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
         <p style="width: 80%;font-size:12px">Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
         <h4>Thank you for business with us.</h4>

        </div>
    <div class="col-xs-3 text-center">
      <div class="row">
          <div class="col-xs-6">
              <h6>Subtotal</h6>
          </div>
          <div class="col-xs-6">
             <h6>$20.00</h6>
          </div>
      </div>
      <div class="row">
          <div class="col-xs-6">
              <h6>Discount</h6>
          </div>
          <div class="col-xs-6">
             <h6>$00.00</h6>
          </div>
      </div>
      <div class="row">
          <div class="col-xs-6">
              <h6>Tax:(10%)</h6>
          </div>
          <div class="col-xs-6">
             <h6>$5.00</h6>
          </div>
      </div>
      <div class="row last-total">
          <div class="col-xs-6">
              <h6>Total<h6>
          </div>
          <div class="col-xs-6">
             <h6>$15.00</h6>
          </div>
      </div>
    </div>
</div>  

<div class="print-foot-wg">
    <div class="row">
        <div class="col-xs-7">
            <div class="row">
            <div class="col-xs-4">
                <div class="media">
                    <div class="media-left">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">John Doe</h6>
                        <h6>Lorem ipsum...</h6>
                    </div>
                    </div>
                </div>
                <div class="col-xs-4">
                <div class="media">
                    <div class="media-left">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">John Doe</h6>
                        <h6>Lorem ipsum...</h6>
                    </div>
                    </div>
                </div>
                <div class="col-xs-4">
                <div class="media">
                    <div class="media-left">
                        <i class="fa fa-phone"></i>
                    </div>
                    <div class="media-body">
                        <h6 class="media-heading">John Doe</h6>
                        <h6>Lorem ipsum...</h6>
                    </div>
                    </div>
                </div>
                </div>

                </div>
                <div class="col-xs-5">

</div>   
                </div>
            </div>
        </div>
    </div>
</div>

</section>

<!-- 


<div class="row small-spacing" id="concession_to_print">


<div class="col-xs-4" style="{{ $lang == 'ar' ? 'float: left !important' : '' }}">
    <div style="text-align: left" class="my-1">
        <h5><b>{{optional($branchToPrint)->name_en}} </b> </h5>
        <h5><b>Phone 1 : </b> {{optional($branchToPrint)->phone1}} </h5>
        <h5><b>Phone 2 : </b> {{optional($branchToPrint)->phone2}} </h5>
        <h5><b>Address : </b> {{optional($branchToPrint)->address_en}} </h5>
        <h5><b>Fax : </b> {{optional($branchToPrint)->fax}} </h5>
        <h5><b>Tax Number : </b> {{optional($branchToPrint)->tax_card}} </h5>
    </div>
</div>

<div class="col-xs-4 text-center" style="{{ $lang == 'ar' ? 'float: left !important' : '' }}">
    <img style="width: 200px; height: 120px"
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

<div class="clearfix"></div>

<h4 class="text-center head-title-print" style="
    background: #8B8B8B !important;
    color: white !important;
    padding: 7px !important;
    margin: 15px 0 !important;
    ">

@if($concession->type == 'add' )
  {{ __('Add Concession') }}
@else
{{ __('Withdrawal Concession') }} </span>
@endif

</h4>

    <div class="wg-tb-snd" style="margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Number')}}</th>
                        <td>{{$concession->type == 'add' ? $concession->add_number : $concession->withdrawal_number }}</td>
                    </tr>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Time & Date')}}</th>
                        <td>{{$concession->time}} - {{$concession->date}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Item Number')}}</th>
                        <td>{{optional($concession->concessionable)->number}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Execution Status')}}</th>
                        <td>

                        @if($concession->concessionExecution)


                                    @if($concession->concessionExecution ->status == 'pending' )
                                    {{__('Processing')}}

                                    @elseif($concession->concessionExecution ->status == 'finished' )
                                    {{__('Finished')}}

                                    @elseif($concession->concessionExecution ->status == 'late' )
                                    {{__('Late')}}
                                    @endif


                                    @else
                                    {{__('Not determined')}}

                                    @endif

                        </td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Status')}}</th>
                        <td>{{__($concession->status)}}</td>
                    </tr>


                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('User Name')}}</th>
                        <td>{{optional($concession->user)->name}}</td>
                    </tr>



                    </tbody>
                </table>
            </div>

            <div class="col-xs-4">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Type')}}</th>
                        <td>{{__($concession->type . ' concession')}}</td>
                    </tr>

                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Concession Type')}}</th>
                        <td>{{optional($concession->concessionType)->name}}</td>
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
                    <th style="background:#CCC !important;color:black">{{__('Quantity')}}</th>
                    <th style="background:#CCC !important;color:black">{{__('Price')}}</th>
                </tr>
                </thead>
                <tbody>

                @foreach($concession->concessionItems as $index=>$item)

                    <tr class="item">
                        <td>{{$index + 1}}</td>
                        <td>{{optional($item->part)->name}}</td>
                        <td>{{$item->partPrice && $item->partPrice->unit ? $item->partPrice->unit->unit : '---'}}</td>
                        <td>{{$item->quantity}}</td>
                        <td>{{$item->price}}</td>
                    </tr>
                @endforeach
                </tbody>

            </table>
        </div>
    </div>

    <div class="wg-tb-snd" style="margin:5px 20px 20px;padding:10px;border-radius:5px">
        <div class="row">
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Total Quantity')}}</th>
                        <td>{{$concession->total_quantity}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-xs-6">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="background:#CCC !important;color:black" scope="row">{{__('Total Price')}}</th>
                        <td>{{$concession->total}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <div>
        {!! $concession->description !!}
    </div>
</div>

 -->
