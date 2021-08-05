<div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <button type="button" class="btn btn-primary waves-effect waves-light" onclick="printDownPayment()"
                        id="print_sales_invoice">
                    <i class='fa fa-print'></i>
                    {{__('Print')}}
                </button>

                <button type="button" class="btn btn-danger waves-effect waves-light" data-dismiss="modal">
                    <i class='fa fa-close'></i>
                    {{__('Close')}}</button>
            </div>

            <div class="modal-body print-border" id="data_to_print" style="border:1px solid #3b3b3b;margin:0 20px;border-radius:5px">


            </div>

            <div class="modal-footer" style="text-align:center">

            </div>

        </div>
    </div>
</div>
