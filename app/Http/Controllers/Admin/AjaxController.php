<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\AssetExpense;
use App\Models\AssetGroup;
use App\Models\AssetsItemExpense;
use App\Models\AssetsTypeExpense;
use App\Models\DamagedStock;
use App\Models\EmployeeData;
use App\Models\OpeningBalance;
use App\Models\Part;
use App\Models\Settlement;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\Request;
use DB;

class AjaxController extends Controller
{
    protected $storeId;
    protected $partId;
    protected $serialNumber;
    protected $asset_group_name;
    protected $asset_expense_type;

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
