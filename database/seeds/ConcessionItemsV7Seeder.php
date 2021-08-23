<?php

use Illuminate\Database\Seeder;

class ConcessionItemsV7Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            'ReturnedSaleReceipt' => [

                'name'=>'Returned Sale Receipt',
                'model'=>'ReturnedSaleReceipt',
                'type'=>'add',
            ],
        ];

        foreach ($data as $item) {

            \App\Models\ConcessionTypeItem::create($item);
        }
    }
}
