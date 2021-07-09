@if(isset($store))
    <div class="modal-header">

    <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ __('Show store data') }}</h4>

    </div>
    <div class="" style="margin:20px 0">

    <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">
        <div class="row">
            <div class="col-md-12">
              
            <div class="col-md-12">
            <table class="table wg-inside-table">
              <tr>
                <th style="width: 30%;">
                {{__('Name in Arabic')}}
                </th>
                <td>
                {{$store->name_ar}}
                </td>
              </tr>
            </table>
            </div>
            </div>

            <div class="col-md-12">

            <div class="col-md-12">
            <table class="table wg-inside-table">
              <tr>
                <th style="width: 30%;">
                {{__('Name in English')}}
                </th>
                <td>
                {{$store->name_en}}
                </td>
              </tr>
            </table>
            </div>
            </div>


            </div>

            <div class="col-md-12">
            <table class="table wg-inside-table">
              <tr>
                <th style="width: 30%;">
                {{__('Store Phone')}}
                </th>
                <td>
                {{$store->store_phone}}
                </td>
              </tr>
            </table>
            </div>
         

            <div class="col-md-12">
            <table class="table wg-inside-table">
              <tr>
                <th style="width: 30%;">
                {{__('Store Address')}}
                </th>
                <td>
                {{$store->store_address}}
                </td>
              </tr>
            </table>
            </div>
        

            <div class="col-md-12">
            <table class="table wg-inside-table">
              <tr>
                <th style="width: 30%;">
                {{__('Notes')}}
                </th>
                <td>
                {{$store->note}}
                </td>
              </tr>
            </table>
            </div>

            </div>
            </div>
            </div>
            </div>
        


            <div class="col-md-12">

            <div class="ribbon ribbon-r bg-secondary show-ribbon" style="right:-15px;top:10px;background:#5685CC !important">
                <p class="mb-0" style="color:white;">{{__('Stores officials')}}</p>
            </div>

                <br>
                <br>
                <br>
           
            </div>
        
            <div class="row">

            <div class="for-scroll-wg">
            @if(count($store->storeEmployeeHistories) > 0)
                @foreach($store->storeEmployeeHistories as $employeeHistory)
                <div class="responsible-persons-show">

                <div class="col-md-12">
                
            <table class="table table-bordered wg-inside-table">
              <tr>
                <th style="width: 47%;">
                {{__('Employee Name In Arabic')}}
                </th>
                <td>
                {{optional($employeeHistory->employee)->name_ar}}
                </td>
              </tr>
            </table>
            </div>

            <div class="col-md-12">
            <table class="table table-bordered wg-inside-table">
              <tr>
                <th style="width: 47%;">
                {{__('Employee Name In English')}}
                </th>
                <td>
                {{optional($employeeHistory->employee)->name_en}}
                </td>
              </tr>
            </table>
            </div>

            <div class="col-md-6">
            <table class="table table-bordered wg-inside-table">
              <tr>
                <th style="width: 30%;">
                {{__('Phone1')}}
                </th>
                <td>
                {{optional($employeeHistory->employee)->phone1}}
                </td>
              </tr>
            </table>
            </div>

            <div class="col-md-6">
            <table class="table table-bordered wg-inside-table">
              <tr>
                <th style="width: 30%;">
                {{__('Phone2')}}
                </th>
                <td>
                {{optional($employeeHistory->employee)->phone2}}
                </td>
              </tr>
            </table>
            </div>

            <div class="col-md-6">
            <table class="table table-bordered wg-inside-table">
              <tr>
                <th style="width: 30%;">
                {{__('Start Date')}}
                </th>
                <td>
                {{$employeeHistory->start}}
                </td>
              </tr>
            </table>
            </div>

            <div class="col-md-6">
            <table class="table table-bordered wg-inside-table">
              <tr>
                <th style="width: 30%;">
                {{__('End Date')}}
                </th>
                <td>
                {{$employeeHistory->end}}
                </td>
              </tr>
            </table>
            </div>

                </div>
                @endforeach
            @else
            </div>
                <div class="col-md-12">
                  <ul class="list-inline no-officials" style="margin:0;padding:0">
                    <li class="icon-official">
                    <h4 ><i class="fa fa-bell"></i></h4>
                    </li>
                    <li>
                    <h4 class="text-for-official">{{__('No Stores officials')}}</h4>
                    </li>
                  </ul>
                </div>
            @endif
        </div>
    </div>
    </div>
    </div>
    
    
    <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('Close')}}</button>
    </div>
@else
    <div class="modal-header">
        <h4 class="modal-title text-center">{{__('Please Try again')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body bg-danger">
        <h1 class="text-center white">{{__('Some thing went wrong')}}</h1>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Close')}}</button>
    </div>
@endif
