<script type="text/javascript">

    $(document).ready(function(){

        var searchUrl = `{!! url('admin/autocomplete') !!}`;

        var selectInputLimitData = 10;

        // you can add here class or id or input name

        var arrayOfSelect2InputsToByAjax = [
            '.select2_by_ajax',
        ];

        // add select 2 plugin to every input in the array

        for(select2InputsToByAjax of arrayOfSelect2InputsToByAjax){

            $(select2InputsToByAjax).each(function(i,ele){

                let inputObject = $(this);
                let searchData = {};
                let placeholder = 'Select an item';
                let inputValue = '';

                if (inputObject.attr('data-value') !== undefined ) {
                    inputValue = inputObject.attr('data-value');
                }
                // if we need to limit returned data with specific count

                if (selectInputLimitData !== undefined) {
                    searchData.limit = selectInputLimitData;
                }
                // the name of model that we need to search in
                if (inputObject.attr('data-model') !== undefined ) {
                    searchData.model = inputObject.attr('data-model');
                }

                // the search columns that select 2 need to seach into

                if (inputObject.attr('data-searchFields') !== undefined) {
                    searchData.searchFields = inputObject.attr('data-searchFields');
                }

                // the search columns that select 2 need to seach into

                if (inputObject.attr('data-selectedColumns') !== undefined) {
                    searchData.selectedColumns = inputObject.attr('data-selectedColumns');
                }

                // the placeholder of input

                if (inputObject.attr('data-placeholder') !== undefined) {
                    placeholder = inputObject.attr('data-placeholder');
                }


                // if we need to make specific  search url for each input

                if (inputObject.attr('data-searchUrl') !== undefined) {
                    searchUrl = inputObject.attr('data-searchUrl');
                }

                runSearchSelect2(inputObject,searchUrl,searchData,inputValue,placeholder);


            });
        }


    });

    var inputInitialData = [];

    function runSearchSelect2(inputObject,searchUrl,searchData , inputValue = '' , placeholder = 'Select an item'){

        let initialData = [];

        if (inputValue != '') {

            getInitialData(searchUrl,searchData,inputValue);

            initialData = inputInitialData;

        }

        inputObject.select2({
            data : initialData,
            language: {
                searching: function() {
                    return "{!! __('Seaching')!!}";
                },
                noResults:function(){
                    return "{!! __('Sorry there is no results') !!}"
                }
            },
            placeholder: placeholder,
            ajax: {
                url: searchUrl,
                dataType: 'json',
                delay: 250,
                data: function (data) {
                    if (data.term !== undefined) {
                        searchData.searchTerm = data.term; // search terms
                    }
                    searchData.branch_id  = $("#branchId").val();
                    searchData.store_id  = $("#store_id").val();
                    searchData.part_name  = $("#part_name").val();
                    searchData.serial_number  = $("#serial_number").val();
                    searchData.asset_group_name  = $("#asset_group_name").val();
                    searchData.asset_expense_type  = $("#asset_expense_type").val();
                    searchData.type_of_purchase_quotation  = $("#type_of_purchase_quotation").val();
                    searchData.supplier_id  = $("#supplier_id").val();
                    searchData.purchase_request_id  = $("#purchase_request_id").val();
                    searchData.supply_order_type  = $("#supply_order_type").val();
                    searchData.quotation_type = $("input[name='quotation_type']:checked").val();
                    searchData.number =  $("#number").val();
                    searchData.supply_order_number =  $("#supply_order_number").val();
                    searchData.invoice_type =  $("#invoice_type").val();
                    searchData.type = $("input[name='type']:checked").val();
                    searchData.supplierID = $("#supplierID").val();
                    searchData.maintenance_detection_type_id = $("#maintenance_detection_type_id").val();
                    return searchData;
                },
                processResults: function (response) {
                    return {
                        results:response
                    };
                },
                cache: false,
            }
        });

    }




    function getInitialData(searchUrl , searchData , inputValue){
        let initialSearchFields = {
            model : searchData.model ,
            limit : searchData.limit ,
            searchFields : "id" ,
            selectedColumns: searchData.selectedColumns ,
            searchTerm : inputValue
        };


        $.ajax({
            url: searchUrl,
            dataType: 'json',
            data:initialSearchFields,
            async: false,
            success: function (response){
                window.inputInitialData = response;
            }
        });

    }


</script>
