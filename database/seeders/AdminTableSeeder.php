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
                'id'             => 1,
                'name'           => 'Admin',
                'username'           => 'admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('password'),
                'user_type'       => 1,
                'remember_token' => '',
            ],
        ];

        Admin::insert($users);
    }
}