<div class="col-md-12 margin-bottom-20">
    <ul class="nav nav-tabs nav-justified" id="myTabs-justified" role="tablist">
        <li role="presentation" class="active">
            <a href="#home-justified" id="home-tab-justified" role="tab" data-toggle="tab" aria-controls="home"
               aria-expanded="true">
                {{__('Part Data')}}
            </a>
        </li>
        <li role="presentation" disabled="">

            <a class="unit_tab_li" href="#profile-justified" role="tab" data-toggle="{{isset($part) ? 'tab' : ''}}"
               id="profile-tab-justified" aria-controls="profile">
                {{__('Units')}}
            </a>

        </li>
    </ul>
    <!-- /.nav-tabs -->
    <div class="tab-content" id="myTabContent-justified">
        <div class="tab-pane fade in active part_tab" role="tabpanel" id="home-justified"
             aria-labelledby="home-tab-justified">

            <div class="row">

                <form method="post" action="{{route('admin:parts.store')}}" class="form" enctype="multipart/form-data"
                      id="part_form_data">
                    @csrf
                    @method('post')

                    @if(isset($part))
                        <input type="hidden" name="part_id" value="{{$part->id}}">
                    @endif

                    @if(authIsSuperAdmin())
                        <div class="col-md-12">
                            <div class="col-md-12">
                                <div class="form-group has-feedback">
                                    <label for="inputStore" class="control-label">{{__('Branches')}}</label>
                                    <div class="input-group">
                                        <span class="input-group-addon fa fa-file"></span>
                                        <select class="form-control js-example-basic-single close_form" name="branch_id"
                                                id="branch_id"
                                                {{isset($part) ? 'disabled':''}}
                                                onchange="changeBranch()"
                                        >
                                            <option value="">{{__('Select Branch')}}</option>

                                            @foreach($branches as $branch)
                                                <option
                                                    value="{{$branch->id}}"
                                                    {{request()->has('branch_id') && request()['branch_id'] == $branch->id ? 'selected':'' }}
                                                    {{isset($part) && $part->branch_id == $branch->id? 'selected':''}}>
                                                    {{$branch->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    {{input_error($errors,'branch_id')}}
                                </div>

                                @if(isset($part))
                                    <input type="hidden" name="branch_id" value="{{$part->branch_id}}">
                                @endif

                            </div>

                        </div>
                    @endif

                    <div class="col-md-12">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputNameAr" class="control-label">{{__('Name Ar')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-file-o"></li></span>
                                    <input type="text" name="name_ar" class="form-control close_form" id="inputNameEn"
                                           placeholder="{{__('NameAr')}}"
                                           value="{{old('name_ar', isset($part)? $part->name_ar:'')}}">
                                </div>
                                {{input_error($errors,'name_ar')}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputName" class="control-label">{{__('Name En')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-file-o"></li></span>
                                    <input type="text" name="name_en" class="form-control close_form" id="inputNameEn"
                                           placeholder="{{__('NameEn')}}"
                                           value="{{old('name_en', isset($part)? $part->name_en:'')}}">
                                </div>
                                {{input_error($errors,'name_en')}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="inputStore" class="control-label">{{__('Store')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-building-o"></span>
                                    <select class="form-control js-example-basic-single close_form" name="stores[]"
                                            id="store_id" multiple>
                                        @foreach($stores as $store)
                                            <option value="{{$store->id}}"
                                                    {{isset($part) && in_array($store->id, $part->stores()->pluck('store_id')->toArray() )? 'selected':''}}
                                                    class="removeToNewData">
                                                {{$store->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{input_error($errors,'store_id')}}
                            </div>

                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputQuantity" class="control-label">{{__('Quantity')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-bars"></li></span>
                                    <input type="number" name="quantity" class="form-control close_form" disabled
                                           placeholder="{{__('quantity')}}"
                                           value="{{old('quantity', isset($part)? $part->quantity :0)}}">
                                </div>
                                {{input_error($errors,'quantity')}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="inputType" class="control-label">{{__('Main Parts Type')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-check-circle"></span>

                                    <select class="form-control js-example-basic-single data_by_branch close_form"
                                            onchange="subPartsTypes()"
                                            name="spare_part_type_ids[]" id="parts_types_options" multiple>

                                        <option value="" class="remove">{{__('Select spare part type')}}</option>

                                        @foreach($mainTypes as $index=>$mainType)
                                            <option value="{{$mainType->id}}" data-order="{{$index+1}}"
                                                    {{isset($part) && in_array($mainType->id, $part->spareParts()->pluck('spare_part_type_id')->toArray()) ? 'selected':''}}
                                                    class="removeToNewData">
                                                {{$index + 1}} . {{$mainType->type}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{input_error($errors,'spare_part_type_id')}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="inputType" class="control-label">{{__('Sub Parts Type')}}</label>
                                <div class="input-group" id="newSubPartsTypes">
                                    <span class="input-group-addon fa fa-check"></span>

                                    <select class="form-control js-example-basic-single data_by_branch close_form"
                                            name="spare_part_type_ids[]" id="sub_parts_types_options" multiple>

                                        <option value="" class="remove">{{__('Select sub spare part type')}}</option>

                                        @foreach($subTypes as $key => $value)

                                            <option value="{{$key}}" class="removeToNewData"
                                                {{isset($part) && in_array($key, $part->spareParts()->pluck('spare_part_type_id')->toArray())? 'selected':''}}
                                            >
                                                {{$value}}
                                            </option>
                                        @endforeach
                                        {{--                        {!! subPartTypeSelectAsTree('removeToNewData' ,isset($part) ? $part->id : NULL) !!}--}}
                                    </select>
                                </div>
                                {{input_error($errors,'spare_part_type_id')}}
                            </div>
                        </div>


                    </div>

                    <div class="col-md-12">

                        <div class="col-md-4">
                            <div class="form-group has-feedback">
                                <label for="inputPhone" class="control-label">{{__('Alternative parts')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon fa fa-gears"></span>
                                    <select class="form-control js-example-basic-single data_by_branch close_form"
                                            name="alternative_parts[]" multiple="multiple" id="parts_options">
                                        @foreach($parts as $partData)
                                            <option value="{{$partData->id}}" class="removeToNewData"
                                                {{isset($part) && in_array( $partData->id, $part->alternative->pluck('id')->toArray())? 'selected':''}}>
                                                {{$partData->name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                {{input_error($errors,'alternative_parts')}}
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group has-feedback">
                                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                                <div class="switch primary">
                                    <input type="checkbox" class="close_form" id="switch-1"
                                           name="status"{{!isset($part)?'checked':''}}
                                        {{isset($part) && $part->status? 'checked':''}}
                                    >
                                    <label for="switch-1">{{__('Active')}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">

                            <div class="form-group has-feedback">
                                <label for="inputPhone" class="control-label">{{__('Is Service')}}</label>
                                <div class="switch primary">
                                    <input type="checkbox" class="close_form" id="switch-2" name="is_service"
                                        {{isset($part) && $part->is_service? 'checked':''}}
                                    >
                                    <label for="switch-2">{{__('Active')}}</label>
                                </div>
                            </div>
                            {{input_error($errors,'is_service')}}
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputDescription" class="control-label">{{__('Description')}}</label>
                                <div class="input-group">
                                    <textarea name="description" class="form-control close_form" rows="4" cols="150"
                                    >{{old('description', isset($part)? $part->description :'')}}</textarea>
                                </div>
                                {{input_error($errors,'description')}}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="inputDescription" class="control-label">{{__('Part In Store')}}</label>
                                <div class="input-group">
                                    <textarea name="part_in_store" class="form-control close_form" rows="4" cols="150"
                                    >{{old('part_in_store', isset($part)? $part->part_in_store :'')}}</textarea>
                                </div>
                                {{input_error($errors,'part_in_store')}}
                            </div>
                        </div>

                        <div class="col-md-4 img-wg-form">
                            <div class="form-group">
                                <label for="inputimage" class="control-label">{{__('Image')}}</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                                    <input type="file" name="img" class="form-control close_form"
                                           onchange="preview_image(event)">
                                </div>
                                {{input_error($errors,'img')}}
                            </div>

                            @if(isset($part->img))

                                <div class="image-container small-wg handing ani image-format-hd"
                                     style="width: 300px !important;">
                                    <a href="http://localhost/cars/public/default-images/defualt.png"
                                       data-lightbox="roadtrip">
                                        <img src="{{url('storage/images/parts/').'/'.$part->img}}"
                                             style="max-width:300px; max-height: 130px"
                                             id="output_image"/>
                                    </a>
                                    <div class="frame"></div>
                                </div>

                            @else
                                <div class="image-container small-wg handing ani image-format-hd" style="width: 300px;">
                                    <a href="http://localhost/cars/public/default-images/defualt.png"
                                       data-lightbox="roadtrip">
                                        <img style="max-width:300px; max-height: 130px" id="output_image"/>
                                    </a>
                                    <div class="frame"></div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="row">

                        @if(isset($part))
                            @include('admin.parts.suppliers.form', ['sups' => $suppliers])
                        @else
                            @include('admin.parts.suppliers.supplier')
                        @endif
                    </div>

                    <div class="form-group col-sm-12">

                        <button type="button" class="btn hvr-rectangle-in saveAdd-wg-btn close_form"
                                onclick="{{isset($part) ? 'updatePart()' : 'storePart()'}}">
                            <i class="ico ico-left fa fa-save"></i>
                            {{__('Save')}}
                        </button>

                        <button id="reset" type="button" class="btn hvr-rectangle-in resetAdd-wg-btn close_form">
                            <i class="ico ico-left fa fa-trash"></i>
                            {{__('Reset')}}
                        </button>

                        <button id="back" type="button" class="btn hvr-rectangle-in closeAdd-wg-btn">
                            <i class="ico ico-left fa fa-close"></i>
                            {{__('Back')}}
                        </button>

                    </div>
                </form>
            </div>
        </div>

        <div class="tab-pane fade unit_tab" role="tabpanel" id="profile-justified"
             aria-labelledby="profile-tab-justified">

            <ul class="list-inline pull-left top-margin-wg">
                <li class="list-inline-item">
                    <a style=" margin-bottom: 12px; border-radius: 5px" type="button" data-toggle="modal"
                       data-target="#part_new_unit" onclick="createUnit()"
                       class="btn btn-icon btn-icon-left btn-create-wg waves-effect waves-light hvr-bounce-to-left">
                        {{__('Create')}} {{__('New')}} {{__('Unit')}}
                        <i class="ico fa fa-plus"></i>
                    </a>
                </li>
            </ul>
            <div class="clearfix"></div>

            <div class="table-responsive" id="part_prices_table">
                @include('admin.parts.units.index', ['prices'=> isset($part) ? $part->prices : null])
            </div>

        </div>
    </div>
    <!-- /.tab-content -->
</div>


