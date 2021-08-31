
function openPriceSegment(id) {

    if ($('#price_segment_checkbox_' + id).is(":checked")) {

        $("#purchase_price_segment_" + id).prop('disabled', false);
        $("#segment_" + id).prop('disabled', false);
        $("#sales_price_segment_" + id).prop('disabled', false);
        $("#maintenance_price_segment_" + id).prop('disabled', false);

    } else {

        $("#purchase_price_segment_" + id).prop('disabled', true);
        $("#segment_" + id).prop('disabled', true);
        $("#sales_price_segment_" + id).prop('disabled', true);
        $("#maintenance_price_segment_" + id).prop('disabled', true);
    }
}

function calculatePrice() {

    var unitsCount = $('#units_count').val();

    if (unitsCount == 0) {
        return false;
    }

    var default_selling_price = $("#default_selling_price").val();
    var default_purchase_price = $("#default_purchase_price").val();
    var default_less_selling_price = $("#default_less_selling_price").val();
    var default_service_selling_price = $("#default_service_selling_price").val();
    var default_less_service_selling_price = $("#default_less_service_selling_price").val();

    var qty = $("#unit_quantity").val();

    if (!qty) {
        qty = 0;
    }

    var new_selling_price = parseFloat(default_selling_price) * parseFloat(qty);
    var new_purchase_price = parseFloat(default_purchase_price) * parseFloat(qty);
    var new_less_selling_price = parseFloat(default_less_selling_price) * parseFloat(qty);
    var new_service_selling_price = parseFloat(default_service_selling_price) * parseFloat(qty);
    var new_less_service_selling_price = parseFloat(default_less_service_selling_price) * parseFloat(qty);

    $("#selling_price").val(new_selling_price);
    $("#purchase_price").val(new_purchase_price);
    $("#less_selling_price").val(new_less_selling_price);
    $("#service_selling_price").val(new_service_selling_price);
    $("#less_service_selling_price").val(new_less_service_selling_price);
}

function hideBody() {

    $("#unit_form_body").slideToggle('slow');
}

function deleteUnit(index) {

    swal({

        title: "Delete Unit",
        text: "Are you sure want to delete this unit ?",
        icon: "warning",
        buttons: true,
        dangerMode: true,

    }).then((willDelete) => {

        if (willDelete) {

            $("#part_unit_div_" + index).remove();
        }
    });
}

function getUnitName(index) {

    let selectedUnit = $("#unit_" + index + " option:selected").text();

    $("#default_unit_title_" + index).text(selectedUnit);
}

function defaultUnit() {

    let selectedUnit = $("#part_units_default" + " option:selected").text();
    $(".default_unit_title").text(selectedUnit);
}
