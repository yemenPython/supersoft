<div class="modal fade" id="item_notes_{{$index}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content wg-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel-1">{{__('Notes')}}</h4>
            </div>

            <div class="modal-body">

                <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="inputDescription" class="control-label">{{__('Description')}}</label>
                        <div class="input-group">
                            <textarea name="description" class="form-control" rows="4" cols="150" id="area_item_notes_{{$index}}"
                                      onkeyup="itemNotesData('{{$index}}')">{{isset($item) ? $item->notes : ''}}</textarea>
                        </div>

                    </div>
                </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                    {{__('Close')}}
                </button>

            </div>
        </div>
    </div>
</div>
