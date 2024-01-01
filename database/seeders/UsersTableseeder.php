<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class UsersTableseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // Admin
            [
            'name'=>'Admin',
            'username'=>'admin',
            'email'=>'Admin@mail.com',
            'password'=>Hash::make('zxcvbnm,'),
            'role'=>'Admin',
            'status'=>'active'
            ],
                // Vendor
            [
                'name'=>'vendor',
                'username'=>'vendor',
                'email'=>'vendor@mail.com',
                'password'=>Hash::make('zxcvbnm,'),
                'role'=>'vendor',
                'status'=>'active'
            ],
                // user
            [
                'name'=>'user',
                'username'=>'user',
                'email'=>'user@mail.com',
                'password'=>Hash::make('zxcvbnm,'),
                'role'=>'user',
                'status'=>'active'
            ],
        ]);
    }
}
