<?php

use App\Models\Branch;
use App\Models\Banks\TypeBankAccount;
use Illuminate\Database\Migrations\Migration;

class AddDefaultDataToTypesBanksAccountForAllBranches extends Migration
{
    public function up(): void
    {
        $branches = Branch::all();
        foreach ($branches as $branch) {
            TypeBankAccount::create([
                'branch_id' => $branch->id,
                'name_ar' => 'حسابات جارية',
                'name_en' => 'حسابات جارية',
                'parent_id' => null,
                'status' => 1,
            ]);
        }
        foreach ($branches as $branch) {
            $typeBankAccount = TypeBankAccount::create([
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
    }

    public function down()
    {
    }
}
