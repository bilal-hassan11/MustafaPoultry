<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\carbon;
use DB;

class AccountTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_types')->insert([
            ['id' => 3, 'parent_id' => null, 'name' => 'Arti', 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'parent_id' => null, 'name' => 'Farmer', 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'parent_id' => null, 'name' => 'Chicks Companies', 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'parent_id' => null, 'name' => 'Feed Companies', 'created_at' => null, 'updated_at' => null],
            ['id' => 7, 'parent_id' => null, 'name' => 'Bank Accounts', 'created_at' => null, 'updated_at' => null],
            ['id' => 13, 'parent_id' => null, 'name' => 'Office Staff Salary Accounts', 'created_at' => null, 'updated_at' => null],
            ['id' => 22, 'parent_id' => 3, 'name' => 'Add Arti Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 23, 'parent_id' => 4, 'name' => 'Add Farmer Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 24, 'parent_id' => 5, 'name' => 'Add Chicks Companies Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 25, 'parent_id' => 6, 'name' => 'Add Feed Companies Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 28, 'parent_id' => 7, 'name' => 'Add Bank Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 34, 'parent_id' => 13, 'name' => 'Add Office Staff Salary Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 51, 'parent_id' => null, 'name' => 'Medicine Companies', 'created_at' => null, 'updated_at' => null],
            ['id' => 52, 'parent_id' => 51, 'name' => 'Add Medicine Companies Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 53, 'parent_id' => null, 'name' => 'Zakat & Dobat Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 54, 'parent_id' => 53, 'name' => 'Add Zakat & Dobat Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 55, 'parent_id' => null, 'name' => 'Tamirati Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 56, 'parent_id' => 55, 'name' => 'Add Tamirati Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 57, 'parent_id' => null, 'name' => 'Others Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 58, 'parent_id' => 57, 'name' => 'Add Others Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 59, 'parent_id' => null, 'name' => 'Capital Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 60, 'parent_id' => 59, 'name' => 'Add Capital Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 61, 'parent_id' => null, 'name' => 'Kameti Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 62, 'parent_id' => 61, 'name' => 'Add Kameti Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 63, 'parent_id' => null, 'name' => 'Expense Account', 'created_at' => null, 'updated_at' => null],
            ['id' => 64, 'parent_id' => 63, 'name' => 'Add Expense Account', 'created_at' => null, 'updated_at' => null],
        ]);

    }
}
