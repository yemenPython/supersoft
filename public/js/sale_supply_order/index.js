function quotationType() {

    let quotation_type = $('#quotation_type').find(":selected").val();



    if (quotation_type == 'from_sale_quotation') {

        $(".out_sale_quotations_type").hide();
        $(".sale_quotation_type").show();
        $(".remove_on_change_branch").remove();
        $("#items_count").val(0);

    } else {

        $(".out_sale_quotations_type").show();
        $(".sale_quotation_type").hide();
        $(".remove_on_change_branch").remove();
        $("#items_count").val(0);
    }
}

// function changeTypeFor() {
//
//     if ($('#supplier_radio').is(':checked')) {
//
//         $("#suppliers_data").show();
//         $("#supplier_id").prop('disabled', false);
//
//         $("#customers_data").hide();
//         $("#customer_id").prop('disabled', true);
//
//     } else {
//
//         $("#customers_data").show();
//         $("#customer_id").prop('disabled', false);
//
//         $("#suppliers_data").hide();
//         $("#supplier_id").prop('disabled', true);
//
//     }
// }

function changeTypeFor() {

    if ($('#supplier_radio').is(':checked')) {

        $("#suppliers_data").show();
        $("#supplier_id").prop('disabled', false);

        $("#customers_data").hide();
        $("#customer_id").prop('disabled', true);

        $("#supplier_id").addClass('client_select');
        $("#customer_id").removeClass('client_select');

        $("#customer_id").val('').change();

    } else {

        $("#customers_data").show();
        $("#customer_id").prop('disabled', false);

        $("#suppliers_data").hide();
        $("#supplier_id").prop('disabled', true);

        $("#customer_id").addClass('client_select');
        $("#supplier_id").removeClass('client_select');

        $("#supplier_id").val('').change();
    }

    $('.supplier_discount').val(0);

    calculateTotal();
}


function getSellPrice(index) {

    let sale_price = $('#prices_part_' + index).find(":selected").data('sale-price');
    $('#price_' + index).val(sale_price);
}

function calculateItem(index) {

    let quantity = $("#quantity_" + index).val();
    let price = $("#price_" + index).val();
    let discount = $("#discount_" + index).val();
    let total = parseFloat(quantity) * parseFloat(price);

    $('#total_before_discount_' + index).val(total.toFixed(2));

    calculateItemDiscount(discount, total, index);

    calculateItemTaxes(index);

    calculateTotal();
}

function calculateItemDiscount(discount, total, id) {

    if (discount == "") {
        discount = 0;
    }

    if ($("#discount_type_amount_" + id).is(':checked')) {

        let big_amount_discount = $('#prices_part_' + id).find(":selected").data('big-amount-discount');

        if (parseFloat(discount) > parseFloat(big_amount_discount)) {

            swal({
                text: " sorry big amount discount is " + big_amount_discount,
                icon: "warning",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $("#discount_" + id).val(0);
                }
            });
            discount = 0;
        }

        var price_after_discount = parseFloat(total) - parseFloat(discount);

    } else {

        let big_percent_discount = $('#prices_part_' + id).find(":selected").data('big-percent-discount');

        if (parseFloat(discount) > parseFloat(big_percent_discount)) {

            swal({
                text: " sorry big percent discount is " + big_percent_discount,
                icon: "warning",
            }).then(function (isConfirm) {
                if (isConfirm) {
                    $("#discount_" + id).val(0);
                }
            });
            discount = 0;
        }

        var discount_percentage_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);

        var price_after_discount = parseFloat(total) - parseFloat(discount_percentage_value);
    }

    if (price_after_discount <= 0)
        price_after_discount = 0;

    $("#total_after_discount_" + id).val(price_after_discount.toFixed(2));
}

