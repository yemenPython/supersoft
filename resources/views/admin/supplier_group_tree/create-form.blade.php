<div class="remodal-content">
    <form  method="post" action="{{ $formRoute }}" class="form" id="part-type-form-content"
        enctype="multipart/form-data">
        @csrf
        @method('post')
        <input type="hidden" name="supplier_group_id" value="{{ isset($parentId) && $parentId != '' ? $parentId : '' }}"/>

        <h4 class="box-title with-control" style="text-align: initial;">
        <i class="ico fa fa-user"></i>
            {{__('Add Suppliers Groups')}}
            </h4>

        @if(authIsSuperAdmin())
            <div class="">

                <div class="form-group has-feedback">
                    <!-- <label for="inputSymbolAR" class="control-label"></label> -->
                    <div class="col-md-12">
                    <div class="input-group" style="margin-bottom:10px">
                        <span class="input-group-addon fa fa-file"></span>
                        <select name="branch_id" class="form-control  js-example-basic-single">
                            <option value=""> {{ __('words.select-one') }} </option>
                            @foreach(\App\Models\Branch::all() as $branch)
                                <option value="{{$branch->id}}">{{$branch->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    </div>
                </div>
            </div>
        @else
            <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
        @endif
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputNameAr" class="control-label">{{__('Supplier Group Name Ar')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                           placeholder="{{__('Supplier Group Name Ar')}}"
                           value="{{old('name_ar', isset($suppliers_group)? $suppliers_group->name_ar:'')}}">
                </div>
                {{input_error($errors,'name_ar')}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="inputName" class="control-label">{{__('Supplier Group Name En')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-user"></li></span>
                    <input type="text" name="name_en" class="form-control" id="inputNameEn"
                           placeholder="{{__('Supplier Group Name En')}}"
                           value="{{old('name_en', isset($suppliers_group)? $suppliers_group->name_en:'')}}">
                </div>
                {{input_error($errors,'name_en')}}
            </div>
        </div>

        <div class="col-md-3">
            <label for="inputPhone" class="control-label">{{__('Status')}}</label>
            <div class="switch primary" style="margin-top: 15px">
                <input type="hidden"  name="status" value="0">
                <input type="checkbox" id="switch-1" name="status" value="1" CHECKED>
                <label for="switch-1">{{__('Active')}}</label>
            </div>
        </div>

        <div class="col-md-3">
                <div class="form-group has-feedback">
                    <label for="inputPhone" class="control-label">{{__('Discount Type')}}</label>
                    <ul class="list-inline ul-float" style="text-align: justify">
                    <li class="radio primary">
                        <input type="radio" id="switch-2" name="discount_type" value="amount">
                        <label for="switch-2">{{__('Amount')}}</label>
                    </li>

                    <li class="radio primary">
                        <input type="radio" id="switch-3" name="discount_type" value="percent">
                        <label for="switch-3">{{__('Percent')}}</label>
                    </li>
                    </div>
                    {{input_error($errors,'discount_type')}}
                </div>
                
        </div>


        <div class="col-md-6">

            <div class="form-group">
                <label for="inputQuantity" class="control-label">{{__('Discount')}}</label>
                <div class="input-group">
                    <span class="input-group-addon"><li class="fa fa-money"></li></span>
                    <input type="text" name="discount" class="form-control"
                           placeholder="{{__('discount')}}"
                           value="{{old('discount', isset($suppliers_group)? $suppliers_group->discount : 0)}}">
                </div>
                {{input_error($errors,'discount')}}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="inputDescription" class="control-label">{{__('Description')}}</label>
                <div class="input-group">
        <textarea name="description" class="form-control" rows="4" cols="150"
        >{{old('description', isset($suppliers_group)? $suppliers_group->description :'')}}</textarea>
                </div>
                {{input_error($errors,'description')}}
            </div>
        </div>



        <div class="form-group col-sm-12">
            <button  class="btn btn-primary waves-effect waves-light" type="submit">
                <i class='fa fa-print'></i>
                {{ __('Save') }}
            </button>

            <button data-remodal-action="cancel" type="button" class="btn btn-danger waves-effect waves-light" onclick="clearSelectedType()">
                <i class='fa fa-close'></i>
                {{ __('Close') }}
            </button>
        </div>
    </form>
</div>

{!! JsValidator::formRequest($validationClass, '#part-type-form-content'); !!}
