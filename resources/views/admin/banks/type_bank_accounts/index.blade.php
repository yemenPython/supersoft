@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Types Bank Accounts') }} </title>
@endsection

@section('content')
    <nav>
        <ol class="breadcrumb" style="font-size: 37px; margin-bottom: 0px !important;padding:0px">
            <li class="breadcrumb-item"><a href="{{route('admin:home')}}"> {{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="{{route('admin:banks.bank_data.index')}}"> {{__('Managing bank accounts')}}</a></li>
            <li class="breadcrumb-item active"> {{__('Types Bank Accounts')}}</li>
        </ol>
    </nav>

    <div class="col-md-12">
        <input type="hidden" id="selected-part-type-id"/>
        <br>
        <div class="col-md-12" style="margin-bottom: 20px">
            @include('admin.banks.type_bank_accounts.buttons')
        </div>

        <div class="col-xs-12 ui-sortable-handle">
            <div class="box-content card bordered-all primary">
                <h4 class="box-title bg-primary"><i class="ico fa fa-money"></i>
                    {{__('Types Bank Accounts')}}
                </h4>
                <div class="card-content">
                    {!! $tree !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="treeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel33">
        <div class="modal-dialog" role="document">
            <div class="modal-content wg-content" id="form-modal">

            </div>
        </div>
    </div>
@endsection

@section('accounting-scripts')
    <script type="application/javascript" src="{{ asset('js/sweet-alert.js') }}"></script>

    <script type="application/javascript">
        function alert(message) {
            swal({
                title:"{{ __('accounting-module.warning') }}",
                text:message,
                icon:"warning",
                buttons:{
                    cancel: {
                        text: "{{ __('words.ok') }}",
                        className: "btn btn-primary",
                        value: null,
                        visible: true
                    }
                }
            })
        }
    </script>
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
                alert('{{ __('Please Select Item To Edit') }}')
            } else {
                switch(action_for) {
                    case "create_main_group":
                        return creatMainPartType(null)
                    case "create":
                        return createPartType($("#selected-part-type-id").val())
                    case "edit":
                        return editPartType($("#selected-part-type-id").val())
                    case "delete":
                        return deletePartType($("#selected-part-type-id").val())
                }
            }
        }

        function creatMainPartType(id) {
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: `{{ route("admin:banks.type_bank_accounts.create" ,['action_for' => 'create' ]) }}${id ? '?parent_id='+id : ''}`,
                success: function (response) {
                    $("#form-modal").html(response.html_code)
                    $('#treeModal').modal('show');
                    $('#branchInTree').select2({
                        dropdownParent: $('#treeModal')
                    });
                },
                error: function (err) {
                    const msg = err.responseJSON.message
                    alert(msg ? msg : "server error")
                }
            })
        }

        function createPartType(id) {
            if (!id) {
                swal({text: '{{__('please select Main Item First')}}', icon: "error"})
                return false;
            }
            $.ajax({
                dataType: 'json',
                type: 'GET',
                url: `{{ route("admin:banks.type_bank_accounts.create" ,['action_for' => 'create' ]) }}${id ? '&parent_id='+id : ''}`,
                success: function (response) {
                    $("#form-modal").html(response.html_code)
                    $('#treeModal').modal('show');
                    $('#branchInTree').select2({
                        dropdownParent: $('#treeModal')
                    });
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
                url: `{{ route("admin:banks.type_bank_accounts.create" ,['action_for' => 'edit' ]) }}${id ? '&id='+id : ''}`,
                success: function (response) {
                    $("#form-modal").html(response.html_code)
                    $('#treeModal').modal('show');
                    $('#branchInTree').select2({
                        dropdownParent: $('#treeModal')
                    });
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
                text:"{{ __('Please select the item from tree') }}",
                icon:"warning",
                reverseButtons: false,
                buttons: {
                    confirm: {text: "{{ __('words.yes_delete') }}", className: "btn btn-danger", value: true, visible: true},
                    cancel: {text: "{{ __('words.no') }}", className: "btn btn-default", value: null, visible: true}
                }
            }).then(function(confirm_delete){
                if (confirm_delete) {
                    window.location = "{{ url('admin/banks/type_bank_accounts/delete') }}"+"/"+id
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
                targetElement.removeClass('fa-plus-square-o')
                targetElement.addClass('fa-minus-square-o')
                targetElement.siblings('span.folder-span').addClass('fa-folder-open')
                targetElement.siblings('span.folder-span').removeClass('fa-folder')
                ul_tree.show()
            } else {
                targetElement.removeClass('fa-minus-square-o')
                targetElement.addClass('fa-plus-square-o')
                targetElement.siblings('span.folder-span').removeClass('fa-folder-open')
                targetElement.siblings('span.folder-span').addClass('fa-folder')
                ul_tree.hide()
            }
        }

        $(document).on('change' ,'#cost-centers-form .form-control' ,function () {
            $(this).parent().removeClass('has-error');
            $(this).parent().find('span.help-block').remove()
        });
    </script>
@endsection
