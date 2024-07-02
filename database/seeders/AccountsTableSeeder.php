<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\carbon;
use DB;
class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = [
            ['id' => 1, 'grand_parent_id' => 57, 'parent_id' => 58, 'name' => 'Adjustment Account', 'opening_balance' => 0.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => '', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '16', '38', '30'), 'updated_at' => Carbon::create('2024', '03', '10', '21', '03', '32')],
            
        ];
        
        DB::table('accounts')->insert($accounts);
    }
}
