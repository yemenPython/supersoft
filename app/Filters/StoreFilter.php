<?php

namespace App\Filters;

use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class StoreFilter
{
    public function filter(Request $request): Builder
    {
        return Store::where(function ($query) use ($request) {
            if ($request->has('branch_id') && $request->branch_id != '' && $request->branch_id != null) {
                $query->where('branch_id', $request->branch_id);
            }

            if ($request->has('store_id') && $request->store_id != '' && $request->store_id != null) {
                $query->where('id', $request->store_id);
            }

            if ($request->has('employee') && $request->employee != '' && $request->employee != null) {
                $employeeId = $request->employee;
                $query->whereHas('storeEmployeeHistories', function ($q) use ($employeeId) {
                    $q->where('employee_id', $employeeId);
                });
            }
        });
    }
}
