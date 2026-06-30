<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Carbon\carbon;
use DB;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1412,
                'first_name'     => 'Super',
                'last_name'      => 'Admin',
                'username'       => 'admin',
                'email'          => 'admin1421@admin.com',
                'password'       => bcrypt('password'),
                'user_type'      => 'admin',
                'is_active'      => 1,
                'remember_token' => '',
            ],
        ];

        Admin::insert($users);
    }
}