<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        DB::table('user_admin')->insert([
            [
                'role_id' => 1, // admin gudang
                'email' => 'gudang@gmail.com',
                'password' => Hash::make('password123')
            ],
            [
                'role_id' => 2, // admin keuangan
                'email' => 'keuangan@gmail.com',
                'password' => Hash::make('password123')
            ]
        ]);
    }
}