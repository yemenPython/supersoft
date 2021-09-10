@if(isset($item))
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center"><i class="fa fa-user-secret"></i></h4>
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
                                {{__('Fax')}}
                            </th>
                            <td>
                                {{optional($item->branch)->fax}}
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
                            {{__('Registration Number')}}
                        </th>
                        <td>
                            {{$item->register_no}}
                        </td>

                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Phone 1')}}
                        </th>
                        <td>
                            {{optional($item->branch)->phone1}}
                        </td>
                        <th style="width: 30%;">
                            {{__('Phone 2')}}
                        </th>
                        <td>
                            {{optional($item->branch)->phone2}}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Approval Expiration Date')}}
                        </th>
                        <td>
                            {{$item->expiration_date}}
                        </td>
                        <th style="width: 30%;">
                            {{__('Commercial Feature')}}
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
                            {{__('Company Type')}}
                        </th>
                        <td>
                            {{$item->company_type}}
                        </td>

                        <th style="width: 30%;">
                            {{__('The Company Field Of Activity')}}
                        </th>
                        <td>
                            {{$item->company_field}}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Owners')}}
                        </th>
                        <td>
                            @foreach($item->owners as $owner) {{$owner->owner}} @if (!$loop->last),@endif  @endforeach
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Representatives')}}
                        </th>
                        <td>
                            @foreach($item->representatives as $representative) {{optional($representative->employee)->name}} @if (!$loop->last),@endif  @endforeach
                        </td>
                    </tr>
                </table>
            </div>


            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Phone')}}
                        </th>
                        <td>
                            @foreach($item->phones as $phone) {{$phone->phone}} @if (!$loop->last),@endif  @endforeach
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
