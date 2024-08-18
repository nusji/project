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
            ['ingredient_name' => 'เนื้อไก่', 'ingredient_type_id' => 19, 'ingredient_unit' => 'กรัม', 'ingredient_quantity' => 500],
            ['ingredient_name' => 'เนื้อหมู', 'ingredient_type_id' => 19, 'ingredient_unit' => 'กรัม', 'ingredient_quantity' => 500],
            ['ingredient_name' => 'เนื้อวัว', 'ingredient_type_id' => 19, 'ingredient_unit' => 'กรัม', 'ingredient_quantity' => 500],
            ['ingredient_name' => 'เนื้อปลา', 'ingredient_type_id' => 19, 'ingredient_unit' => 'กรัม', 'ingredient_quantity' => 500],
            ['ingredient_name' => 'กุ้ง', 'ingredient_type_id' => 19, 'ingredient_unit' => 'กรัม', 'ingredient_quantity' => 500],

            // เครื่องเทศ
            ['ingredient_name' => 'กระเทียม', 'ingredient_type_id' => 20, 'ingredient_unit' => 'กลีบ', 'ingredient_quantity' => 10],
            ['ingredient_name' => 'หอมแดง', 'ingredient_type_id' => 20, 'ingredient_unit' => 'หัว', 'ingredient_quantity' => 5],
            ['ingredient_name' => 'ตะไคร้', 'ingredient_type_id' => 20, 'ingredient_unit' => 'ต้น', 'ingredient_quantity' => 3],
            ['ingredient_name' => 'ใบมะกรูด', 'ingredient_type_id' => 20, 'ingredient_unit' => 'ใบ', 'ingredient_quantity' => 10],
            ['ingredient_name' => 'ข่า', 'ingredient_type_id' => 20, 'ingredient_unit' => 'ชิ้น', 'ingredient_quantity' => 3],

            // พริกแกง
            ['ingredient_name' => 'พริกแกงเขียวหวาน', 'ingredient_type_id' => 21, 'ingredient_unit' => 'ถ้วย', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'พริกแกงแดง', 'ingredient_type_id' => 21, 'ingredient_unit' => 'ถ้วย', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'พริกแกงส้ม', 'ingredient_type_id' => 21, 'ingredient_unit' => 'ถ้วย', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'พริกแกงมัสมั่น', 'ingredient_type_id' => 21, 'ingredient_unit' => 'ถ้วย', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'พริกแกงเผ็ด', 'ingredient_type_id' => 21, 'ingredient_unit' => 'ถ้วย', 'ingredient_quantity' => 1],

            // เครื่องปรุงรส
            ['ingredient_name' => 'น้ำปลา', 'ingredient_type_id' => 22, 'ingredient_unit' => 'ขวด', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'กะปิ', 'ingredient_type_id' => 22, 'ingredient_unit' => 'ช้อนโต๊ะ', 'ingredient_quantity' => 2],
            ['ingredient_name' => 'น้ำตาลปี๊บ', 'ingredient_type_id' => 22, 'ingredient_unit' => 'ก้อน', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'น้ำมะขามเปียก', 'ingredient_type_id' => 22, 'ingredient_unit' => 'ขวด', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'มะนาว', 'ingredient_type_id' => 22, 'ingredient_unit' => 'ลูก', 'ingredient_quantity' => 5],

            // กะทิ
            ['ingredient_name' => 'กะทิสด', 'ingredient_type_id' => 23, 'ingredient_unit' => 'ขวด', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'กะทิกระป๋อง', 'ingredient_type_id' => 23, 'ingredient_unit' => 'กระป๋อง', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'กะทิสำเร็จรูป', 'ingredient_type_id' => 23, 'ingredient_unit' => 'กล่อง', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'กะทิแบบผง', 'ingredient_type_id' => 23, 'ingredient_unit' => 'ซอง', 'ingredient_quantity' => 1],
            ['ingredient_name' => 'กะทิกล่อง', 'ingredient_type_id' => 23, 'ingredient_unit' => 'กล่อง', 'ingredient_quantity' => 1],
        ]);
    }
}
