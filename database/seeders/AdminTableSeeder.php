<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1412,
                'first_name'           => 'Admin',
                'username'           => 'admin1412',
                'email'          => 'admin1421@admin.com',
                'password'       => bcrypt('password'),
                'user_type'       => 1,
                'remember_token' => '',
            ],
        ];

        Admin::insert($users);
    }
}