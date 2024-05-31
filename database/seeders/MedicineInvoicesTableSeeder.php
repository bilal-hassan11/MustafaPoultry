<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\carbon;

class MedicineInvoicesTableSeeder extends Seeder
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
                'ref_no' => 'REF' . (2000 + $i),
                'description' => 'Medicine invoice ' . $i,
                'item_id' => $i,
                'purchase_price' => rand(50, 200) + rand(0, 99) / 100,
                'sale_price' => rand(100, 300) + rand(0, 99) / 100,
                'quantity' => rand(1, 50) + rand(0, 99) / 100,
                'amount' => rand(1000, 5000) + rand(0, 99) / 100,
                'discount_in_rs' => rand(0, 100) + rand(0, 99) / 100,
                'discount_in_percent' => rand(0, 20) + rand(0, 99) / 100,
                'net_amount' => rand(1000, 5000) + rand(0, 99) / 100,
                'expiry_date' => now()->addMonths(rand(1, 24)),
                'type' => ['Purchase', 'Sale', 'Purchase Return', 'Sale Return', 'Adjust In', 'Adjust Out'][array_rand(['Purchase', 'Sale', 'Purchase Return', 'Sale Return', 'Adjust In', 'Adjust Out'])],
                'stock_type' => ['In', 'Out'][array_rand(['In', 'Out'])],
                
                'whatsapp_status' => ['Sent', 'Not Sent'][array_rand(['Sent', 'Not Sent'])],
                'remarks' => 'Remarks for invoice ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('medicine_invoices')->insert($records);
    }
}
