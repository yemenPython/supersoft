<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PurchaseReturn;
use App\Models\Area;
use App\Models\Asset;
use App\Models\AssetExpense;
use App\Models\AssetGroup;
use App\Models\AssetMaintenance;
use App\Models\AssetsItemExpense;
use App\Models\AssetsTypeExpense;
use App\Models\BankAccount;
use App\Models\Banks\BankAccount as BAC;
use App\Models\Banks\BankCommissioner;
use App\Models\Banks\BankData;
use App\Models\Banks\BankOfficial;
use App\Models\Banks\BranchProduct;
use App\Models\Banks\OpeningBalanceAccount;
use App\Models\City;
use App\Models\Country;
use App\Models\Customer;
use App\Models\DamagedStock;
use App\Models\EmployeeData;
use App\Models\Locker;
use App\Models\LockerOpeningBalance;
use App\Models\MaintenanceCenter;
use App\Models\MaintenanceDetection;
use App\Models\MaintenanceDetectionType;
use App\Models\OpeningBalance;
use App\Models\Part;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseQuotation;
use App\Models\PurchaseReceipt;
use App\Models\PurchaseRequest;
use App\Models\SaleQuotation;
use App\Models\Settlement;
use App\Models\Store;
use App\Models\Supplier;
use App\Models\SupplierContact;
use App\Models\SupplyOrder;
use Illuminate\Http\Request;
use DB;

class AjaxController extends Controller
{
    protected $storeId;
    protected $partId;
    protected $serialNumber;
    protected $asset_group_name;
    protected $asset_expense_type;
    protected $type_of_purchase_quotation;
    protected $supplier_id;
    protected $purchase_request_id;
    protected $supply_order_type;
    protected $quotation_type;
    protected $number;
    protected $supply_order_number;
    protected $invoice_type;
    protected $type;
    protected $supplierID;
    protected $maintenance_detection_type_id_select2;
    protected $asset_id_select_2;
    protected $bank_data_id;
    protected $country_id;
    protected $city_id;

