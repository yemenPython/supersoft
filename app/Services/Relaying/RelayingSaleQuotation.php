<?php


namespace App\Services\Relaying;


class RelayingSaleQuotation
{

    public function toSaleSupplyOrder ($item) {

        $reasonsPreventRelaying = [];

        if ($item->status != 'finished' ) {
            $reasonsPreventRelaying[] = __('item status not finished');
        }

        return json_encode($reasonsPreventRelaying);
    }

    public function toSalesInvoice ($item) {

        $reasonsPreventRelaying = [];

        if ($item->salesInvoices->count()) {
            $reasonsPreventRelaying[] = __('item already has sales invoice');
        }

        if ($item->status != 'finished' ) {
            $reasonsPreventRelaying[] = __('item status not finished');
        }

        return json_encode($reasonsPreventRelaying);
    }

}
