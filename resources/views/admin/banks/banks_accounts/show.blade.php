@if(isset($item))
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{{ __('Accounts') }}</h4>
    </div>
    <div class="" style="margin:20px 0">
       @include('admin.banks.banks_accounts.forms.show_from')
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
