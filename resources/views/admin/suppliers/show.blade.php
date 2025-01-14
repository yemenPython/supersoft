@extends('admin.layouts.app')

@section('title')
    <title>{{ __('supplier Info') }} </title>
@endsection

@section('content')

    <div class="row small-spacing">


        <nav>
            <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
                <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin:suppliers.index')}}"> {{__('Suppliers')}}</a></li>
                <li class="breadcrumb-item active"> {{__('supplier Info')}}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xs-12">
                <div class=" card box-content-wg-new bordered-all primary">
                    <h4 class="box-title with-control" style="text-align: initial"><i
                            class="fa fa-user ico"></i>{{__('supplier Info')}}
                            <span class="controls hidden-sm hidden-xs pull-left">

							<button class="control text-white"
                                    style="background:none;border:none;font-size:14px;font-weight:normal !important;"> {{__('Back')}} <img
                                    class="img-fluid"
                                    style="width:40px;height:40px;margin-top:-15px;margin-bottom:-13px"
                                    src="{{asset('assets/images/f3.png')}}"></button>
						</span>
                    </h4>


                    <div class="box-content">
                        <div class="card-content">
                            <div class="row table-color-wg">

                                <div class="col-md-6">


                                    <table class="table table-bordered ">
                                        <tbody>
                                        <tr>
                                            <th scope="row">{{__('Supplier Name')}}</th>
                                            <td>{{$supplier->name}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Company Code')}}</th>
                                            <td>{{$supplier->company_code}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Supplier Type')}}</th>
                                            <td>{{__($supplier->type)}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Branch')}}</th>
                                            <td>{{optional($supplier->branch)->name}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Main Supplier Group')}}</th>
                                            <td>{{optional($supplier->group)->name}}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">{{__('Sub Supplier Group')}}</th>
                                            <td>{{optional($supplier->subGroup)->name}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Country')}}</th>
                                            <td>{{optional($supplier->country)->name}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('City')}}</th>
                                            <td>{{optional($supplier->city)->name}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Area')}}</th>
                                            <td>{{optional($supplier->area)->name}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('phone 1')}}</th>
                                            <td>{{$supplier->phone_1}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('phone 2')}}</th>
                                            <td>{{$supplier->phone_2}}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">{{__('Tax Number')}}</th>
                                            <td>{{$supplier->tax_number}}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">{{__('Tax File Number')}}</th>
                                            <td>{{$supplier->tax_file_number}}</td>
                                        </tr>

                                        <tr>
                                            <th scope="row">{{__('Commercial Record Area')}}</th>
                                            <td>{{$supplier->commercial_record_area}}</td>
                                        </tr>
                                        </tbody>
                                    </table>


                                </div>


                                <div class="col-md-6">

                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <th scope="row">{{__('Status')}}</th>
                                            <td>{{$supplier->active}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Address')}}</th>
                                            <td>{{$supplier->address}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Email')}}</th>
                                            <td>{{$supplier->email}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Fax')}}</th>
                                            <td>{{$supplier->fax}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Commercial Register')}}</th>
                                            <td>{{$supplier->commercial_number}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Tax Card')}}</th>
                                            <td>{{$supplier->tax_card}}</td>
                                        </tr>
                                        @php
                                            $supplier_balance = $supplier->direct_balance();
                                        @endphp
                                        <tr>
                                            <th scope="row">{{__('Funds For')}}</th>
                                            <td>{{ $supplier_balance['debit'] }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Funds On')}}</th>
                                            <td>{{ $supplier_balance['credit'] }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Description')}}</th>
                                            <td>{{$supplier->description}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Maximum Supplier Funds')}}</th>
                                            <td>{{$supplier->maximum_fund_on}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">{{__('Location')}}</th>
                                            <td>
                                                <input type="hidden" id="latVal" value="{{$supplier->lat}}">
                                                <input type="hidden" id="longVal" value="{{$supplier->long}}">
                                                <a data-toggle="modal" data-target="#boostrapModal-2" title="Cars info"
                                                       class="btn btn-primary"
                                                       style="margin-top:1px;cursor:pointer;font-size:12px;padding:3px 15px">
                                                        <i class="fa fa-eye"> </i> {{__('Location')}}
                                                    </a>
                                            </td>
                                        </tr>


                                        </tbody>
                                    </table>

                                </div>
                                <!-- /.row -->
                            </div>
                            <!-- /.card-content -->
                        </div>
                        <!-- /.box-content card -->


                        <div class="col-xs-12">
                            <div class="box-content box-content-wg card bordered-all blue-1 js__card">
                                <h4 class="box-title bg-blue-1 with-control">
                                {{__('Contacts')}}
                                    <span style="float: right;"> </span>
                                    <span class="controls">

                                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                                    <button type="button" class="control fa fa-times js__card_remove"></button>
                                </span>
                                </h4>

                                <div class="card-content js__card_content" style="">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>{{__('Name')}}</th>
                                            <th>{{__('Phone 1')}}</th>
                                            <th>{{__('Phone 2')}}</th>
                                            <th>{{__('address')}}</th>
                                            <th>{{__('Created Date')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($supplier->contacts as $contact)
                                                <tr>
                                                    <td>{{$contact->name}}</td>
                                                    <td>{{$contact->phone_1}}</td>
                                                    <td>{{$contact->phone_2}}</td>
                                                    <td>{{\Illuminate\Support\Str::limit($contact->address, 50)}}</td>
                                                    <td>{{$contact->created_at}}</td>
                                                </tr>
                                            @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="box-content box-content-wg card bordered-all blue-1 js__card">
                                <h4 class="box-title bg-blue-1 with-control">
                                    {{__('Bank Accounts')}}
                                    <span style="float: right;"> </span>
                                    <span class="controls">

                                    <button type="button" class="control fa fa-minus js__card_minus"></button>
                                    <button type="button" class="control fa fa-times js__card_remove"></button>
                                </span>
                                </h4>

                                <div class="card-content js__card_content" style="">
                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
                                            <th>{{__('Bank Name')}}</th>
                                            <th>{{__('Account Name')}}</th>
                                            <th>{{__('Branch Name')}}</th>
                                            <th>{{__('Account Number')}}</th>
                                            <th>{{__('IBAN')}}</th>
                                            <th>{{__('Swift Code')}}</th>
                                            <th>{{__('Created Date')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach($supplier->bankAccounts as $bankAccount)
                                            <tr>
                                                <td>{{$bankAccount->bank_name}}</td>
                                                <td>{{$bankAccount->account_name}}</td>
                                                <td>{{$bankAccount->branch}}</td>
                                                <td>{{$bankAccount->account_number}}</td>
                                                <td>{{$bankAccount->iban}}</td>
                                                <td>{{$bankAccount->swift_code}}</td>
                                                <td>{{$bankAccount->created_at}}</td>
                                            </tr>
                                        @endforeach

                                    </table>
                                </div>
                            </div>

                        </div>

                    </div>


                </div>
            </div>
        </div>


    </div>

@endsection

@section('accounting-scripts')
    <script type="application/javascript" defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4YXeD4XTeNieaKWam43diRHXjsGg7aVY&callback=initMap"></script>
    <script type="application/javascript">

    function select_part_type(id, ele) {
        openTree(ele)
        $('.span-selected').removeClass('span-selected')
        $(event.target).addClass('span-selected')
        $("#selected-part-type-id").val(id)
    }

    function clearSelectedType() {
        $('.span-selected').removeClass('span-selected')
        $("#selected-part-type-id").val('')
    }

    function open_modal(action_for) {
        if ($('.span-selected').length <= 0 && (action_for == 'edit' || action_for == 'delete')) {
            alert('{{ __('words.choose-supplier-group-to-edit') }}')
        } else {
            switch(action_for) {
                case "create":
                    return createPartType($("#selected-part-type-id").val())
                case "edit":
                    return editPartType($("#selected-part-type-id").val())
                case "delete":
                    return deletePartType($("#selected-part-type-id").val())
            }
        }
    }

    function createPartType(id) {
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: `{{ route("admin:supplier-group.form" ,['action_for' => 'create' ]) }}${id ? '?parent_id='+id : ''}`,
            success: function (response) {
                $("#form-modal").html(response.html_code)
                $('[data-remodal-id=part-type-modal]').remodal().open()
                // $('select[name="branch_id"]').select2()
            },
            error: function (err) {
                const msg = err.responseJSON.message
                alert(msg ? msg : "server error")
            }
        })
    }

    function editPartType(id) {
        $.ajax({
            dataType: 'json',
            type: 'GET',
            url: `{{ route("admin:supplier-group.form" ,['action_for' => 'edit' ]) }}${id ? '?id='+id : ''}`,
            success: function (response) {
                $("#form-modal").html(response.html_code)
                $('[data-remodal-id=part-type-modal]').remodal().open()
                // $('select[name="branch_id"]').select2()
            },
            error: function (err) {
                var msg = "server error"
                if (err.responseJSON.message) msg = err.responseJSON.message
                alert(msg)
            }
        })
    }

    function deletePartType(id) {
        swal({
            title:"{{ __('words.warning') }}",
            text:"{{ __('words.are-u-sure-to-delete-supplier-group') }}",
            icon:"warning",
            reverseButtons: false,
            buttons: {
                confirm: {text: "{{ __('words.yes_delete') }}", className: "btn btn-danger", value: true, visible: true},
                cancel: {text: "{{ __('words.no') }}", className: "btn btn-default", value: null, visible: true}
            }
        }).then(function(confirm_delete){
            if (confirm_delete) {
                window.location = "{{ route('admin:supplier-group.delete') }}?id="+id
            } else {
                alert("{{ __('words.part-type-not-deleted') }}")
            }
        })
    }

    function openTree(element) {
        let targetElement = $('.' + element)
        let ul_tree_id = targetElement.data('current-ul')
        let ul_tree = $("#" + ul_tree_id)
        if (ul_tree.css('display') === 'none') {
            targetElement.removeClass('fa-plus')
            targetElement.addClass('fa-minus')
            targetElement.siblings('span.folder-span').addClass('fa-folder-open')
            targetElement.siblings('span.folder-span').removeClass('fa-folder')
            ul_tree.show()
        } else {
            targetElement.removeClass('fa-minus')
            targetElement.addClass('fa-plus')
            targetElement.siblings('span.folder-span').removeClass('fa-folder-open')
            targetElement.siblings('span.folder-span').addClass('fa-folder')
            ul_tree.hide()
        }
    }

    $(document).on('change' ,'#cost-centers-form .form-control' ,function () {
        $(this).parent().removeClass('has-error');
        $(this).parent().find('span.help-block').remove()
    });

    var map;
    var marker = false;
    function initMap() {
        let longVal = parseFloat($('#longVal').val());
        let latVal = parseFloat($('#latVal').val());
        const myLatLng = { lat: latVal, lng: longVal };
        var centerOfMap = new google.maps.LatLng(latVal, longVal);
        var options = {
            center: centerOfMap,
            zoom: 7
        };
        map = new google.maps.Map(document.getElementById('map'), options);
        new google.maps.Marker({
            position: myLatLng,
            map:map
        });
    }
    //Load the map when the page has finished loading.
    google.maps.event.addDomListener(window, 'load', initMap);
</script>
@endsection
@section('modals')

    <div class="modal fade" id="boostrapModal-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Supplier Locations')}}</h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body" id="map" style="height: 400px;" >

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

@endsection
