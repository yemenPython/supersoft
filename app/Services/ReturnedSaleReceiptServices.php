<?php


namespace App\Services;


use App\Models\ReturnedSaleReceiptItem;

class ReturnedSaleReceiptServices
{

    public function saveReturnedReceiptItems($salesable, $items, $returnedReceipt)
    {
//        dd($returnedReceipt);

        $total = [
            'total' => 0,
            'total_accepted' => 0,
            'total_rejected' => 0,
        ];

        foreach ($salesable->items as $salesableItem) {

            if ($salesableItem->remaining_quantity_for_accept == 0) {
                continue;
            }

            $data = [

                'sale_receipt_id' => $returnedReceipt->id,
                'itemable_id' => $salesableItem->id,
                'part_id' => $salesableItem->part_id,
                'part_price_id' => $salesableItem->part_price_id,
                'total_quantity' => $salesableItem->quantity,
                'price' => $salesableItem->price,

                'refused_quantity' => $items[$salesableItem->id]['refused_quantity'],
                'accepted_quantity' => $items[$salesableItem->id]['accepted_quantity'],
                'store_id' => $items[$salesableItem->id]['store_id'],
                'spare_part_id' => $salesableItem->spare_part_id,
            ];

            $data['defect_percent'] = round($data['refused_quantity'] * 100 / $salesableItem->quantity, 2);

            if ($returnedReceipt->type == 'from_invoice') {
                $data['itemable_type'] = 'App\Models\SalesInvoiceItems';

            } elseif ($returnedReceipt->type == 'from_sale_quotation') {
                $data['itemable_type'] = 'App\Models\SaleQuotationItem';

            } else {
                $data['itemable_type'] = 'App\Models\SaleSupplyOrderItem';
            }


            $total['total_accepted'] += $data['price'] * $data['accepted_quantity'];
            $total['total_rejected'] += $data['price'] * $data['refused_quantity'];

            $returnedReceiptItem = ReturnedSaleReceiptItem::create($data);

            $total['total'] += $returnedReceiptItem->remaining_quantity * $data['price'];
        }

        return $total;
    }

    public function returnedReceiptData($requestData)
    {
        $data = [
            'date' => $requestData['date'],
            'time' => $requestData['time'],
            'type' => $requestData['type'],
            'salesable_id' => $requestData['salesable_id'],
            'notes' => $requestData['notes'],
        ];

        if ($data['type'] == 'from_invoice') {
            $data['salesable_type'] = 'App\Models\SalesInvoice';

        } elseif ($data['type'] == 'from_sale_quotation') {
            $data['salesable_type'] = 'App\Models\SaleQuotation';

        } else {
            $data['salesable_type'] = 'App\Models\SaleSupplyOrder';
        }

        return $data;
    }

    public function validateItemsQuantity($supplyOrder, $items)
    {

        $data = ['status' => true];

        foreach ($supplyOrder->items as $supplyOrderItem) {

            if ($supplyOrderItem->remaining_quantity_for_accept == 0) {
                continue;
            }

            $totalQuantity = $supplyOrderItem->remaining_quantity_for_accept;

            if (!isset($items[$supplyOrderItem->id])) {

                $data['status'] = false;
                $data['message'] = __('sorry, supply order item not valid');
                return $data;
            }

            $itemQuantity = $items[$supplyOrderItem->id];

            if ($itemQuantity['accepted_quantity'] > $totalQuantity) {

                $data['status'] = false;
                $data['message'] = __('sorry, accepted quantity is more than total');
                return $data;
            }

            if ($itemQuantity['refused_quantity'] > $totalQuantity) {

                $data['status'] = false;
                $data['message'] = __('sorry, refused quantity is more than total');
                return $data;
            }

            $refuseAndAcceptQuantity = $itemQuantity['refused_quantity'] + $itemQuantity['accepted_quantity'];

            if ($refuseAndAcceptQuantity > $totalQuantity) {

                $data['status'] = false;
                $data['message'] = __('sorry, refused quantity is more than total');
                return $data;
            }
        }

        return $data;
    }

    public function resetReturnedReceiptItems($returnSaleReceipt)
    {

        foreach ($returnSaleReceipt->items as $item) {

            $item->delete();
        }
    }

}
