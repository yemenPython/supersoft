<?php


namespace App\Services\Relaying;


class RelayingPurchaseQuotation
{
    public function toSupplyOrders ($item) {

        $reasonsPreventRelaying = [];

        if ($item->supplyOrders->count()) {
            $reasonsPreventRelaying[] = __('item has supply order');
        }

        if ($item->status != 'accepted') {
            $reasonsPreventRelaying[] = __('item status not finished');
        }

        return json_encode($reasonsPreventRelaying);
    }
}
