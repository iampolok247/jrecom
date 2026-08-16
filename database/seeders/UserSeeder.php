<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['role' => 'admin'],
            [
                'name' => 'Sami Admin',
                'email' => 'sami@ravelis.online',
                'phone' => '+8801700000000',
                'role' => 'admin',
                'status' => true,
                'address' => 'Gulshan Avenue, Dhaka-1212',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'zip' => '1212',
                'country' => 'Bangladesh',
                'password' => Hash::make('SamiR!@145#$'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'customer@jrecom.com'],
            [
                'name' => 'Jane Customer',
                'email' => 'customer@jrecom.com',
                'phone' => '+8801811112222',
                'role' => 'customer',
                'status' => true,
                'address' => 'Dhanmondi 27, Dhaka',
                'city' => 'Dhaka',
                'state' => 'Dhaka',
                'zip' => '1209',
                'country' => 'Bangladesh',
                'password' => Hash::make('password123'),
            ]
        );
    }
}
