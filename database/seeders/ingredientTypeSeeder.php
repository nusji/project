<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ingredientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ingredient_types')->insert([
            ['ingredient_type_name' => 'เนื้อสัตว์', 'ingredient_type_detail' => 'เนื้อไก่, เนื้อหมู, เนื้อวัว, เนื้อปลา, กุ้ง, ปลาหมึก'],
            ['ingredient_type_name' => 'เครื่องเทศ', 'ingredient_type_detail' => 'กระเทียม, หอมแดง, ตะไคร้, ใบมะกรูด, ข่า, ขิง'],
            ['ingredient_type_name' => 'พริกแกง', 'ingredient_type_detail' => 'พริกแกงเขียวหวาน, พริกแกงแดง, พริกแกงส้ม, พริกแกงมัสมั่น'],
            ['ingredient_type_name' => 'เครื่องปรุงรส', 'ingredient_type_detail' => 'น้ำปลา, กะปิ, น้ำตาลปี๊บ, น้ำมะขามเปียก, มะนาว'],
            ['ingredient_type_name' => 'กะทิ', 'ingredient_type_detail' => 'กะทิสด, กะทิกระป๋อง'],
        ]);
    }
}
