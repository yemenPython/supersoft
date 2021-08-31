<div class="modal fade" id="updateStopDate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center" id="myModalLabel-1">{{__('Detect The Stop Date of Dealing')}} <span class="text-danger" id="bankName"></span></h4>
            </div>
            <div class="modal-body" style="height: 150px;">
                    <form method="post" action="" id="formUpdateStopDate">
                        @method('get')
                        <div class="form-group col-md-12">
                            <label> {{ __('Date') }} {!! required() !!}</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input required  name="stop_date" value=""
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>
                    </form>
            </div>
            <div class="modal-footer">

                <button form="formUpdateStopDate" type="submit" class="btn hvr-rectangle-in saveAdd-wg-btn">
                    <i class="ico ico-left fa fa-save"></i>
                    {{__('Save')}}
                </button>
                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                    {{__('Close')}}
                </button>
            </div>
        </div>
    </div>
</div>
