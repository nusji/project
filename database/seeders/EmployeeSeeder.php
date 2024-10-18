<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        DB::table('employees')->insert([
            'name' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('12345678'), // Make sure to hash the password
            'role' => 'owner', // Set role to admin
            'id_card_number' => '1234567890123',
            'phone_number' => '0123456789',
            'employment_type' => 'พนักงานประจำ',
            'salary' => 30000,
            'start_date' => now(),
            'address' => '123 Admin St, Admin City, Admin Country',
            'date_of_birth' => '1980-01-01',
            'profile_picture' => 'path/to/profile_picture.jpg',
            'bank_account' => 'Bank of Admin',
            'bank_account_number' => '0123456789',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
