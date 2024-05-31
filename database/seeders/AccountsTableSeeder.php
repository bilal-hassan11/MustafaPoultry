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
            ['id' => 3, 'grand_parent_id' => 3, 'parent_id' => 22, 'name' => 'Usman Poineer Karachi (Arti)', 'opening_balance' => 327281.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => 'Karachi', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '16', '38', '30'), 'updated_at' => Carbon::create('2024', '03', '10', '21', '03', '32')],
            ['id' => 4, 'grand_parent_id' => 63, 'parent_id' => 64, 'name' => 'Electricity Equipment Expenses', 'opening_balance' => 0.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => 'MirpurKhas', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '20', '20', '06'), 'updated_at' => Carbon::create('2024', '03', '10', '20', '21', '54')],
            ['id' => 5, 'grand_parent_id' => 63, 'parent_id' => 64, 'name' => 'Salary Account', 'opening_balance' => 0.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => '-380,000.00', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '20', '21', '18'), 'updated_at' => Carbon::create('2024', '03', '10', '20', '21', '18')],
            ['id' => 6, 'grand_parent_id' => 3, 'parent_id' => 22, 'name' => 'Usman Poineer Karachi (Arti)', 'opening_balance' => 327281.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => 'Karachi', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '16', '38', '30'), 'updated_at' => Carbon::create('2024', '03', '10', '21', '03', '32')],
            ['id' => 7, 'grand_parent_id' => 63, 'parent_id' => 64, 'name' => 'Electricity Equipment Expenses', 'opening_balance' => 0.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => 'MirpurKhas', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '20', '20', '06'), 'updated_at' => Carbon::create('2024', '03', '10', '20', '21', '54')],
            ['id' => 8, 'grand_parent_id' => 63, 'parent_id' => 64, 'name' => 'Salary Account', 'opening_balance' => 0.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => '-380,000.00', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '20', '21', '18'), 'updated_at' => Carbon::create('2024', '03', '10', '20', '21', '18')],
            ['id' => 9, 'grand_parent_id' => 3, 'parent_id' => 22, 'name' => 'Usman Poineer Karachi (Arti)', 'opening_balance' => 327281.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => 'Karachi', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '16', '38', '30'), 'updated_at' => Carbon::create('2024', '03', '10', '21', '03', '32')],
            ['id' => 10, 'grand_parent_id' => 63, 'parent_id' => 64, 'name' => 'Electricity Equipment Expenses', 'opening_balance' => 0.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => 'MirpurKhas', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '20', '20', '06'), 'updated_at' => Carbon::create('2024', '03', '10', '20', '21', '54')],
            ['id' => 11, 'grand_parent_id' => 63, 'parent_id' => 64, 'name' => 'Salary Account', 'opening_balance' => 0.000, 'opening_date' => '2024-01-01', 'account_nature' => 'debit', 'ageing' => 0, 'commission' => 0, 'discount' => 0, 'address' => '-380,000.00', 'phone_no' => NULL,  'status' => 1, 'created_at' => Carbon::create('2024', '03', '10', '20', '21', '18'), 'updated_at' => Carbon::create('2024', '03', '10', '20', '21', '18')],
            
        ];

        DB::table('accounts')->insert($accounts);
    }
}
