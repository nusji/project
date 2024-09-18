<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ingredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ingredients')->insert([
            // เนื้อสัตว์
            ['ingredient_name' => 'เนื้อไก่', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 5, 'minimum_quantity' => 5],
            ['ingredient_name' => 'เนื้อหมู', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 5, 'minimum_quantity' => 5],
            ['ingredient_name' => 'เนื้อวัว', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 5, 'minimum_quantity' => 5],
            ['ingredient_name' => 'เนื้อปลา', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 5, 'minimum_quantity' => 5],
            ['ingredient_name' => 'กุ้ง', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 5, 'minimum_quantity' => 5],

            // เครื่องเทศ
            ['ingredient_name' => 'กระเทียม', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10, 'minimum_quantity' => 5],
            ['ingredient_name' => 'หอมแดง', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 5, 'minimum_quantity' => 5],
            ['ingredient_name' => 'ตะไคร้', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 3, 'minimum_quantity' => 5],
            ['ingredient_name' => 'ใบมะกรูด', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10, 'minimum_quantity' => 5],
            ['ingredient_name' => 'ข่า', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 3, 'minimum_quantity' => 5],

            // พริกแกง
            ['ingredient_name' => 'พริกแกงเขียวหวาน', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 1, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงแดง', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 1, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงส้ม', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 1, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงมัสมั่น', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 1, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงเผ็ด', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 1, 'minimum_quantity' => 5],

            // เครื่องปรุงรส
            ['ingredient_name' => 'น้ำปลา', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 1, 'minimum_quantity' => 5],
            ['ingredient_name' => 'กะปิ', 'ingredient_type_id' => 4, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 2, 'minimum_quantity' => 5],
            ['ingredient_name' => 'น้ำตาลปี๊บ', 'ingredient_type_id' => 4, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 1, 'minimum_quantity' => 5],
            ['ingredient_name' => 'น้ำมะขามเปียก', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 1, 'minimum_quantity' => 5],
            ['ingredient_name' => 'มะนาว', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 5, 'minimum_quantity' => 5],

        ]);
    }
}
