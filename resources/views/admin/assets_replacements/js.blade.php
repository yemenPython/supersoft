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
        window.location.href = "{{route('admin:assets_replacements.create')}}" + "?branch_id=" + branch_id ;
    }

    $('#assetsGroups').on('change', function () {
        if (checkBranchValidation()) {
            swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
            return false;
        }
        $.ajax({
            url: "{{ route('admin:assets_expenses.getAssetsByAssetGroup') }}?asset_group_id=" + $(this).val(),
            method: 'GET',
            success: function (data) {
                $('#assetsOptions').html(data.assets);
            }
        });
    });

    $('#assetsOptions').on('change', function () {
        if (checkBranchValidation()) {
            swal({text: '{{__('sorry, please select branch first')}}', icon: "error"});
            return false;
        }

        if (checkIfAssetExists( $(this).val())) {
            swal({text: '{{__('sorry, you have already add this asset before')}}', icon: "warning"});
            return false;
        }
        let branch_id = $('#branch_id').find(":selected").val();
        $.ajax({
            url: "{{ route('admin:assets_replacements.getItemsByAssetId') }}?asset_id=" + $(this).val(),
            method: 'get',
            data : {
                asset_id: $(this).val(),
                branch_id: branch_id,
                _token: '{{csrf_token()}}',
            },
            success: function (data) {
                $('#items_data').append(data.items);
                $('.js-example-basic-single').select2();
                calculateTotalBeforeReplacement();
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

    function calculateTotalBeforeReplacement() {
        var total = 0;
        $(".replacement_before").each(function(index, item){
            var v =  parseInt($(this).val());
            if (isNaN( parseInt($(this).val()))) {
                v  = 0
            }
            total = (parseInt(total) + v);
        })
        $('#total_before_replacement').val(total.toFixed(2));
        $('#total_before_replacement_hidden').val(total.toFixed(2));
    }

    function calculateTotalAfterReplacement() {
        let total = 0;
        $(".replacement_after").each(function(index, item){
            let v = parseInt($(this).val());
            if (isNaN( parseInt($(this).val()))) {
                v  = 0
            }
            total = (parseInt(total) + v);
        })
        $('#total_after_replacement').val(total.toFixed(2));
        $('#total_after_replacement_hidden').val(total.toFixed(2));
    }

    function addReplacementValue(index) {
        let valueBefore = $("#replacement_before"+index).val();
        let value = $("#value_replacement"+index).val();
        let valueAfter = $("#replacement_after"+index);
        if (false === is_numeric(value)) {
            swal({text: '{{__('Please Type Only Number')}}', icon: "warning"});
            return false;
        }
        let total = parseInt(valueBefore) + parseInt(value);
        valueAfter.val(total.toFixed(2));
        calculateTotalAfterReplacement();
        calculateAge(index);
    }

    function calculateAge(index) {
        let purchaseCost = $("#purchase_cost"+index).val();
        let valueReplacement = $("#value_replacement"+index).val();
        let valueReplacementAfter = $("#replacement_after"+index).val();
        let age = parseInt(purchaseCost) + parseInt(valueReplacement);
        let age2 = parseInt(valueReplacementAfter)/100;
        let age3 = age / age2;
        $("#age"+index).val(age3.toFixed(2));
    }
</script>
