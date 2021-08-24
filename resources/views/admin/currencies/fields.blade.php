<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNameAR" class="control-label">{{__('Name in Arabic')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-file-o"></i></span>
                    <input type="text" name="name_ar" class="form-control"
                           value="{{isset($currency) ? $currency->name_ar : old('name_ar')}}" id="inputNameAR"
                           placeholder="{{__('Name in Arabic')}}">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="inputNameEN" class="control-file-o">{{__('Name in English')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    <input type="text" name="name_en" class="form-control" id="inputNameEN"
                           value="{{isset($currency) ? $currency->name_en : old('name_en')}}"
                           placeholder="{{__('Name in English')}}">
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="inputSymbolAR" class="control-label">{{__('Symbol in Arabic')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    <input type="text" name="symbol_ar" class="form-control"
                           value="{{isset($currency) ? $currency->symbol_ar : old('symbol_ar')}}" id="inputSymbolAR"
                           placeholder="{{__('Symbol in Arabic')}}">
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="inputSymbolEN" class="control-label">{{__('Symbol in English')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    <input type="text" name="symbol_en" class="form-control" id="inputSymbolEN"
                           value="{{isset($currency) ? $currency->symbol_en : old('symbol_en')}}"
                           placeholder="{{__('Symbol in English')}}">
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-12">
        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Is Main Currency')}}</label>
                <div class="checkbox primary" style="margin-top: 6px">
                    <input type="hidden" name="is_main_currency" value="0">
                    <input type="checkbox" id="checkbox-1" name="is_main_currency" value="1"
                        {{isset($currency) && $currency->is_main_currency ? 'checked' : ''}}>
                    <label for="checkbox-1">{{__('Active')}}</label>
                </div>
            </div>
            <span class="text-from text-danger">{{__('if you detected this currency to be main currency other currency will be not main')}}</span>
        </div>

        <div class="col-md-3">
            <div class="form-group has-feedback">
                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                <div class="checkbox primary" style="margin-top: 6px">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" id="checkbox-2" name="status" value="1"
                        {{isset($currency) && $currency->status ? 'checked' : ''}} {{!isset($currency) ? 'checked' : ''}}>
                    <label for="checkbox-2">{{__('Active')}}</label>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group has-feedback">
                <label for="conversion_factor"
                       class="control-label">{{__('Conversion Factor')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                    <input type="number" name="conversion_factor" class="form-control"
                           id="conversion_factor" value="{{isset($currency) ? $currency->conversion_factor : old('conversion_factor')}}"
                           placeholder="{{__('Conversion Factor')}}">
                </div>
            </div>
        </div>
    </div>
</div>
