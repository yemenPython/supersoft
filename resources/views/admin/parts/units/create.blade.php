{{--create model--}}
<div class="modal fade" id="part_new_unit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content wg-content">
            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

                <h4 class="modal-title" id="myModalLabel-1">{{__('New Part')}}</h4>
            </div>

            <div class="modal-body">
                <div class="row" id="unit_form_place">

                    @include('admin.parts.units.form')

                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" onclick="storeUnit()">
                    {{__('Save')}}
                </button>

                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light"
                        data-dismiss="modal">
                    {{__('Close')}}
                </button>

            </div>
        </div>
    </div>
</div>

