<?php


namespace App\Services\Relaying;


class RelayingPurchaseReceipts
{

    public function toPurchaseInvoice ($item) {

        $reasonsPreventRelaying = [];

        if ($item->purchaseInvoices->count()) {
            $reasonsPreventRelaying[] = __('item already has purchase invoice');
        }

        if (!$item->concession) {
            $reasonsPreventRelaying[] = __('item not has concession');
        }

        if ($item->concession && $item->concession->status != 'accepted') {
            $reasonsPreventRelaying[] = __('item status not accepted');
        }

        return json_encode($reasonsPreventRelaying);
    }

    public function toPurchaseReturn ($item) {

        $reasonsPreventRelaying = [];

        if (!$item->concession) {
            $reasonsPreventRelaying[] = __('item not has concession');
        }

        if ($item->concession && $item->concession->status != 'accepted') {
            $reasonsPreventRelaying[] = __('item status not accepted');
        }

        return json_encode($reasonsPreventRelaying);
    }

}
