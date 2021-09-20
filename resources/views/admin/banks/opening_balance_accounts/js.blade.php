<script type="application/javascript">
    function checkBranchValidation() {
        let branch_id = $('#branch_id').find(":selected").val();
        let isSuperAdmin = '{{authIsSuperAdmin()}}';
        if (isSuperAdmin && !branch_id) {
            return true;
        }
        if (!isSuperAdmin && !branch_id) {
            return false;
        }
    }

    function changeBranch () {
        let branch_id = $('#branch_id').find(":selected").val();
        window.location.href = "{{route('admin:banks.opening_balance_accounts.create')}}" + "?branch_id=" + branch_id ;
    }

    $("#main_type_bank_account_id").change(function () {
        if (checkBranchValidation()) {
            alertWithMsg('{{__('sorry, please select branch first')}}');
            return false;
        }
        let branch_id = $('#branch_id').find(":selected").val();
        if (!branch_id) {
            branch_id = $('#branch_id').val();
        }
        let main_bank_account_type = $(this).find(':selected').attr('data-mainType')
        let main_bank_account_type_id = $(this).val();
        if (!main_bank_account_type) {
            $("#current_account_type_section").hide();
            $.ajax({
                url: "{{ route('admin:banks.opening_balance_accounts.getBanksAccount') }}?branch_id=" + branch_id,
                method: 'GET',
                data: {
                    main_bank_account_type_id: main_bank_account_type_id,
                },
                success: function (response) {
                    $("#bankAccountsData").html(response.data)
                }
            });
        }
        if (main_bank_account_type == 'حسابات جارية') {
            $("#current_account_type_section").show();
        } else {
            $("#current_account_type_section").hide();
            $.ajax({
                url: "{{ route('admin:banks.opening_balance_accounts.getBanksAccount') }}?branch_id=" + branch_id,
                method: 'GET',
                data: {
                    main_bank_account_type_id: main_bank_account_type_id,
                },
                success: function (response) {
                    $("#bankAccountsData").html(response.data)
                }
            });
        }
    });

    $("#sub_type_bank_account_id").change( function () {
        let branch_id = $('#branch_id').find(":selected").val();
        if (!branch_id) {
            branch_id = $('#branch_id').val();
        }
        let sub_bank_account_type_id = $(this).val();
        $.ajax({
            url: "{{ route('admin:banks.opening_balance_accounts.getBanksAccount') }}?branch_id=" + branch_id,
            method: 'GET',
            data: {
                sub_bank_account_type_id: sub_bank_account_type_id,
            },
            success: function (response) {
                $("#bankAccountsData").html(response.data)
            }
        });
    })

    function getBankAccountById() {
        let bank_account_id = $("#bankAccountsData").val();
       if(!bank_account_id) {
           alertWithMsg('من فضلك أختر حساب بنكى صحيـــح !');
           return;
       }
        if (checkIfAssetExists(bank_account_id)) {
            swal({text: 'لقد تم أضافة هذا الحساب البنكى من قبل !!', icon: "warning"});
            return false;
        }
        let branch_id = $('#branch_id').find(":selected").val();
        if (!branch_id) {
            branch_id = $('#branch_id').val();
        }
        let indexItem = indexTable("#items_count", "increment");
        $.ajax({
            url: "{{ route('admin:banks.opening_balance_accounts.getBankDataById') }}?bank_account_id=" + bank_account_id,
            method: 'GET',
            data : {
                branch_id: branch_id,
                index: indexItem,
            },
            success: function (response) {
                $('#items_data').append(response.items);
            },
            error: function (jqXhr, json, errorThrown) {
                var errors = jqXhr.responseJSON;
                alertWithMsg(errors);
            }
        });
    }
    function removeItem(index) {
        swal({
            title: "Delete Item",
            text: "Are you sure want to delete this item ?",
            icon: "warning",
            buttons: true,
            dangerMode: true,

        }).then((willDelete) => {
            if (willDelete) {
                indexTable("#items_count", "decrement");
                $('#item_' + index).remove();
            }
        });
    }

    function checkIfAssetExists(index) {
        var ids = [];
        $('.isItemExist').each(function (item, index) {
            ids.push($(this).val())
        })
        return ids.includes(index);
    }


    function updateBalance(index) {
        let currentBalance = $('#balance_item'+index).val();
        let addedBalance = $('#added_balance'+index).val();
        var total = parseFloat(currentBalance) + parseFloat(addedBalance);
        if (isNaN(total)) {
            total = 0;
           $('#added_balance'+index).val(0);
        }
        $('#total_item_balance'+index).val(total.toFixed(2));
    }

    function indexTable(id, type) {
        var currentIndex = $(id).val();
        if (type === 'increment') {
            var incrementIndex = parseInt(currentIndex) + 1;
        }
        if (type === 'decrement') {
            var incrementIndex = parseInt(currentIndex) - 1;
        }
        $(id).val(incrementIndex)
        return incrementIndex;
    }

    function loadDataWithModal(route, modal, target) {
        event.preventDefault();
        $.ajax({
            url: route,
            type: 'get',
            success: function (response) {
                $('#showBankData').modal('show');
                setTimeout( () => {
                    $('.box-loader').hide();
                    $('#showBankDataResponse').html(response.data);
                },1000)
            }
        });
    }

    $('#showBankData').on('hidden.bs.modal', function () {
        $('.box-loader').show();
        $('#showBankDataResponse').html('');
    })
</script>
