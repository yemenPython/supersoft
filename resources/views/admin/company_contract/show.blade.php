@if(isset($item))
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center"></i>{{__('Renewable')}}   [ <i class="fa fa-{{$item->renewable ? 'check' : 'times'}}"></i> ]</h4>

    </div>
    <div class="" style="margin:20px 0">
        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-12">
                        <table class="table wg-inside-table">
                            <tr>
                                <th style="width: 30%;">
                                    {{__('Company name')}}
                                </th>
                                <td>
                                    {{optional($item->branch)->name}}
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
                                    {{__('Company Address')}}
                                </th>
                                <td>
                                    {{optional($item->branch)->address_ar}}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table wg-inside-table">
                        <tr>
                            <th style="width: 30%;">
                                {{__('Duration Of Partnership')}}
                            </th>
                            <td>
                                {{$item->partnership_duration}}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Commercial Registration No')}}
                        </th>
                        <td>
                            {{$item->commercial_registration_no}}
                        </td>
                        <th style="width: 30%;">
                            {{__(' Commercial Feature')}}
                        </th>
                        <td>
                            {{$item->commercial_feature}}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Contract Date')}}
                        </th>
                        <td>
                            {{$item->contract_date}}
                        </td>
                        <th style="width: 30%;">
                            {{__('Date Of Registration')}}
                        </th>
                        <td>
                            {{$item->register_date}}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Company Purpose')}}
                        </th>
                        <td>
                            {{$item->company_purpose}}
                        </td>
                        <th style="width: 30%;">
                            {{__('Share Capital')}}
                        </th>
                        <td>
                            {{$item->share_capital}}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Start On')}}
                        </th>
                        <td>
                            {{$item->start_at}}
                        </td>

                        <th style="width: 30%;">
                            {{__('End On')}}
                        </th>
                        <td>
                            {{$item->end_at}}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Partners')}}
                        </th>
                        <td>
                            @foreach($item->partners as $partner) {{$partner->partner}} @if (!$loop->last),@endif  @endforeach
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Company Share')}}
                        </th>
                        <td>
                            @foreach($item->company_shares as $company_share) {{$company_share->company_share}} @if (!$loop->last),@endif  @endforeach
                        </td>
                    </tr>
                </table>
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
