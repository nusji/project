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
            'first_name' => 'John',
            'last_name' => 'Doe',
            'username' => 'admin',
            'password' => Hash::make('12345678'), // Make sure to hash the password
            'email' => 'admin@example.com',
            'role' => 'admin', // Set role to admin
            'id_card_number' => '1234567890123',
            'id_card_image' => 'path/to/id_card_image.jpg',
            'phone_number' => '0123456789',
            'employment_status' => 'permanent',
            'start_date' => now(),
            'address' => '123 Admin St, Admin City, Admin Country',
            'date_of_birth' => '1980-01-01',
            'profile_picture' => 'path/to/profile_picture.jpg',
            'previous_experience' => '5 years of experience in management.',
            'relevant_education' => 'Bachelor\'s degree in Business Administration.',
            'bank_account' => 'Bank of Admin',
            'bank_account_number' => '0123456789',
            'emergency_contact' => 'Jane Doe - 0987654321',
            'health_info' => 'No known health issues.',
            'religion' => 'Religion',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
