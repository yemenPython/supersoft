<?php

use Illuminate\Database\Seeder;

class ConcessionItemsV6Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [

            'SalesInvoice' => [

                'name'=>'Sales Invoice',
                'model'=>'SalesInvoice',
                'type'=>'withdrawal',
            ],
        ];

        foreach ($data as $item) {

            \App\Models\ConcessionTypeItem::create($item);
        }
    }
}
