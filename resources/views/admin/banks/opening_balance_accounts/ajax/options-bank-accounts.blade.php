<option value=""> {{ __('Select') }} </option>
@foreach($bankAccounts as $bankAccount)
    <option value="{{$bankAccount->id}}"  {{isset($item) && $item->bank_account_child_id == $bankAccount->id ? 'selected' : ''}}>
        {{ optional($bankAccount->mainType)->name }}
        @if ($bankAccount->subType)
            <strong class="text-danger">[   {{ optional($bankAccount->subType)->name }}  ]</strong>
        @endif
        @if ($bankAccount->bankData)
            <strong class="text-danger">[   {{ optional($bankAccount->bankData)->name }}  ]</strong>
        @endif
    </option>
@endforeach
