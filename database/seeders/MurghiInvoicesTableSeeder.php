<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\carbon;

class MurghiInvoicesTableSeeder extends Seeder
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
            $purchasePrice = rand(50, 200) + rand(0, 99) / 100;
            $salePrice = rand(100, 300) + rand(0, 99) / 100;
            $quantity = rand(1, 50) + rand(0, 99) / 100;
            $amount = $quantity * $salePrice;
            $weightDetection = rand(1, 10) + rand(0, 99) / 100;
            $finalWeight = $quantity - $weightDetection;
            $netAmount = $finalWeight * $salePrice;

            $records[] = [
                'date' => now(),
                'invoice_no' => 2405000 + $i,
                'account_id' => $i,
                'ref_no' => 'REF' . (4000 + $i),
                'description' => 'Murghi invoice ' . $i,
                'item_id' => $i,
                'purchase_price' => $purchasePrice,
                'sale_price' => $salePrice,
                'quantity' => $quantity,
                'amount' => $amount,
                'weight_detection' => $weightDetection,
                'weight' => $finalWeight,
                'net_amount' => $netAmount,
                'type' => ['Purchase', 'Sale', 'Purchase Return', 'Sale Return', 'Adjust In', 'Adjust Out'][array_rand(['Purchase', 'Sale', 'Purchase Return', 'Sale Return', 'Adjust In', 'Adjust Out'])],
                'stock_type' => ['In', 'Out'][array_rand(['In', 'Out'])],

                'whatsapp_status' => ['Sent', 'Not Sent'][array_rand(['Sent', 'Not Sent'])],
                'remarks' => 'Remarks for invoice ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('murghi_invoices')->insert($records);
    }
}
