<option value="{{ $child->id }}" data-mainType="{{$child->name}}"
{{isset($bankAccount) && $bankAccount->main_type_bank_account_id == $child->id ? 'selected' : ''}}>
    {{ isset($counter) ? $counter.' .' : ''  }} {{ $child->name }}
</option>
