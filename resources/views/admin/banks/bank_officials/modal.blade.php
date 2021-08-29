<div class="modal fade" id="add-employee-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33">
    <div class="modal-dialog" role="document">
        <div class="modal-content wg-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel-1">{{__('Bank Officials')}}</h4>
            </div>

            <form id="newAssetEmployee-form" method="post" action="{{ route('admin:banks.bank_officials.store') }}">
                <div class="modal-body">
                    <div class="row">
                        @csrf
                        <input type="hidden" value="{{$bankData->id}}" name="bank_data_id" id="bank_data_id">
                        <input type="hidden" value="" name="old_bank_official_id" id="old_bank_official_id">

                        <div class="form-group col-md-6">
                            <label> {{ __('Name in Arabic') }}  {!! required() !!} </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-user"></span>
                                <input type="text" name="name_ar" id="name_ar" class="form-control">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Name in English') }}  </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-user"></span>
                                <input type="text" name="name_en" id="name_en" class="form-control">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Phone') }}  </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-phone"></span>
                                <input type="text" name="phone1" id="phone1" class="form-control">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Inside Phone') }}  </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-phone"></span>
                                <input type="text" name="phone2" id="phone2" class="form-control">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Mobile') }}  </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-phone"></span>
                                <input type="text" name="phone3" id="phone3" class="form-control">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('E-Mail') }}  </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-yahoo"></span>
                                <input type="text" name="email" id="email" class="form-control">
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('words.date-from') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="date_from" id="date_from" value="{{now()}}"
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('words.date-to') }} </label>
                            <div class="input-group">
                                <span class="input-group-addon"><li class="fa fa-calendar"></li></span>
                                <input name="date_to" id="date_to" value="{{now()}}"
                                       class="form-control date js-example-basic-single" type="date"/>
                            </div>
                        </div>

                        <div class="form-group col-md-6">
                            <label> {{ __('Job') }}  </label>
                            <div class="input-group">
                                <span class="input-group-addon fa fa-bug"></span>
                                <input type="text" name="job" id="job" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="control-label">{{__('Status')}}</label>
                            <div class="switch primary" style="margin-top: 15px">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" id="switch-1" name="status" value="1" checked>
                                <label for="switch-1">{{__('Active')}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="">
                    <button class="btn btn-primary waves-effect waves-light" type="submit">
                        {{__('save')}}
                    </button>
                    <button type="button" class="btn btn-danger btn-sm waves-effect waves-light" data-dismiss="modal">
                        {{__('Close')}}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
