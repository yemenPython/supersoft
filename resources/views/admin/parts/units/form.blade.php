<form method="post" class="form" id="unit_form_data">
    @csrf
    @method('post')

    <div class="form_new_unit">

        {{-- unit one (default)  --}}

        <div id="part_unit_div">
            <div class="col-xs-12">
                <div class="box-content card bordered-all js__card top-search">

                    <h4 class="box-title with-control remove-before">
                        <i class="fa fa-clone"></i>
                        <span  id="default_unit_title">{{__('New Unit')}}</span>
                        <span class="controls">
                            <button type="button" class="control fa fa-plus " onclick="hideBody()"></button>
                        </span>
                        <!-- /.controls -->
                    </h4>
                    <!-- /.box-title -->

                    <div class="card-content " id="unit_form_body" style="background-color: white">

                        <input type="hidden" name="unit_part_id" id="unit_part_id" value="{{isset($part) ? $part->id : 65 }}">
                        <input type="hidden" name="units_count" id="units_count" value="{{isset($part) && $part->prices ? $part->prices->count() : 0 }}">

                        <input type="hidden" id="default_selling_price"  value="">
                        <input type="hidden" id="default_purchase_price" value="">
                        <input type="hidden" id="default_less_selling_price" value="">
                        <input type="hidden" id="default_service_selling_price" value="">
                        <input type="hidden" id="default_less_service_selling_price" value="">

                        {{-- ajax unit form  --}}
                        <div class="list-inline margin-bottom-0 row" id="part_ajax_unit_form">
{{--                            @include('admin.parts.units.ajax_form_new_unit')--}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
