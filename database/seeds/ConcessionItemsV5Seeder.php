<?php

use Illuminate\Database\Seeder;

class ConcessionItemsV5Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            'PurchaseReturn' => [

                'name'=>'Purchase Return',
                'model'=>'PurchaseReturn',
                'type'=>'withdrawal',
            ],
        ];

        foreach ($data as $item) {

            \App\Models\ConcessionTypeItem::create($item);
        }
    }
}
