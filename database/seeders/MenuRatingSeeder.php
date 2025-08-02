<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MenuRating;

class MenuRatingSeeder extends Seeder
{
    public function run()
    {
        // สร้าง 100 records ด้วย factory
        MenuRating::factory()->count(100)->create();
    }
}