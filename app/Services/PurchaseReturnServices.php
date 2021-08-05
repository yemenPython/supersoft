<?php


namespace App\Services;

use App\Model\PurchaseReturnItem;
use App\Models\Part;
use App\Models\PurchaseInvoice;
use App\Models\Supplier;
use App\Models\SupplyOrder;
use App\Models\TaxesFees;

class PurchaseReturnServices
{
    public function ItemData($item, $invoice_type)
    {
        $returnedItem = $this->getReturnedModel($invoice_type)::find($item['item_id']);

        $part = Part::find($item['part_id']);

        $part_store = $part->stores()->where('store_id', $returnedItem->store_id)->first();

        $availableQuantity = $part_store && $part_store->pivot ? $part_store->pivot->quantity : 0;

        $data = [
            'part_id' => $item['part_id'],
            'spare_part_id' => $item['spare_part_id'],
            'store_id' => $returnedItem->store_id,
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'discount' => $item['discount'],
            'discount_type' => $item['discount_type'],
            'active' => isset($item['active']) ? 1 : 0,
            'sub_total' => $item['quantity'] * $item['price'],
            'part_price_id'=> $returnedItem->part_price_id,
            'part_price_segment_id' => $returnedItem->part_price_segment_id,
            'item_id'=> $item['item_id'],
            'max_quantity' => $invoice_type == 'normal' ? $returnedItem->quantity : $returnedItem->accepted_quantity,
            'available_qty' => $availableQuantity,
        ];


        $discountValue = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discountValue;

        $itemTaxes = isset($item['taxes']) ? $item['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data['sub_total'], $itemTaxes);

        $data['total'] = $data['total_after_discount'] + $data['tax'];

        return $data;
    }

    public function PurchaseReturnData($requestData)
    {
        $data = [
            'invoice_number' => $requestData['invoice_number'],
            'type' => $requestData['type'],
            'date' => $requestData['date'],
            'time' => $requestData['time'],
            'status' => $requestData['status'],
            'invoice_type' => $requestData['invoice_type'],
            'discount' => $requestData['discount'],
            'discount_type' => $requestData['discount_type'],
            'supplier_discount_status' => 0,
            'purchase_invoice_id'=> $requestData['invoice_type'] == 'normal' ? $requestData['purchase_invoice_id'] : null,
        ];

        $data['sub_total'] = 0;
        $supplier_discount = 0;

        $supplier = $this->getSupplier($requestData);

        $data['supplier_id'] = $supplier ? $supplier->id : null;

        if (isset($requestData['items'])) {

            foreach ($requestData['items'] as $item) {

                if (isset($item['active'])) {

                    $itemData = $this->ItemData($item, $data['invoice_type']);
                    $data['sub_total'] += $itemData['total'];
                }
            }
        }

        if (isset($requestData['supplier_discount_active']) && $supplier) {

            $data['supplier_discount_status'] = 1;
            $data['supplier_discount'] = $supplier->group_discount;
            $data['supplier_discount_type'] = $supplier->group_discount_type;

            $supplier_discount = $this->supplierDiscount($supplier, $data['sub_total']);
        }

        $discountValue = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $totalDiscount = $discountValue + $supplier_discount;

        $data['total_after_discount'] = $data['sub_total'] - $totalDiscount;

        $itemTaxes = isset($requestData['taxes']) ? $requestData['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data['sub_total'], $itemTaxes);

        $additionalPayments = isset($requestData['additional_payments']) ? $requestData['additional_payments'] : [];

        $data['additional_payments'] = $this->taxesValue($data['total_after_discount'], $data['sub_total'], $additionalPayments);

        $data['total'] = $data['total_after_discount'] + $data['tax'] + $data['additional_payments'];

        return $data;
    }

    function discountValue($type, $value, $total)
    {
        if ($type == 'amount') {

            $discount = $value;

        } else {

            $discount = $total * $value / 100;
        }

        return $discount;
    }

    function taxesValue($totalAfterDiscount, $subTotal, $itemTaxes)
    {
        $value = 0;

        foreach ($itemTaxes as $tax_id) {

            $tax = TaxesFees::find($tax_id);

            if ($tax) {

                if ($tax->execution_time == 'after_discount') {

                    $totalUsedInCalculate = $totalAfterDiscount;
                } else {

                    $totalUsedInCalculate = $subTotal;
                }

                if ($tax->tax_type == 'amount') {

                    $value += $tax->value;

                } else {

                    $value += $totalUsedInCalculate * $tax->value / 100;
                }
            }
        }
        return $value;
    }

    function resetPurchaseReturnItems($purchaseReturn)
    {
        foreach ($purchaseReturn->items as $item) {
            $item->taxes()->detach();
            $item->delete();
        }

        $purchaseReturn->taxes()->detach();
        $purchaseReturn->supplyOrders()->detach();
        $purchaseReturn->purchaseReceipts()->detach();
    }

    public function checkItemsQuantity($requestedData)
    {
        $invoiceType = $requestedData['invoice_type'];

        $itemModel = $this->getReturnedModel($invoiceType);

        $data = ['status' => true];

        if (!count($requestedData['items'])) {

            $data['status'] = false;
            $data['message'] = __('sorry, no items send please check data');

            return $data;
        }

        foreach ($requestedData['items'] as $item) {

            $returnedItem = $itemModel::find($item['item_id']);

            if (!$returnedItem) {

                $data['status'] = false;
                $data['message'] = __('sorry, the returned item not valid');

                return $data;
            }

            $maxQuantity = $invoiceType == 'normal' ? $returnedItem->quantity : $returnedItem->accepted_quantity;

            if ($item['quantity'] > $maxQuantity) {

                $data['status'] = false;
                $data['message'] = __('sorry, the requested quantity, more than max quantity to return');

                return $data;
            }
        }

        return $data;
    }

    public function getReturnedModel ($type) {

        return $type == 'normal' ? 'App\\Models\\PurchaseInvoiceItem' : 'App\\Models\\PurchaseReceiptItem';
    }

    public function getSupplier ($requestedData) {

        if ($requestedData['invoice_type'] == 'normal') {

            $invoice = PurchaseInvoice::find($requestedData['purchase_invoice_id']);

            return $invoice ? $invoice->supplier : null;

        }else {

            $supplyOrder = isset($requestedData['supply_order_ids'][0]) ? SupplyOrder::find($requestedData['supply_order_ids'][0]) : null;

            return $supplyOrder && $supplyOrder->supplier ? $supplyOrder->supplier : null;
        }
    }

    public function supplierDiscount($supplier, $total)
    {
        $supplier_discount = $supplier->group_discount;
        $supplier_discount_type = $supplier->group_discount_type;

        return $this->discountValue($supplier_discount_type, $supplier_discount, $total);
    }

    public function purchaseReturnTaxes($purchaseReturn, $data)
    {
        $taxes = [];

        if (isset($data['taxes'])) {
            $taxes = array_merge($data['taxes'], $taxes);
        }

        if (isset($data['additional_payments'])) {
            $taxes = array_merge($data['additional_payments'], $taxes);
        }

        if (!empty($taxes)) {
            $purchaseReturn->taxes()->attach($taxes);
        }
    }

    public function affectedPart($purchaseReturnItem)
    {
        $part = $purchaseReturnItem->part;

        $data = ['status'=> true];

        if ($purchaseReturnItem->quantity > $part->quantity) {

            $data['status'] = false;
            $data['message'] = __('sorry, this part quantity less than requested');
            return $data;
        }

        $part = $purchaseReturnItem->part;

        $partStorePivot = $part->stores()->where('store_id', $purchaseReturnItem->store_id)->first();

        if (!$part || !$partStorePivot || !$partStorePivot->pivot) {

            $data['status'] = false;
            $data['message'] = __('sorry, item store not valid');
            return $data;
        }

        $unitQuantity = $purchaseReturnItem->partPrice ? $purchaseReturnItem->partPrice->quantity : 1;

        $requestedQuantity = $unitQuantity * $purchaseReturnItem->quantity;

        if ($requestedQuantity > $partStorePivot->pivot->quantity) {

            $data['status'] = false;
            $data['message'] = __('sorry, quantity requested more than in store');
            return $data;
        }

        $part->quantity -= $purchaseReturnItem->quantity;
        $part->save();

        $partStorePivot->pivot->quantity -= $requestedQuantity;
        $partStorePivot->pivot->save();

        return $data;
    }
}
