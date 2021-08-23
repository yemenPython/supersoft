<?php


namespace App\Services;


use App\Models\Part;
use App\Models\PurchaseInvoiceItem;
use App\Models\ReturnedSaleReceipt;
use App\Models\ReturnedSaleReceiptItem;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceItems;
use App\Models\TaxesFees;

class SalesInvoiceReturnServices
{
    public function calculateItemTotal($item, $invoiceType)
    {
        $returnedModelItem = $this->getReturnedModelItem($item['item_id'], $invoiceType);

        $data = [

            'part_id' => $returnedModelItem->part_id,
            'store_id' => $returnedModelItem->store_id,
            'quantity' => $item['quantity'],
            'price' => $returnedModelItem->price,
            'discount_type' => $item['discount_type'],
            'discount' => $item['discount'],
            'spare_part_id' => $returnedModelItem->spare_part_id,
            'part_price_id' => $returnedModelItem->part_price_id,
            'part_price_segment_id' => $returnedModelItem->part_price_segment_id,

            'active'=> isset($item['active']) ? 1:0,

            'max_quantity'=> $returnedModelItem->quantity,

            'itemable_id'=> $item['item_id'],
            'itemable_type'=> $this->getReturnedModelItemPath($invoiceType),

            'sub_total' => $item['quantity'] * $returnedModelItem->price
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
        $returnedModel = $this->getReturnedModel($data_request['invoiceable_id'], $data_request['invoice_type']);

        $data = [

            'number' => $data_request['number'],
            'time' => $data_request['time'],
            'date' => $data_request['date'],
            'type' => $data_request['type'],
            'invoice_type' => $data_request['invoice_type'],
            'invoiceable_id' => $data_request['invoiceable_id'],
            'invoiceable_type' => $this->getReturnedModelPath($data_request['invoice_type']),

            'clientable_id' => $returnedModel->clientable_id,
            'clientable_type' => $returnedModel->clientable_type,

            'discount_type' => $data_request['discount_type'],
            'discount' => $data_request['discount'],
            'status' => $data_request['status'],
            'customer_discount_active'=> 0,
            'customer_discount' => 0,
            'customer_discount_type' => 'amount'
        ];

        $data['sub_total'] = 0;
        $customer_discount = 0;

        foreach ($data_request['items'] as $item) {

            if (isset($item['active'])) {

                $item_data = $this->calculateItemTotal($item, $data['invoice_type']);
                $data['sub_total'] += $item_data['total'];
            }
        }

//        if (isset($data_request['customer_discount_active'])) {
//
//            $client = $data['salesable_type']::find($data['salesable_id']);
//
//            $data['customer_discount_active'] = 1;
//            $data['customer_discount'] = $data['type_for'] == 'customer' ? $client->group_sales_discount : $client->group_discount;
//            $data['customer_discount_type'] = $data['type_for'] == 'customer' ? $client->group_sales_discount_type : $client->group_discount_type ;
//
//            $customer_discount = $this->customerDiscount($client, $data['sub_total'], $data['type_for']);
//        }

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

    public function salesInvoiceReturnTaxes($salesInvoiceReturn, $data)
    {
        $taxes = [];

        if (isset($data['taxes'])) {
            $taxes = array_merge($data['taxes'], $taxes);
        }

        if (isset($data['additional_payments'])) {
            $taxes = array_merge($data['additional_payments'], $taxes);
        }

        if (!empty($taxes)) {
            $salesInvoiceReturn->taxes()->attach($taxes);
        }
    }

    function resetSalesInvoiceReturnDataItems($salesInvoiceReturn)
    {
        foreach ($salesInvoiceReturn->items as $item) {
            $item->taxes()->detach();
            $item->delete();
        }

        $salesInvoiceReturn->taxes()->detach();
    }

    public function getReturnedModelPath ($type) {

        if (in_array($type, ['direct_invoice', 'direct_sale_quotations'])) {

           return 'App\Models\SalesInvoiceReturn';
        }

        return 'App\Models\ReturnedSaleReceipt';
    }

    public function getReturnedModelItemPath ($type) {

        if (in_array($type, ['direct_invoice', 'direct_sale_quotations'])) {

            return 'App\Models\SalesInvoiceItemReturn';
        }

        return 'App\Models\ReturnedSaleReceiptItem';
    }

    public function getReturnedModel($invoiceable_id, $invoiceType) {

        if (in_array($invoiceType, ['direct_invoice', 'direct_sale_quotations'])) {

            return SalesInvoice::find($invoiceable_id);
        }

        return ReturnedSaleReceipt::find($invoiceable_id);
    }

    public function getReturnedModelItem($item_id, $invoiceType) {

        if (in_array($invoiceType, ['direct_invoice', 'direct_sale_quotations'])) {

            return SalesInvoiceItems::find($item_id);
        }

        return ReturnedSaleReceiptItem::find($item_id);
    }

    public function getTypeItems ($type, $salesInvoiceReturnId = null) {

        if ($type == 'normal') {

            $items = ReturnedSaleReceipt::whereHas('concession', function ($q) {

                $q->where('status', 'accepted');

            })->whereDoesntHave('salesInvoiceReturn', function ($s) use($salesInvoiceReturnId) {

                $s->when($salesInvoiceReturnId, function ($sa) use($salesInvoiceReturnId) {
                    $sa->where('id', '!=', $salesInvoiceReturnId);
                });

            })
                ->where('type', 'from_invoice')
                ->select('id', 'number')
                ->get();

        } elseif ($type == 'direct_invoice') {

            $items = SalesInvoice::where('status', 'finished')
                ->where('invoice_type', 'direct_invoice')
                ->select('id', 'number')
                ->get();

        } elseif ($type == 'direct_sale_quotations') {

            $items = SalesInvoice::where('status', 'finished')
                ->where('invoice_type', 'direct_sale_quotations')
                ->select('id', 'number')
                ->get();

        } elseif ($type == 'from_sale_quotations') {

            $items = ReturnedSaleReceipt::whereHas('concession', function ($q) {

                $q->where('status', 'accepted');

            })->where('type', 'from_sale_quotation')
                ->select('id', 'number')
                ->get();

        } elseif ($type == 'from_sale_supply_order') {

            $items = ReturnedSaleReceipt::whereHas('concession', function ($q) {

                $q->where('status', 'accepted');

            })->where('type', 'from_sale_supply_order')
                ->select('id', 'number')
                ->get();
        }

        return $items;
    }
}
