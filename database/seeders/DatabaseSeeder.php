<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminTableSeeder::class,
            MedicineInvoicesTableSeeder::class,
            FeedInvoicesTableSeeder::class,
            ChickInvoicesTableSeeder::class,
            MurghiInvoicesTableSeeder::class,
            CashBookSeeder::class,
            ItemsTableSeeder::class,
            AccountTypesTableSeeder::class,
            CategoriesTableSeeder::class,
            AccountsTableSeeder::class,
        ]);
    }
}