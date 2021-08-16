<div id="colum-visible-modal" class="modal fade new-modal-wg" role="dialog">
    <div class="modal-dialog" style="width:175px;padding-top:100px">
        <div class="modal-content" style="overflow:hidden">
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'id')">
                #
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'branch-name')">
                {!! __('Branches') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'Membership-No')">
                {!! __('Membership No') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'company-type')">
                {!! __('Company Type') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'register-date')">
                {!! __('Date of register in the union') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'funds-on')">
                {!! __('End date') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'created-at')">
                {!! __('created at') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'updated-at')">
                {!! __('updated at') !!}
            </button>
        </div>
    </div>
</div>
