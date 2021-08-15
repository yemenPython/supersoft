<div class="modal-header">
    <p class="text-center text-danger" style="font-size: 18px">{{__('Maintenance centers')}} [ {{ $item->name }}]</p>
</div>
<div class="modal-body">
    <div class="col-xs-12">
        <table class="table static-table-wg">
            <tbody>
            <tr>
                <th style="width:20% !important">{{__('Name in Arabic')}}</th>
                <td> {{$item->name_ar}} </td>
                <th style="width:20% !important">{{__('Name in English')}}</th>
                <td> {{$item->name_en}} </td>

            </tr>
            <tr>
                <th style="width:20% !important">{{__('Country')}}</th>
                <td>{{ optional($item->country)->name }} </td>
                <th style="width:20% !important">{{__('Status')}}</th>
                <td>@if($item->status == 1){{ __('Active') }}@else{{ __('Inactive') }}@endif</td>
            </tr>

            <tr>
                <th style="width:20% !important">{{__('Address')}}</th>
                <td> {{$item->address}} </td>
                <th style="width:20% !important">{{__('Fax')}}</th>
                <td> {{$item->fax}} </td>
            </tr>

            <tr>
                <th style="width:20% !important">{{__('Commercial Number')}}</th>
                <td> {{$item->commercial_number}} </td>
                <th style="width:20% !important">{{__('Tax Card')}}</th>
                <td> {{$item->tax_card}} </td>
            </tr>

            <tr>
                <th style="width:20% !important">{{__('Identity Number')}}</th>
                <td> {{$item->identity_number}} </td>
                <th style="width:20% !important">{{__('Commercial Record Area')}}</th>
                <td> {{$item->commercial_record_area}} </td>
            </tr>

            <tr>
                <th style="width:20% !important">{{__('Tax File Number')}}</th>
                <td> {{$item->tax_file_number}} </td>
                <th style="width:20% !important">{{__('City')}}</th>
                <td>{{ optional($item->city)->name }} </td>
            </tr>

            <tr>
                <th style="width:20% !important">{{__('Phone')}}</th>
                <td> {{$item->phone_2}} </td>
                <th style="width:20% !important">{{__('Phone')}}</th>
                <td> {{$item->phone_1}} </td>
            </tr>

            <tr>
                <th style="width:20% !important">{{__('E-Mail')}}</th>
                <td> {{$item->email}} </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer text-left">
    <button type="button" class="btn btn-danger waves-effect waves-light"
            data-dismiss="modal"><i class='fa fa-close'></i>
        {{__('Close')}}</button>
</div>
