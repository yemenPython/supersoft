<?php

namespace App\Observers;

use App\Models\Banks\TypeBankAccount;
use App\Models\Branch;

class BranchObserver
{
    public function created(Branch $branch)
    {
        $typeBankAccount = TypeBankAccount::create([
            'branch_id' => $branch->id,
            'name_ar' => 'حسابات جارية',
            'name_en' => 'حسابات جارية',
            'parent_id' => null,
            'status' => 1,
        ]);
          TypeBankAccount::create([
            'branch_id' => $branch->id,
            'name_ar' => 'حسابات ودائع وشهادات',
            'name_en' => 'حسابات ودائع وشهادات',
            'parent_id' => null,
            'status' => 1,
        ]);

        $children = [
            'child1' => [
                'branch_id' => $branch->id,
                'name_ar' => 'حسابات جارية دائنة',
                'name_en' => 'حسابات جارية دائنة',
                'parent_id' => $typeBankAccount->id,
                'status' => 1,
            ],
            'child2' => [
                'branch_id' => $branch->id,
                'name_ar' => 'حسابات جارية مدينة',
                'name_en' => 'حسابات جارية مدينة',
                'parent_id' => $typeBankAccount->id,
                'status' => 1,
            ],
        ];
        foreach ($children as $child) {
            TypeBankAccount::create($child);
        }
    }

    public function deleted(Branch $branch)
    {
        TypeBankAccount::where('branch_id', $branch->id)->delete();
    }
}
