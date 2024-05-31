<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            [
                'category_id' => 1,
                'company_id' => 1,
                'name' => 'Item 1',
                'unit' => 'kg',
                'primary_unit' => 'kg',
                'discount' => 10,
                'price' => 100,
                'purchase_price' => 90,
                'sale_price' => 110,
                'type' => 'sale',
                'stock_status' => 1,
                'status' => 1,
                'opening_stock' => 50,
                'stock_qty' => 50,
                'remarks' => 'Remark for item 1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'category_id' => 2,
                'company_id' => 2,
                'name' => 'Item 2',
                'unit' => 'liters',
                'primary_unit' => 'liters',
                'discount' => 5,
                'price' => 200,
                'purchase_price' => 180,
                'sale_price' => 220,
                'type' => 'sale',
                'stock_status' => 1,
                'status' => 1,
                'opening_stock' => 100,
                'stock_qty' => 100,
                'remarks' => 'Remark for item 2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
