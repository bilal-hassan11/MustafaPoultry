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
                'username'           => 'admin',
                'email'          => 'admin1421@admin.com',
                'password'       => bcrypt('password'),
                'user_type'       => 1,
                'remember_token' => '',
            ],
        ];

        Admin::insert($users);
    }
}