function calculateItemTaxes(index) {

    let taxes_count = $('#tax_count_' + index).val();

    let total_taxes = 0;
    let total_after_discount = $('#total_after_discount_' + index).val();

    for (let i = 1; i <= taxes_count; i++) {

        if ($('#checkbox_tax_' + i + '_' + index).is(':checked')) {

            let tax_type = $('#checkbox_tax_' + i + '_' + index).data('tax-type');
            let tax_value = $('#checkbox_tax_' + i + '_' + index).data('tax-value');
            let tax_execution_time = $('#checkbox_tax_' + i + '_' + index).data('tax-execution-time');

            if (tax_execution_time == 'after_discount') {

                var totalUsedToCalculate = $('#total_after_discount_' + index).val();

            } else {

                var totalUsedToCalculate = $('#total_before_discount_' + index).val();
            }

            if (tax_type == 'amount') {

                total_taxes += parseFloat(tax_value);
                $("#calculated_tax_value_" + i + '_' + index).text(tax_value.toFixed(2));

            } else {

                let tax_percent_value = parseFloat(totalUsedToCalculate) * parseFloat(tax_value) / 100;
                total_taxes += parseFloat(tax_percent_value);

                $("#calculated_tax_value_" + i + '_' + index).text(tax_percent_value.toFixed(2));
            }
        }
    }

    var total = parseFloat(total_after_discount) + parseFloat(total_taxes);

    $("#total_" + index).val(total.toFixed(2));
    $("#tax_" + index).val(total_taxes.toFixed(2));
}

function calculateTotal() {

    let items_count = $('#items_count').val();

    let total = 0;

    for (let i = 1; i <= items_count; i++) {

        if ($('#price_' + i).length) {
            total += parseFloat($('#total_' + i).val());
        }
    }

    $('#sub_total').val(total.toFixed(2));

    calculateInvoiceDiscount();
}

function calculateInvoiceDiscount() {

    let discount = $("#discount").val();
    let total = $("#sub_total").val();
    let supplier_discount = 0;
    let client_id = $('.client_select').find(":selected").val();

    if (discount == "") {
        discount = 0;
    }

    if ($('#discount_type_amount').is(':checked')) {

        var price_after_discount = parseFloat(total) - parseFloat(discount);

    } else {

        let discount_percent_value = parseFloat(total) * parseFloat(discount) / parseFloat(100);
        var price_after_discount = parseFloat(total) - parseFloat(discount_percent_value);
    }

    if ($('#supplier_discount_check').is(':checked') && client_id != '') {
        supplier_discount = clientDiscount();
    }

    price_after_discount -= parseFloat(supplier_discount);

    if (price_after_discount <= 0)
        price_after_discount = 0;

    $("#total_after_discount").val(price_after_discount.toFixed(2));

    calculateTax();
}

function calculateTax() {

    var total_after_discount = $("#total_after_discount").val();
    var tax_count = $("#invoice_tax_count").val();
    var total_tax = 0;
    var sub_total = $("#sub_total").val();

    if (sub_total == 0) {
        $("#tax").val(0);
        $("#total_after_discount").val(0);
        $("#total").val(0);
        return false;
    }

    for (var i = 1; i <= tax_count; i++) {

        if ($('#checkbox_tax_' + i).is(':checked')) {

            var type = $('#checkbox_tax_' + i).data('tax-type');
            var value = $('#checkbox_tax_' + i).data('tax-value');

            let tax_execution_time = $('#checkbox_tax_' + i).data('tax-execution-time');

            if (tax_execution_time == 'after_discount') {

                var totalUsedToCalculate = $("#total_after_discount").val();

            } else {

                var totalUsedToCalculate = $("#sub_total").val();
            }

            if (type == 'amount') {

                total_tax += parseFloat(value);
                $("#calculated_tax_value_" + i).text(value.toFixed(2));
            } else {

                var tax_value = parseFloat(totalUsedToCalculate) * parseFloat(value) / 100;
                total_tax += parseFloat(tax_value);

                $("#calculated_tax_value_" + i).text(tax_value.toFixed(2));
            }
        }
    }

    var total = parseFloat(total_after_discount) + parseFloat(total_tax);

    var additional_payments = calculateAdditionalPayments();

    total += parseFloat(additional_payments);

    $("#total").val(total.toFixed(2));

    $("#tax").val(total_tax.toFixed(2));
}

