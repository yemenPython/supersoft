<?php


namespace App\Services;


use App\Models\PurchaseQuotation;
use App\Models\PurchaseQuotationItem;

class PurchaseQuotationCompareServices
{
    public function filter($request, $data, $branch_id)
    {
//        $quotationItems = PurchaseQuotationItem::with('purchaseQuotation')

          $data  ->whereHas('purchaseQuotation', function ($q) use ($branch_id) {
                $q->where('branch_id', $branch_id);
            });

        if ($request->has('part_id') && $request['part_id'] != null) {
            $data->where('part_id', $request['part_id']);
        }

        if ($request->has('part_type_id') && $request['part_type_id'] != null) {
            $data->whereHas('part', function ($q) use ($request) {
                $q->whereHas('spareParts', function ($q) use ($request) {
                    $q->where('spare_part_type_id', $request['part_type_id']);
                });
            });
        }

        if ($request->has('supplier_id') && $request['supplier_id'] != null) {

            $data->whereHas('purchaseQuotation', function ($q) use ($request) {
                $q->where('supplier_id', $request['supplier_id']);
            });
        }

        if ($request->has('quotation_number') && $request['quotation_number'] != null) {

            $data->whereHas('purchaseQuotation', function ($q) use ($request) {
                $q->where('id', $request['quotation_number']);
            });
        }

        if ($request->has('purchase_request_id') && $request['purchase_request_id'] != null) {

            $data->whereHas('purchaseQuotation', function ($q) use ($request) {
                $q->where('purchase_request_id', $request['purchase_request_id']);
            });
        }

        if ($request->has('date_from') && $request['date_from'] != null) {

            $data->whereHas('purchaseQuotation', function ($q) use ($request) {
                $q->where('created_at', '>=', $request['date_from']);
            });
        }

        if ($request->has('date_to') && $request['date_to'] != null) {

            $data->whereHas('purchaseQuotation', function ($q) use ($request) {
                $q->where('created_at', '<=', $request['date_to']);
            });
        }

        if ($request->has('part_barcode') && $request['part_barcode'] != null) {
            $data->whereHas('part', function ($q) use ($request) {
                $q->whereHas('prices', function ($q) use ($request) {
                    $q->where('barcode', 'like', '%' . $request['part_barcode'] . '%');
                });
            });
        }

        if ($request->has('supplier_barcode') && $request['supplier_barcode'] != null) {
            $data->whereHas('part', function ($q) use ($request) {
                $q->whereHas('prices', function ($q) use ($request) {
                    $q->where('supplier_barcode', 'like', '%' . $request['supplier_barcode'] . '%');
                });
            });
        }

        return $data;

//        $quotationItems = $quotationItems->get();
//
//        return $quotationItems;
    }

    public function checkItems($purchaseQuotationsItems)
    {

        $data = ['status' => true];
        $suppliers = [];
        $purchaseRequests = [];

        foreach ($purchaseQuotationsItems as $index => $itemId) {

            $item = PurchaseQuotationItem::find($itemId);

            if (!$item) {
                $data['status'] = false;
                $data['message'] = 'sorry, purchase quotation item not valid';
                return $data;
            }

            $purchaseQuotation = $item->purchaseQuotation;

            if (!$purchaseQuotation) {
                $data['status'] = false;
                $data['message'] = 'sorry, purchase quotation not valid';
                return $data;
            }

            $data['purchase_quotations'][] = $purchaseQuotation->id;

            if (!empty($suppliers) && !in_array($purchaseQuotation->supplier_id, $suppliers)) {

                $data['status'] = false;
                $data['message'] = 'sorry, supplier is different';
                return $data;
            }

            $suppliers[] = $purchaseQuotation->supplier_id;

            if (!empty($purchaseRequests) && !in_array($purchaseQuotation->purchase_request_id, $purchaseRequests)) {

                $data['status'] = false;
                $data['message'] = 'sorry, purchase request is different';
                return $data;
            }

            $purchaseRequests[] = $purchaseQuotation->purchase_request_id;
        }

        $data['purchase_request_ids'] = $purchaseRequests;
        $data['suppliersId'] = $suppliers;

        return $data;
    }
}
