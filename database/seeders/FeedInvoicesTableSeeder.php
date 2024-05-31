<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\carbon;

class FeedInvoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [];
        
        for ($i = 1; $i <= 10; $i++) {
            $records[] = [
                'date' => now(),
                'invoice_no' => 2405000 + $i,
                'account_id' => $i,
                'ref_no' => 'REF' . (1000 + $i),
                'description' => 'Test invoice ' . $i,
                'item_id' => $i,
                'purchase_price' => rand(100, 500) + rand(0, 99) / 100,
                'sale_price' => rand(100, 500) + rand(0, 99) / 100,
                'quantity' => rand(1, 20) + rand(0, 99) / 100,
                'amount' => rand(1000, 5000) + rand(0, 99) / 100,
                'discount_in_rs' => rand(10, 100) + rand(0, 99) / 100,
                'discount_in_percent' => rand(0, 100) / 10,
                'net_amount' => rand(1000, 5000) + rand(0, 99) / 100,
                'type' => ['Purchase', 'Sale', 'Purchase Return', 'Sale Return', 'Adjust In', 'Adjust Out'][array_rand(['Purchase', 'Sale', 'Purchase Return', 'Sale Return', 'Adjust In', 'Adjust Out'])],
                'stock_type' => ['In', 'Out'][array_rand(['In', 'Out'])],
                'whatsapp_status' => ['Sent', 'Not Sent'][array_rand(['Sent', 'Not Sent'])],
                'remarks' => 'Remarks for invoice ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('feed_invoices')->insert($records);
    }
}
