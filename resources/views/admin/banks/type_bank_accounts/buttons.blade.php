<button style="margin-bottom: 12px; border-radius: 5px"
        class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left"
        onclick="open_modal('create_main_group')">
    <i class="ico fa fa-plus"></i>
    {{ __('Creat Main Type') }}
</button>

<button style="margin-bottom: 12px; border-radius: 5px"
    class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left"
    onclick="open_modal('create')">
    <i class="ico fa fa-plus"></i>
    {{ __('Creat Sub Main Type') }}
</button>

<button style="margin-bottom: 12px; border-radius: 5px"
    class="btn btn-icon btn-icon-left  waves-effect waves-light hvr-bounce-to-left resetAdd-wg-btn"
    onclick="open_modal('edit')">
    <i class="ico fa fa-edit"></i>
    {{ __('Edit') }}
</button>

<button style="margin-bottom: 12px; border-radius: 5px"
    class="btn btn-icon btn-icon-left btn-delete-wg waves-effect waves-light hvr-bounce-to-left"
    onclick="open_modal('delete')">
    <i class="ico fa fa-trash-o"></i>
    {{ __('Delete') }}
</button>