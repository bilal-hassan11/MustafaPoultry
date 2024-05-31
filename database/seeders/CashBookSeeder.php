<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\carbon;

class CashBookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entries = [];

        // Generating 10 entries
        for ($i = 1; $i <= 10; $i++) {
            $entryType = $i % 2 == 0 ? 'payment' : 'receipt';

            $entries[] = [
                'entry_date' => now(),
                'account_id' => $i,
                'narration' => 'Transaction ' . $i . ' description',
                'bil_no' => 1000 + $i,
                'payment_ammount' => $entryType == 'payment' ? rand(100, 1000) : null,
                'receipt_ammount' => $entryType == 'receipt' ? rand(100, 1000) : null,
                'status' => $entryType,
                'remarks' => 'Remarks for transaction ' . $i,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('cash_book')->insert($entries);
    }
}
