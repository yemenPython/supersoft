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
        window.location.href = "{{route('admin:lockers_opening_balance.create')}}" + "?branch_id=" + branch_id ;
    }

    $('#selectLocker').on('change', function () {
        if (checkBranchValidation()) {
            swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
            return false;
        }

        if (checkIfAssetExists( $(this).val())) {
            swal({text: '{{__('sorry, you have already add this asset before')}}', icon: "warning"});
            return false;
        }
        let branch_id = $('#branch_id').find(":selected").val();
        let indexItem = indexTable("#items_count", "increment");
        $.ajax({
            url: "{{ route('admin:lockers_opening_balance.getLockers') }}?locker_id=" + $(this).val(),
            method: 'get',
            data : {
                asset_id: $(this).val(),
                branch_id: branch_id,
                _token: '{{csrf_token()}}',
                index: indexItem,
            },
            success: function (data) {
                $('#items_data').append(data.items);
                $('.js-example-basic-single').select2();
                calculateCurrentBalanceTotal();
                calculateAddedBalanceTotal();
                calculateBalanceTotal();
            },
            error: function (jqXhr, json, errorThrown) {
                var errors = jqXhr.responseJSON;
                swal({text: errors, icon: "error"})
            }
        });
    });

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
                calculateTotalBeforeReplacement();
                calculateTotalAfterReplacement();
            }
        });
    }

    function checkIfAssetExists(index) {
        var ids = [];
        $('.assetExist').each(function () {
            ids.push($(this).val())
        })
        return ids.includes(index);
    }


    function updateBalance(index) {
        let currentBalance = $('#current_balance_item'+index).val();
        let addedBalance = $('#added_balance'+index).val();
        let total = parseFloat(currentBalance) + parseFloat(addedBalance);
        if (isNaN(total)) {
            total = 0;
           $('#added_balance'+index).val(0);
        }
        $('#total_item_balance'+index).val(total);
        calculateCurrentBalanceTotal();
        calculateAddedBalanceTotal();
        calculateBalanceTotal();
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

    function calculateCurrentBalanceTotal() {
        var currentBalanceTotal = 0;
        $(".current_balance").each(function(index, item){
            var v =  parseInt($(this).val());
            if (isNaN( parseInt($(this).val()))) { v  = 0 }
            currentBalanceTotal = (parseInt(currentBalanceTotal) + v);
        })
        $('#total_current_balance_items').val(currentBalanceTotal.toFixed(2));
        $('#total_current_balance_items_hidden').val(currentBalanceTotal.toFixed(2));
    }

    function calculateAddedBalanceTotal() {
        var addedBalanceTotal = 0;
        $(".added_balance").each(function(index, item){
            var v =  parseInt($(this).val());
            if (isNaN( parseInt($(this).val()))) { v  = 0 }
            addedBalanceTotal = (parseInt(addedBalanceTotal) + v);
        })
        $('#total_added_balance_items').val(addedBalanceTotal.toFixed(2));
        $('#total_added_balance_items_hidden').val(addedBalanceTotal.toFixed(2));
    }

    function calculateBalanceTotal() {
        var allBalanceTotal = 0;
        $(".total_balance").each(function(index, item){
            var v =  parseInt($(this).val());
            if (isNaN( parseInt($(this).val()))) { v  = 0 }
            allBalanceTotal = (parseInt(allBalanceTotal) + v);
        })
        $('#total_balance_items').val(allBalanceTotal.toFixed(2));
        $('#total_balance_items_items_hidden').val(allBalanceTotal.toFixed(2));
    }
</script>
