<div id="colum-visible-modal" class="modal fade new-modal-wg" role="dialog">
    <div class="modal-dialog" style="width:175px;padding-top:100px">
        <div class="modal-content" style="overflow:hidden">
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'id')">
                {!! __('#') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'name')">
                {!! __('Name') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'type')">
                {!! __('Type') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'quantity')">
                {!! __('Quantity') !!}
            </button>

            <button class="col-md-12 columns-btns btn btn-default"
                    onclick="hide_column(event ,'reviewable')">
                {!! __('Reviewable') !!}
            </button>

            <button class="col-md-12 columns-btns btn btn-default"
                    onclick="hide_column(event ,'taxable')">
                {!! __('Taxable') !!}
            </button>

            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'status')">
                {!! __('Status') !!}
            </button>


            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'created-at')">
                {!! __('Created At') !!}
            </button>
            <button class="col-md-12 columns-btns btn btn-default"
                onclick="hide_column(event ,'updated-at')">
                {!! __('Updated At') !!}
            </button>
        </div>
    </div>
</div>
