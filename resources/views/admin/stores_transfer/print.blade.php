<div id="concession_to_print">
    <div class="border-container" style="">
        <div class="print-header-wg">
            <div class="top-logo-print">
                <div class="logo-print text-center">
                    <ul class="list-inline" style="margin:0">
                        <li>
                            <h5>{{optional($branchToPrint)->name_ar}}</h5>
                        </li>
                        <li>
                            <img
                                src="{{isset($branchToPrint->logo) ? asset('storage/images/branches/'.$branchToPrint->logo) : env('DEFAULT_IMAGE_PRINT')}}"
                                style="width: 50px;
    height: 50px;
    border-radius: 50%;
    position: absolute;
    top: 2px;
    left: 21px;">
                        </li>
                    </ul>
                </div>
            </div>


            <div class="row row-right-data">
                <div class="col-xs-6"></div>
                <div class="col-xs-6 right-top-detail">
                    <h3>
                        <span> {{__('words.stores-transfers')}} </span>
                    </h3>

                </div>
            </div>
        </div>
        @include('admin.stores_transfer.show_form')
        <div class="print-foot-wg position-relative ml-0">
            <div class="row for-reverse-en" style="display: flex;
    align-items: flex-end;">
                <div class="col-xs-7">
                    <div class="row">
                        <div class="col-xs-12">

                            <div class="media">
                                <div class="media-left">
                                    <h6 class="media-heading" style="line-height:30px;">{{__('address')}} </h6>
                                </div>

                                <div class="media-body">
                                    <h6 style="padding:0 15px">{{optional($branchToPrint)->address_ar}} </h6>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-6">

                        </div>
                        <div class="col-xs-6">

                        </div>
                    </div>

                </div>
                <div class="col-xs-5 small-data-wg">
                    <div class="row">
                        <div class="col-xs-4">
                            <h6>{{__('contact numbers')}} : </h6>
                        </div>
                        <div class="col-xs-4">
                            <h6>{{optional($branchToPrint)->phone1}}</h6>
                        </div>

                        <div class="col-xs-4">
                            <h6>{{optional($branchToPrint)->phone2}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