    public function AutoComplete(Request $request)
    {
        $limit = 10;
        $data = [];

        if ($request->has('limit') && !empty($request->limit)) {
            $limit = $request->limit;
        }

        if ($request->has('model') && !empty($request->model)) {

            $selectedColumns = ($request->has('selectedColumns') && !empty($request->selectedColumns))
                ? $request->selectedColumns : '*';

            $searchFields = ($request->has('searchFields') && !empty($request->searchFields)) ? explode(',',
                $request->searchFields) : [];

            $searchTerm = ($request->has('searchTerm') && !empty($request->searchTerm)) ? $request->searchTerm : '';

            $branchId = ($request->has('branch_id') && !empty($request->branch_id)) ? $request->branch_id : '';
            $this->storeId = ($request->has('store_id') && !empty($request->store_id)
                && $request->store_id != __('words.select-one')) ? $request->store_id : '';
            $this->partId = ($request->has('part_name') && !empty($request->part_name)
                && $request->part_name != __('words.select-one')) ? $request->part_name : '';
            $this->serialNumber = ($request->has('serial_number') && !empty($request->serial_number)
                && $request->serial_number != __('words.select-one')) ? $request->serial_number : '';
            $this->asset_group_name = ($request->has('asset_group_name') && !empty($request->asset_group_name)
                && $request->asset_group_name != __('words.select-one')) ? $request->asset_group_name : '';
            $this->asset_expense_type = ($request->has('asset_expense_type') && !empty($request->asset_expense_type)
                && $request->asset_expense_type != __('words.select-one')) ? $request->asset_expense_type : '';
            $this->type_of_purchase_quotation = ($request->has('type_of_purchase_quotation') && !empty($request->type_of_purchase_quotation)
                && $request->type_of_purchase_quotation != __('words.select-one')) ? $request->type_of_purchase_quotation : '';
            $this->supplier_id = ($request->has('supplier_id') && !empty($request->supplier_id)
                && $request->supplier_id != __('words.select-one')) ? $request->supplier_id : '';
            $this->purchase_request_id = ($request->has('purchase_request_id') && !empty($request->purchase_request_id)
                && $request->purchase_request_id != __('words.select-one')) ? $request->purchase_request_id : '';
            $this->supply_order_type = ($request->has('supply_order_type') && !empty($request->supply_order_type)
                && $request->supply_order_type != __('words.select-one')) ? $request->supply_order_type : '';
            $this->quotation_type = ($request->has('quotation_type') && !empty($request->quotation_type)
                && $request->quotation_type != __('words.select-one')) ? $request->quotation_type : '';
            $this->number = ($request->has('number') && !empty($request->number)
                && $request->number != __('words.select-one')) ? $request->number : '';
            $this->supply_order_number = ($request->has('supply_order_number') && !empty($request->supply_order_number)
                && $request->supply_order_number != __('words.select-one')) ? $request->supply_order_number : '';
            $this->invoice_type = ($request->has('invoice_type') && !empty($request->invoice_type)
                && $request->invoice_type != __('words.select-one')) ? $request->invoice_type : '';
            $this->type = ($request->has('type') && !empty($request->type)
                && $request->type != __('words.select-one')) ? $request->type : '';
            $this->supplierID = ($request->has('supplierID') && !empty($request->supplierID)
                && $request->supplierID != __('words.select-one')) ? $request->supplierID : '';
            $this->maintenance_detection_type_id_select2 = ($request->has('maintenance_detection_type_id_select2') && !empty($request->maintenance_detection_type_id_select2)
                && $request->maintenance_detection_type_id_select2 != __('words.select-one')) ? $request->maintenance_detection_type_id_select2 : '';
            $this->asset_id_select_2 = ($request->has('asset_id_select_2') && !empty($request->asset_id_select_2)
                && $request->asset_id_select_2 != __('words.select-one')) ? $request->asset_id_select_2 : '';
            $this->bank_data_id = ($request->has('bank_data_id') && !empty($request->bank_data_id)
                && $request->bank_data_id != __('words.select-one')) ? $request->bank_data_id : '';
            $this->country_id = ($request->has('country_id') && !empty($request->country_id)
                && $request->country_id != __('words.select-one')) ? $request->country_id : '';

            $this->city_id = ($request->has('city_id') && !empty($request->city_id) && $request->city_id != __('words.select-one')) ? $request->city_id : '';

            switch ($request->model) {
                case 'User':
                    $data = $this->getUsers($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'Branch':
                    $data = $this->getBranches($searchFields, $searchTerm, $selectedColumns, $limit);
                    break;
                    case 'Asset':
                    $data = $this->getAsset($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                    case 'AssetInsurances':
                    $data = $this->getAssetInsurances($searchFields, $searchTerm, $selectedColumns, $limit);
                    break;
                    case 'AssetLicenses':
                    $data = $this->getAssetLicenses($searchFields, $searchTerm, $selectedColumns, $limit);
                    break;
                    case 'AssetExamination':
                    $data = $this->getAssetExamination($searchFields, $searchTerm, $selectedColumns, $limit);
                    break;
                case 'Shift':
                    $data = $this->getShifts($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'TaxesFees':
                    $data = $this->getTaxesFees($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'Concession':
                    $data = $this->getConcessions($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'Store':
                    $data = $this->getStores($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'OpeningBalance':
                    $data = $this->getOpeningBalance($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'Part':
                    $data = $this->getParts($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'DamagedStock':
                    $data = $this->getdamagedStock($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'Settlement':
                    $data = $this->getSettlements($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'StoreTransfer':
                    $data = $this->getStoreTransfer($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'EmployeeData':
                    $data = $this->getEmployeeData($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'Supplier':
                    $data = $this->getSuppliers($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'AssetExpense':
                    $data = $this->getAssetExpenses($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'AssetGroup':
                    $data = $this->getAssetGroups($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'AssetsTypeExpense':
                    $data = $this->getAssetsTypeExpenses($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;

                case 'AssetsItemExpense':
                    $data = $this->getAssetsItemExpenses($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'PurchaseRequest':
                    $data = $this->getPurchaseRequestNumber($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'PurchaseQuotation':
                    $data = $this->getPurchaseQuationNumber($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'SupplyOrder':
                    $data = $this->getSupplyOrders($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'PurchaseReceipt':
                    $data = $this->getPurchaseReceipts($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'PurchaseInvoice':
                    $data = $this->getPurchaseInvoices($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'PurchaseReturn':
                    $data = $this->getPurchaseReturns($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'SupplierContact':
                    $data = $this->getSupplierContacts($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'BankAccount':
                    $data = $this->getBankAccounts($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'MaintenanceDetectionType':
                    $data = $this->getMaintenanceDetectionType($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'MaintenanceDetection':
                    $data = $this->getMaintenanceDetection($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'AssetMaintenance':
                    $data = $this->getAssetMaintenance($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'MaintenanceCenter':
                    $data = $this->getMaintenanceCenters($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'Locker':
                    $data = $this->getLockers($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'LockerOpeningBalance':
                    $data = $this->getLockerOpeningBalance($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'BankOfficial':
                    $data = $this->getBankOfficials($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'BankCommissioner':
                    $data = $this->getBankCommissioner($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'Country':
                    $data = $this->getCountries($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'City':
                    $data = $this->getCities($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'Area':
                    $data = $this->getAreas($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'BankData':
                    $data = $this->getBankData($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'BranchProduct':
                    $data = $this->getBranchProduct($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'BAC':
                    $data = $this->getBankAccount($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'OpeningBalanceAccount':
                    $data = $this->getOpeningBalanceAccount($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'SaleQuotation':
                    $data = $this->getSaleQuotations($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                case 'Customer':
                    $data = $this->getCustomers($searchFields, $searchTerm, $selectedColumns, $limit, $branchId);
                    break;
                default:
                    break;
            }
        }

        return $data;
    }

    public function getUsers($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $users = DB::table('users')->whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $users = $users->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $users = $users->where('branch_id', $branchId);
        }
        $users = $users->limit($limit)->get();

        // dd($selectedColumns);

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'text' => $this->buildSelectedColumnsAsText($user, $selectedColumns)
            ];
        }

        return $data;
    }

    public function getBranches($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10)
    {
        $data = [];

        $id = ' id ,';

        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }


        $resources = DB::table('branches')->whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $resources = $resources->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }

        if (!empty($branchId)) {
            $resources = $resources->where('branch_id', $branchId);
        }
        $resources = $resources->limit($limit)->get();

        foreach ($resources as $resource) {
            $onItemData = [
                'id' => $resource->id,
                'text' => $this->buildSelectedColumnsAsText($resource, $selectedColumns)
            ];

            $data[] = $onItemData;
        }

        return $data;
    }
    public function getAsset($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assets = Asset::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assets = $assets->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assets = $assets->where('branch_id', $branchId);
        }

        if (!empty($this->asset_group_name)) {
            $assets = $assets->where('asset_group_id', $this->asset_group_name);
        }

        $assets = $assets->limit($limit)->get();
        foreach ($assets as $asset) {
            $data[] = [
                'id' => $asset->id,
                'text' => $this->buildSelectedColumnsAsText($asset, $selectedColumns)
            ];
        }
        return $data;
    }
    public function getAssetInsurances($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10)
    {
        $data = [];

        $id = ' id ,';

        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }


        $resources = DB::table('assets_insurances')->whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $resources = $resources->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        $resources = $resources->limit($limit)->get();
        foreach ($resources as $resource) {
            $onItemData = [
                'id' => $resource->id,
                'text' => $this->buildSelectedColumnsAsText($resource, $selectedColumns)
            ];

            $data[] = $onItemData;
        }

        return $data;
    }
    public function getAssetLicenses($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10)
    {
        $data = [];

        $id = ' id ,';

        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }


        $resources = DB::table('assets_licenses')->whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $resources = $resources->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        $resources = $resources->limit($limit)->get();
        foreach ($resources as $resource) {
            $onItemData = [
                'id' => $resource->id,
                'text' => $this->buildSelectedColumnsAsText($resource, $selectedColumns)
            ];

            $data[] = $onItemData;
        }

        return $data;
    }
    public function getAssetExamination($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10)
    {
        $data = [];

        $id = ' id ,';

        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }


        $resources = DB::table('assets_examinations')->whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $resources = $resources->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        $resources = $resources->limit($limit)->get();
        foreach ($resources as $resource) {
            $onItemData = [
                'id' => $resource->id,
                'text' => $this->buildSelectedColumnsAsText($resource, $selectedColumns)
            ];

            $data[] = $onItemData;
        }

        return $data;
    }

    public function getShifts($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];

        $id = ' id ,';

        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }


        $resources = DB::table('shifts')->whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $resources = $resources->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $resources = $resources->where('branch_id', $branchId);
        }
        $resources = $resources->limit($limit)->get();


        foreach ($resources as $resource) {
            $data[] = [
                'id' => $resource->id,
                'text' => $this->buildSelectedColumnsAsText($resource, $selectedColumns)
            ];
        }

        return $data;
    }

    public function getTaxesFees($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];

        $id = ' id ,';

        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }


        $resources = DB::table('taxes_fees')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $resources = $resources->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $resources = $resources->where('branch_id', $branchId);
        }
        $resources = $resources->limit($limit)->get();

        // dd($resources);

        foreach ($resources as $resource) {
            $data[] = [
                'id' => $resource->id,
                'text' => $this->buildSelectedColumnsAsText($resource, $selectedColumns)
            ];
        }

        return $data;
    }

    public function getConcessions($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];

        $id = ' id ,';

        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }

        $concessions = DB::table('concessions')->whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $concessions = $concessions->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $concessions = $concessions->where('branch_id', $branchId);
        }
        $concessions = $concessions->limit($limit)->get();

        // dd($selectedColumns);

        foreach ($concessions as $concession) {
            $data[] = [
                'id' => $concession->id,
                'text' => $this->buildSelectedColumnsAsText($concession, $selectedColumns)
            ];
        }

        return $data;
    }

    public function getStores(
        $searchFields = [],
        $searchTerm = '',
        $selectedColumns = '*',
        $limit = 10,
        $branchId = null
    ) {
        $data = [];
        $id = 'id,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $stores = Store::whereNull('deleted_at')->select(DB::raw($selectedColumns));
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $stores = $stores->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }

        if (!empty($branchId)) {
            $stores = $stores->where('branch_id', $branchId);
        }

        $stores = $stores->limit($limit)->get();
        foreach ($stores as $store) {
            $data[] = [
                'id' => $store->id,
                'text' => $this->buildSelectedColumnsAsText($store, $selectedColumns)
            ];
        }
        return $data;
    }

    public function getOpeningBalance(
        $searchFields = [],
        $searchTerm = '',
        $selectedColumns = '*',
        $limit = 10,
        $branchId = null
    ) {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $openingBalances = OpeningBalance::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $openingBalances = $openingBalances->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $openingBalances = $openingBalances->where('branch_id', $branchId);
        }

        if (!empty($this->partId)) {
            $openingBalances = $openingBalances->whereHas('items', function ($q) {
                $q->where('part_id', $this->partId);
            });
        }
        if (!empty($this->storeId)) {
            $openingBalances = $openingBalances->whereHas('items', function ($q) {
                $q->where('store_id', $this->storeId);
            });
        }
        $openingBalances = $openingBalances->limit($limit)->get();
        foreach ($openingBalances as $openingBalance) {
            $data[] = [
                'id' => $openingBalance->serial_number,
                'text' => $this->buildSelectedColumnsAsText($openingBalance, $selectedColumns)
            ];
        }
        return $data;
    }


    public function getParts($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $parts = Part::whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $parts = $parts->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $parts = $parts->where('branch_id', $branchId);
        }

        if (!empty($this->storeId)) {
            $parts = $parts->whereHas('stores', function ($q) {
                $q->where('store_id', $this->storeId);
            });
        }

        $parts = $parts->limit($limit)->get();
        foreach ($parts as $part) {
            $data[] = [
                'id' => $part->id,
                'text' => $this->buildSelectedColumnsAsText($part, $selectedColumns)
            ];
        }
        return $data;
    }

    public function getdamagedStock($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $damagedStocks = DamagedStock::whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $damagedStocks = $damagedStocks->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $damagedStocks = $damagedStocks->where('branch_id', $branchId);
        }
        if (!empty($this->storeId)) {
            $damagedStocks = $damagedStocks->whereHas('items', function ($q) {
                $q->where('store_id', $this->storeId);
            });
        }
        $damagedStocks = $damagedStocks->limit($limit)->get();
        foreach ($damagedStocks as $damagedStock) {
            $data[] = [
                'id' => $damagedStock->id,
                'text' => $this->buildSelectedColumnsAsText($damagedStock, $selectedColumns)
            ];
        }
        return $data;
    }

    public function getSettlements($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $settlements = Settlement::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $settlements = $settlements->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $settlements = $settlements->where('branch_id', $branchId);
        }
        if (!empty($this->storeId)) {
            $settlements = $settlements->whereHas('items', function ($q) {
                $q->where('store_id', $this->storeId);
            });
        }
        $settlements = $settlements->limit($limit)->get();
        foreach ($settlements as $settlement) {
            $data[] = [
                'id' => $settlement->id,
                'text' => $this->buildSelectedColumnsAsText($settlement, $selectedColumns)
            ];
        }
        return $data;
    }

    public function getStoreTransfer($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $settlements = DB::table('store_transfers')->whereNull('deleted_at')->select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $settlements = $settlements->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $settlements = $settlements->where('branch_id', $branchId);
        }
        $settlements = $settlements->limit($limit)->get();
        foreach ($settlements as $settlement) {
            $data[] = [
                'id' => $settlement->id,
                'text' => $this->buildSelectedColumnsAsText($settlement, $selectedColumns)
            ];
        }
        return $data;
    }

    public function getEmployeeData($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $employees = EmployeeData::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $employees = $employees->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $employees = $employees->where('branch_id', $branchId);
        }
        $employees = $employees->limit($limit)->get();
        foreach ($employees as $employee) {
            $data[] = [
                'id' => $employee->id,
                'text' => $this->buildSelectedColumnsAsText($employee, $selectedColumns)
            ];
        }
        return $data;
    }

    public function getSuppliers($searchFields = [], $searchTerm = '', $selectedColumns = '*', $limit = 10, $branchId = null)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $suppliers = Supplier::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $suppliers = $suppliers->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $suppliers = $suppliers->where('branch_id', $branchId);
        }
        $suppliers = $suppliers->limit($limit)->get();
        foreach ($suppliers as $suppler) {
            $data[] = [
                'id' => $suppler->id,
                'text' => $this->buildSelectedColumnsAsText($suppler, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getAssetExpenses(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId): array
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsExpenses = AssetExpense::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsExpenses = $assetsExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsExpenses = $assetsExpenses->where('branch_id', $branchId);
        }
        $assetsExpenses = $assetsExpenses->limit($limit)->get();
        foreach ($assetsExpenses as $assetsExpense) {
            $data[] = [
                'id' => $assetsExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsExpense, $selectedColumns)
            ];
        }
        return $data;
    }


    private function getAssetGroups(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsGroups = AssetGroup::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsGroups = $assetsGroups->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsGroups = $assetsGroups->where('branch_id', $branchId);
        }


        $assetsGroups = $assetsGroups->limit($limit)->get();
        foreach ($assetsGroups as $assetsGroup) {
            $data[] = [
                'id' => $assetsGroup->id,
                'text' => $this->buildSelectedColumnsAsText($assetsGroup, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getAssetsTypeExpenses(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId): array
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsTypeExpenses = AssetsTypeExpense::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsTypeExpenses = $assetsTypeExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsTypeExpenses = $assetsTypeExpenses->where('branch_id', $branchId);
        }


        $assetsTypeExpenses = $assetsTypeExpenses->limit($limit)->get();
        foreach ($assetsTypeExpenses as $assetsTypeExpense) {
            $data[] = [
                'id' => $assetsTypeExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsTypeExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getAssetsItemExpenses(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId): array
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = AssetsItemExpense::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsItemExpenses = $assetsItemExpenses->where('branch_id', $branchId);
        }
        if (!empty($this->asset_expense_type)) {
            $assetsItemExpenses = $assetsItemExpenses->where('assets_type_expenses_id', $this->asset_expense_type);
        }


        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    public function getPurchaseRequestNumber(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId): array
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = PurchaseRequest::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsItemExpenses = $assetsItemExpenses->where('branch_id', $branchId);
        }

        if (!empty($this->number)) {
            $purchaseRId = SupplyOrder::find($this->number);
           if ($purchaseRId) {
               $assetsItemExpenses = $assetsItemExpenses->where('id', $purchaseRId->purchase_request_id);
           }
        }

        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    public function getPurchaseQuationNumber(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId): array
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = PurchaseQuotation::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsItemExpenses = $assetsItemExpenses->where('branch_id', $branchId);
        }

        if (!empty($this->type_of_purchase_quotation)) {
            $assetsItemExpenses = $assetsItemExpenses->where('type', $this->type_of_purchase_quotation);
        }

        if (!empty($this->supplier_id)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supplier_id', $this->supplier_id);
        }

        if (!empty($this->purchase_request_id)) {
            $assetsItemExpenses = $assetsItemExpenses->where('purchase_request_id', $this->purchase_request_id);
        }

        if (!empty($this->quotation_type)) {
            if ($this->quotation_type == 'cash_credit') {
                $assetsItemExpenses = $assetsItemExpenses->whereIn('quotation_type', ['credit', 'cash']);
            } else {
                $assetsItemExpenses = $assetsItemExpenses->where('quotation_type', $this->quotation_type);
            }

        }

        if (!empty($this->number)) {
            $assetsItemExpenses = $assetsItemExpenses->whereHas('supplyOrders', function ($q) {
                $q->where('supply_order_id', $this->number);
            });
        }
        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    public function getSupplyOrders(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId): array
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = SupplyOrder::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsItemExpenses = $assetsItemExpenses->where('branch_id', $branchId);
        }

        if (!empty($this->supply_order_type)) {
            $assetsItemExpenses = $assetsItemExpenses->where('type', $this->supply_order_type);
        }

        if (!empty($this->supplier_id)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supplier_id', $this->supplier_id);
        }

        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getPurchaseReceipts(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId): array
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = PurchaseReceipt::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsItemExpenses = $assetsItemExpenses->where('branch_id', $branchId);
        }

        if (!empty($this->supplier_id)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supplier_id', $this->supplier_id);
        }

        if (!empty($this->supply_order_number)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supply_order_id', $this->supply_order_number);
        }


        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getPurchaseInvoices(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = PurchaseInvoice::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsItemExpenses = $assetsItemExpenses->where('branch_id', $branchId);
        }

        if (!empty($this->supplier_id)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supplier_id', $this->supplier_id);
        }

        if (!empty($this->supplier_id)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supplier_id', $this->supplier_id);
        }

        if (!empty($this->supply_order_number)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supply_order_id', $this->supply_order_number);
        }

        if (!empty($this->invoice_type)) {
            $assetsItemExpenses = $assetsItemExpenses->where('invoice_type', $this->invoice_type);
        }

        if (!empty($this->type) && $this->type != 'together') {
            $assetsItemExpenses = $assetsItemExpenses->where('type', $this->type);
        }

        if (!empty($this->type) && $this->type == 'together') {
            $assetsItemExpenses = $assetsItemExpenses->whereIn('type', ['credit', 'cash']);
        }

        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }


    private function getPurchaseReturns(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = PurchaseReturn::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsItemExpenses = $assetsItemExpenses->where('branch_id', $branchId);
        }

        if (!empty($this->supply_order_number)) {
            $assetsItemExpenses->whereHas('supplyOrders', function ($q)  {
                $q->where('supply_order_id', $this->supply_order_number);
            });
        }

        if (!empty($this->type) && $this->type != 'together') {
            $assetsItemExpenses = $assetsItemExpenses->where('type', $this->type);
        }

        if (!empty($this->type) && $this->type == 'together') {
            $assetsItemExpenses = $assetsItemExpenses->whereIn('type', ['credit', 'cash']);
        }

        if (!empty($this->supplier_id)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supplier_id', $this->supplier_id);
        }

        if (!empty($this->invoice_type)) {
            $assetsItemExpenses = $assetsItemExpenses->where('invoice_type', $this->invoice_type);
        }

        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getSupplierContacts(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = SupplierContact::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }



        if (!empty($this->supplierID)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supplier_id', $this->supplierID);
        }


        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }


    private function getBankAccounts(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = BankAccount::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }



        if (!empty($this->supplierID)) {
            $assetsItemExpenses = $assetsItemExpenses->where('supplier_id', $this->supplierID);
        }


        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }


    private function getMaintenanceCenters(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId)
    {
        $withNoNull = $selectedColumns;
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = MaintenanceCenter::select(DB::raw($selectedColumns));
        if ($withNoNull == 'phone_1') {
            $assetsItemExpenses = $assetsItemExpenses->where($withNoNull, '!=', null);
        }
        if ($withNoNull == 'commercial_number') {
            $assetsItemExpenses = $assetsItemExpenses->where($withNoNull, '!=', null);
        }

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->whereNotNull($searchField)->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $assetsItemExpenses = $assetsItemExpenses->where('branch_id', $branchId);
        }

        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getMaintenanceDetectionType(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = MaintenanceDetectionType::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getMaintenanceDetection(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId): array
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = MaintenanceDetection::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($this->maintenance_detection_type_id_select2)) {
            $assetsItemExpenses = $assetsItemExpenses->where('maintenance_type_id', $this->maintenance_detection_type_id_select2);
        }
        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }


    private function getAssetMaintenance(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId): array
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $assetsItemExpenses = AssetMaintenance::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $assetsItemExpenses = $assetsItemExpenses->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }

        if (!empty($this->asset_id_select_2)) {
            $assetsItemExpenses = $assetsItemExpenses->where('asset_id', $this->asset_id_select_2);
        }
        $assetsItemExpenses = $assetsItemExpenses->limit($limit)->get();
        foreach ($assetsItemExpenses as $assetsItemExpense) {
            $data[] = [
                'id' => $assetsItemExpense->id,
                'text' => $this->buildSelectedColumnsAsText($assetsItemExpense, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getLockers(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = Locker::select(DB::raw($selectedColumns));
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $items = $items->where('branch_id', $branchId);
        }
        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }


    private function getLockerOpeningBalance(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = LockerOpeningBalance::select(DB::raw($selectedColumns));
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $items = $items->where('branch_id', $branchId);
        }
        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getBankOfficials(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = BankOfficial::select(DB::raw($selectedColumns));
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($this->bank_data_id)) {
            $items = $items->where('bank_data_id', $this->bank_data_id);
        }
        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getBankCommissioner(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = BankCommissioner::select(DB::raw($selectedColumns));
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($this->bank_data_id)) {
            $items = $items->where('bank_data_id', $this->bank_data_id);
        }
        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getCountries(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = Country::select(DB::raw($selectedColumns));
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getCities(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = City::select(DB::raw($selectedColumns));
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($this->country_id)) {
            $items = $items->where('country_id', $this->country_id);
        }
        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getAreas(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = Area::select(DB::raw($selectedColumns));
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($this->city_id)) {
            $items = $items->where('city_id', $this->city_id);
        }
        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getBankData(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $withNoNull = $selectedColumns;
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = BankData::select(DB::raw($selectedColumns));
        if ($withNoNull == 'code') {
            $items = $items->where($withNoNull, '!=', null);
        }
        if ($withNoNull == 'swift_code') {
            $items = $items->where($withNoNull, '!=', null);
        }
        if ($withNoNull == 'branch') {
            $items = $items->where($withNoNull, '!=', null);
        }

        if ($withNoNull == 'phone') {
            $items = $items->where($withNoNull, '!=', null);
        }

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $items = $items->where('branch_id', $branchId);
        }

        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getBranchProduct(array $searchFields, string $searchTerm, string $selectedColumns, int $limit, string $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = BranchProduct::select(DB::raw($selectedColumns));
        if (!empty($this->bank_data_id)) {
            $bankData = BankData::find($this->bank_data_id);
            $items = $bankData->products;
        }

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $items = $items->where('branch_id', $branchId);
        }

        if (!empty($this->bank_data_id)) {
            $items = $items->where('id', $this->bank_data_id);
        }
        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getBankAccount(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId): array
    {

        $withNoNull = $selectedColumns;
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = BAC::select(DB::raw($selectedColumns));
        if ($withNoNull == 'iban') {
            $items = $items->where($withNoNull, '!=', null);
        }

        if ($withNoNull == 'customer_number') {
            $items = $items->where($withNoNull, '!=', null);
        }
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $items = $items->where('branch_id', $branchId);
        }

        if (!empty($this->bank_data_id)) {
            $items = $items->where('bank_data_id', $this->bank_data_id);
        }
        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getOpeningBalanceAccount(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId)
    {
        $data = [];
        $id = ' id ,';
        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }
        $items = OpeningBalanceAccount::select(DB::raw($selectedColumns));
        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }
        if (!empty($branchId)) {
            $items = $items->where('branch_id', $branchId);
        }

        $items = $items->limit($limit)->get();
        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getSaleQuotations(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId)
    {
        $data = [];

        $id = ' id ,';

        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }

        $items = SaleQuotation::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }

        if (!empty($branchId)) {
            $items = $items->where('branch_id', $branchId);
        }

        if (!empty($this->type_of_purchase_quotation)) {
            $items = $items->where('type', $this->type_of_purchase_quotation);
        }

        if (!empty($this->quotation_type)) {
            if ($this->quotation_type == 'cash_credit') {
                $items = $items->whereIn('type', ['credit', 'cash']);
            } else {
                $items = $items->where('type', $this->quotation_type);
            }

        }

        $items = $items->limit($limit)->get();

        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }

    private function getCustomers(array $searchFields, $searchTerm, $selectedColumns, $limit, $branchId)
    {
        $data = [];

        $id = ' id ,';

        if ($selectedColumns != '' && $selectedColumns != '*') {
            $selectedColumns = $id . ' ' . $selectedColumns;
        }

        $items = Customer::select(DB::raw($selectedColumns));

        if (!empty($searchFields)) {
            foreach ($searchFields as $searchField) {
                if (!empty($searchTerm) && $searchTerm != '') {
                    $items = $items->where($searchField, 'like', '%' . $searchTerm . '%');
                }
            }
        }

        if (!empty($branchId)) {
            $items = $items->where('branch_id', $branchId);
        }

        $items = $items->limit($limit)->get();

        foreach ($items as $item) {
            $data[] = [
                'id' => $item->id,
                'text' => $this->buildSelectedColumnsAsText($item, $selectedColumns)
            ];
        }
        return $data;
    }



    private function buildSelectedColumnsAsText($resource, $selectedColumns = ['name'])
    {
        $text = '';

        $columns = $this->convertSelectedColumnsFromStringToArray($selectedColumns);

        $prefix = '';

        // to skip prefix from the one column an also skip if columns has id
        if (count($columns) > 2) {
            $prefix = ' - ';
        }

        $isFirst = true;

        if ($selectedColumns != '*') {
            foreach ($columns as $key => $columnName) {
                if ($columnName != 'id') {
                    if (!$isFirst) {
                        $text .= $prefix;
                    }

                    $text .= $resource->$columnName;

                    $isFirst = false;
                }
            }
        }

        return $text;
    }

    private function convertSelectedColumnsFromStringToArray($selectedColumns)
    {
        $data = [];

        $columns = $selectedColumns;

        if (!is_array($selectedColumns)) {
            $columns = explode(',', $columns);
        }

        foreach ($columns as $key => $column) {
            $data[] = str_replace(' ', '', $column);
        }

        return $data;
    }
}
