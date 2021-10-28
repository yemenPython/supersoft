<?php


namespace App\Services\Relaying;


class RelayingSaleSupplyOrder
{

    public function toSalesInvoice ($item) {

        $reasonsPreventRelaying = [];

        if ($item->salesInvoices->count()) {
            $reasonsPreventRelaying[] = __('item already has sales invoice');
        }

        if ($item->status != 'finished') {
            $reasonsPreventRelaying[] = __('item status not finished');
        }

        return json_encode($reasonsPreventRelaying);
    }

}