function calculateAdditionalPayments() {

    var total_after_discount = $("#total_after_discount").val();
    var additional_count = $("#invoice_additional_count").val();
    var total_additional = 0;
    var sub_total = $("#sub_total").val();

    if (sub_total == 0) {
        $("#additional_payments").val(0);
        $("#total_after_discount").val(0);
        $("#total").val(0);
        return false;
    }

    for (var i = 1; i <= additional_count; i++) {

        if ($('#checkbox_additional_' + i).is(':checked')) {

            var type = $('#checkbox_additional_' + i).data('additional-type');
            var value = $('#checkbox_additional_' + i).data('additional-value');

            let additional_execution_time = $('#checkbox_additional_' + i).data('additional-execution-time');

            if (additional_execution_time == 'after_discount') {

                var totalUsedToCalculate = $("#total_after_discount").val();

            } else {

                var totalUsedToCalculate = $("#sub_total").val();
            }


            if (type == 'amount') {

                total_additional += parseFloat(value);
                $("#calculated_additional_value_" + i).text(value.toFixed(2));

            } else {

                var tax_value = parseFloat(totalUsedToCalculate) * parseFloat(value) / 100;
                total_additional += parseFloat(tax_value);

                $("#calculated_additional_value_" + i).text(tax_value.toFixed(2));
            }
        }
    }

    $("#additional_payments").val(total_additional.toFixed(2));

    let additional_total = total_additional.toFixed(2);

    return additional_total;
}

function getPurchasePriceFromSegments(index) {

    let price_segment = $('#price_segments_part_' + index).find(":selected").val();

    if (price_segment.length == 0) {

        getSellPrice(index);
        return true;
    }

    let sell_price = $('#price_segments_part_' + index).find(":selected").data('sell-price');

    $('#price_' + index).val(sell_price);
}

function executeAllItems() {

    let items_count = $('#items_count').val();

    for (let i = 1; i <= items_count; i++) {

        if ($('#price_' + i).length) {
            calculateItem(i);
        }
    }

    calculateTotal();
}

function selectSaleQuotation(index) {

    if ($('.sale_quotation_box_' + index).is(':checked')) {

        $('.real_sale_quotation_box_' + index).prop('checked', true);

    } else {

        $('.real_sale_quotation_box_' + index).prop('checked', false);
    }
}

function selectClient () {

    let supplier_id = $('.client_select').find(":selected").val();
    let discount = $('.client_select').find(":selected").data('discount');
    let discount_type = $('.client_select').find(":selected").data('discount-type');

    if (discount_type == 'amount') {

        $('.supplier_discount_type').val('$');

    }else {
        $('.supplier_discount_type').val('%');
    }

    $('.supplier_discount_type_value').val(discount_type);
    $('.supplier_discount').val(discount);

    calculateTotal();
}

function clientDiscount () {

    let total = $('#sub_total').val();

    let discount = $('.client_select').find(":selected").data('discount');
    let discount_type = $('.client_select').find(":selected").data('discount-type');

    // console.log(discount);

    if (discount == "") {
        discount = 0;
    }

    if (total == 0) {
        return 0;
    }

    if (discount_type == 'amount') {

        return discount

    }else {

        return  parseFloat(total) * parseFloat(discount) / parseFloat(100);
    }
}

function quantityValidation (index, message) {

    let quantity = $('#quantity_' + index).val();

    if (quantity <= 0) {

        swal({text: message, icon: "warning"});

        $('#quantity_' + index).val(1);

        calculateItem(index);
    }
}

function priceValidation (index, message) {

    let price = $('#price_' + index).val();

    if (price < 0) {

        swal({text: message, icon: "warning"});

        $('#price_' + index).val(0);

        calculateItem(index);
    }
}

function discountValidation (index, message) {

    let discount = $('#discount_' + index).val();

    if (discount < 0) {

        swal({text: message, icon: "warning"});

        $('#discount_' + index).val(0);

        calculateItem(index);
    }
}


