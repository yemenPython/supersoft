@if(isset($item))
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ __('Show Bank Data') }}</h4>
    </div>
    <div class="" style="margin:20px 0">

        <div class="row top-data-wg" style="box-shadow: 0 0 7px 1px #DDD;margin:5px 5px 10px;padding-top:20px">
            <div class="row">
                <div class="col-md-12">

                    <div class="col-md-12">
                        <table class="table wg-inside-table">
                            <tr>
                                <th style="width: 30%;">{{__('Name in Arabic')}}</th>
                                <td>{{$item->name_ar}}</td>

                                <th style="width: 30%;">{{__('Name in English')}}</th>
                                <td>{{$item->name_en}}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-12">

                    <div class="col-md-12">
                        <table class="table wg-inside-table">
                            <tr>
                                <th style="width: 30%;">
                                    {{__('ShortName Ar')}}
                                </th>
                                <td>
                                    {{$item->short_name_ar}}
                                </td>

                                <th style="width: 30%;">
                                    {{__('ShortName En')}}
                                </th>
                                <td>
                                    {{$item->short_name_en}}
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
                            {{__('Phone')}}
                        </th>
                        <td>
                            {{$item->phone}}
                        </td>

                        <th style="width: 30%;">
                            {{__('Branch')}}
                        </th>
                        <td>
                            {{$item->branch}}
                        </td>
                    </tr>
                </table>
            </div>


            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Branch Code')}}
                        </th>
                        <td>
                            {{$item->code}}
                        </td>

                        <th style="width: 30%;">
                            {{__('Swift Branch Code')}}
                        </th>
                        <td>
                            {{$item->swift_code}}
                        </td>
                    </tr>
                </table>
            </div>


            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Bank Website')}}
                        </th>
                        <td>
                           <a href=" {{$item->website ?? 'javascript:void(0);'}}" target="_blank"><i class="fa fa-link"></i></a>
                        </td>

                        <th style="width: 30%;">
                            {{__('Bank Url')}}
                        </th>
                        <td>
                            <a href=" {{$item->url ?? 'javascript:void(0);'}}" target="_blank"><i class="fa fa-link"></i></a>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Start Date With Bank')}}
                        </th>
                        <td>
                            {{$item->date}}
                        </td>

                        <th style="width: 30%;">
                            {{__('Start Date With Bank')}}
                        </th>
                        <td>
                            {{$item->stop_date ?? '--'}}
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-12">
                <table class="table wg-inside-table">
                    <tr>
                        <th style="width: 30%;">
                            {{__('Address')}}
                        </th>
                        <td>
                            {{$item->address}}
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
