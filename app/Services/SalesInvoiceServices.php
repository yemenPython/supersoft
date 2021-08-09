<?php


namespace App\Services;


use App\Models\Part;
use App\Models\PurchaseInvoiceItem;
use App\Models\Supplier;
use App\Models\TaxesFees;

class SalesInvoiceServices
{

    public function calculateItemTotal($item)
    {
        $data = [

            'part_id' => $item['part_id'],
            'store_id' => $item['store_id'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
            'discount_type' => $item['discount_type'],
            'discount' => $item['discount'],
            'spare_part_id' => isset($item['spare_part_id']) ? $item['spare_part_id'] : null,
            'part_price_id' => $item['part_price_id'],
            'part_price_segment_id' => isset($item['part_price_segment_id']) ? $item['part_price_segment_id'] : null,

            'sub_total' => $item['quantity'] * $item['price']
        ];

        $discount_value = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - $discount_value;

        $taxIds = isset($item['taxes']) ? $item['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data['sub_total'], $taxIds);

        $data['total'] = $data['total_after_discount'] + $data['tax'];

        return $data;
    }

    public function prepareInvoiceData($data_request)
    {
        $data = [

            'number' => $data_request['number'],
            'invoice_type' => $data_request['invoice_type'],
            'type_for' => $data_request['type_for'],
            'salesable_id' => $data_request['salesable_id'] ?? null,
            'salesable_type' => $data_request['type_for'] == 'customer' ? 'App\Models\Customer':'App\Models\Supplier',
            'time' => $data_request['time'],
            'date' => $data_request['date'],
            'type' => $data_request['type'],
            'discount_type' => $data_request['discount_type'],
            'discount' => $data_request['discount'],
            'status' => $data_request['status']
        ];

        $data['sub_total'] = 0;
        $customer_discount = 0;

        foreach ($data_request['items'] as $item) {

            $item_data = $this->calculateItemTotal($item);
            $data['sub_total'] += $item_data['total'];
        }

        if (isset($data_request['customer_discount_active'])) {

            $client = $data['salesable_type']::find($data['salesable_id']);

            $data['customer_discount_active'] = 1;
            $data['customer_discount'] = $data['type_for'] == 'customer' ? $client->group_sales_discount : $client->group_discount;
            $data['customer_discount_type'] = $data['type_for'] == 'customer' ? $client->group_sales_discount_type : $client->group_discount_type ;

            $customer_discount = $this->customerDiscount($client, $data['sub_total'], $data['type_for']);
        }

        $discount = $this->discountValue($data['discount_type'], $data['discount'], $data['sub_total']);

        $data['total_after_discount'] = $data['sub_total'] - ($discount + $customer_discount);

        $taxIds = isset($data_request['taxes']) ? $data_request['taxes'] : [];

        $data['tax'] = $this->taxesValue($data['total_after_discount'], $data['sub_total'], $taxIds);

        $additionalPayments = isset($data_request['additional_payments']) ? $data_request['additional_payments'] : [];

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

        $taxes = TaxesFees::whereIn('id', $itemTaxes)->get();

        foreach ($taxes as $tax) {

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

        return $value;
    }

    public function customerDiscount($client, $total, $type)
    {
        $customer_discount = $type == 'customer' ? $client->group_sales_discount : $client->group_discount;;
        $customer_discount_type = $type == 'customer' ? $client->group_sales_discount_type : $client->group_discount_type;

        return $this->discountValue($customer_discount_type, $customer_discount, $total);
    }

    public function deletePartsNotInRequest($invoiceItemsIds, $requestItemsIds)
    {

        foreach ($invoiceItemsIds as $id) {

            $invoice_item = PurchaseInvoiceItem::find($id);

            if ($invoice_item && !in_array($id, $requestItemsIds)) {

                $this->restPart($invoice_item->part_id, $invoice_item->purchase_qty);

                $invoice_item->delete();
            }
        }
    }

    public function restPart($part_id, $purchase_qty)
    {

        $part = Part::find($part_id);

        if ($part) {

            $part->quantity -= $purchase_qty;

            if ($part->quantity < 0)
                $part->quantity = 0;

            $part->save();
        }
    }

    public function affectedPart($purchaseInvoiceItem)
    {
        $part = $purchaseInvoiceItem->part;

        if ($part) {
            $part->quantity += $purchaseInvoiceItem->quantity;
            $part->save();
        }

        $this->saveStoreQuantity($purchaseInvoiceItem);
    }

    public function salesInvoiceTaxes($salesInvoice, $data)
    {
        $taxes = [];

        if (isset($data['taxes'])) {
            $taxes = array_merge($data['taxes'], $taxes);
        }

        if (isset($data['additional_payments'])) {
            $taxes = array_merge($data['additional_payments'], $taxes);
        }

        if (!empty($taxes)) {
            $salesInvoice->taxes()->attach($taxes);
        }
    }

    function resetSalesInvoiceDataItems($salesInvoice)
    {
        foreach ($salesInvoice->items as $item) {
            $item->taxes()->detach();
            $item->delete();
        }

        $salesInvoice->taxes()->detach();
        $salesInvoice->saleQuotations()->detach();
    }

    public function saveStoreQuantity($item)
    {
        $part = $item->part;

        $partStorePivot = $part->stores()->where('store_id', $item->store_id)->first();

        if (!$partStorePivot) {
            $part->stores()->attach($item->store_id);
        }

        $partStorePivot = $part->load('stores')->stores()->where('store_id', $item->store_id)->first();

        $unitQuantity = $item->partPrice ? $item->partPrice->quantity : 1;

        $requestedQuantity = $unitQuantity * $item->quantity;

        $partStorePivot->pivot->quantity += $requestedQuantity;

        $partStorePivot->pivot->save();

        return true;
    }

}
