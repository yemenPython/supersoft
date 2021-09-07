@if(isset($item))
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center"><i class="fa fa-eye"></i></h4>
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


            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Tax Card')}}
                        </th>
                        <td>
                            {{optional($item->branch)->tax_card}}
                        </td>
                    </tr>
                </table>
            </div>


            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Area')}}
                        </th>
                        <td>
                            {{$item->area}}
                        </td>
                    </tr>
                </table>
            </div>



            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Errands')}}
                        </th>
                        <td>
                            {{$item->errands}}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Date Of Registration')}}
                        </th>
                        <td>
                            {{$item->register_date}}
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
