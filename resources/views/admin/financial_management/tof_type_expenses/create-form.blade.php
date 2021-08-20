<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel-1">{{__('Expense types')}}</h4>
</div>
<div class="modal-body">
    <div class="row">
        <form method="post" action="{{ $formRoute }}" class="form" id="treeFromSubmit"
              enctype="multipart/form-data">
            @csrf
            @method('post')
            <input type="hidden" name="parent_id" value="{{ isset($parentId) && $parentId != '' ? $parentId : '' }}"/>

            @if(authIsSuperAdmin())
                <div class="form-group has-feedback">
                    <div class="col-md-12">
                        <div class="input-group" style="margin-bottom:10px">
                            <span class="input-group-addon fa fa-file"></span>
                            <select name="branch_id" class="form-control  js-example-basic-single" id="branchInTree">
                                <option value=""> {{ __('words.select-one') }} </option>
                                @foreach(\App\Models\Branch::all() as $branch)
                                    <option value="{{$branch->id}}">{{$branch->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            @else
                <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}"/>
            @endif
            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputNameAr" class="control-label">{{__('Name in Arabic')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><li class="fa fa-file"></li></span>
                        <input type="text" name="name_ar" class="form-control" id="inputNameEn"
                               placeholder="{{__('Name in Arabic')}}"
                               value="{{old('name_ar', isset($suppliers_group)? $suppliers_group->name_ar:'')}}">
                    </div>
                    {{input_error($errors,'name_ar')}}
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="inputNameAr" class="control-label">{{__('Name in English')}}</label>
                    <div class="input-group">
                        <span class="input-group-addon"><li class="fa fa-file"></li></span>
                        <input type="text" name="name_en" class="form-control" id="inputNameEn"
                               placeholder="{{__('Name in English')}}"
                               value="{{old('name_en', isset($suppliers_group)? $suppliers_group->name_ar:'')}}">
                    </div>
                    {{input_error($errors,'name_en')}}
                </div>
            </div>

            <div class="col-md-6">
                <label for="inputPhone" class="control-label">{{__('Status')}}</label>
                <div class="switch primary" style="margin-top: 15px">
                    <input type="hidden" name="status" value="0">
                    <input type="checkbox" id="switch-1" name="status" value="1" CHECKED>
                    <label for="switch-1">{{__('Active')}}</label>
                </div>
            </div>
        </form>

    </div>
</div>
<div class="modal-footer">

   <div class="row">
       <div class="col-md-12">
           <button class="btn btn-primary waves-effect waves-light" type="submit" form="treeFromSubmit">
               {{__('save')}}
           </button>

           <button data-dismiss="modal" type="button" class="btn btn-danger waves-effect waves-light"
                   onclick="clearSelectedType()">
               <i class='fa fa-close'></i>
               {{ __('Close') }}
           </button>
       </div>
   </div>
</div>

{!! JsValidator::formRequest($validationClass, '#treeFromSubmit'); !!}
