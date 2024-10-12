<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ingredients')->insert([
            // ประเภทที่ 1: เนื้อสัตว์ (Meat)
            ['ingredient_name' => 'เนื้อไก่', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10, 'minimum_quantity' => 5],
            ['ingredient_name' => 'เนื้อวัว', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 8, 'minimum_quantity' => 5],
            ['ingredient_name' => 'เนื้อปลา', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 7, 'minimum_quantity' => 5],
            ['ingredient_name' => 'กุ้ง', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 6, 'minimum_quantity' => 5],
            ['ingredient_name' => 'ปลาหมึก', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 5, 'minimum_quantity' => 5],
            ['ingredient_name' => 'ปลาโอ', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 4, 'minimum_quantity' => 5],
            ['ingredient_name' => 'กุ้งแม่น้ำ', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 2, 'minimum_quantity' => 2],
            ['ingredient_name' => 'เนื้อแกะ', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 2, 'minimum_quantity' => 2],
            ['ingredient_name' => 'ปลาเนื้อขาว', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 3, 'minimum_quantity' => 3],
            ['ingredient_name' => 'เนื้อเป็ด', 'ingredient_type_id' => 1, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 1, 'minimum_quantity' => 1],

            // ประเภทที่ 2: เครื่องเทศ (Spices)
            ['ingredient_name' => 'พริกไทยดำ', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 500.00, 'minimum_quantity' => 50],
            ['ingredient_name' => 'ยี่หร่า', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 300.00, 'minimum_quantity' => 30],
            ['ingredient_name' => 'กระเทียม', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 400.00, 'minimum_quantity' => 40],
            ['ingredient_name' => 'หอมแดง', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 350.00, 'minimum_quantity' => 35],
            ['ingredient_name' => 'ขิง', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 200.00, 'minimum_quantity' => 20],
            ['ingredient_name' => 'ตะไคร้', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 250.00, 'minimum_quantity' => 25],
            ['ingredient_name' => 'ข่าหั่น', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 150.00, 'minimum_quantity' => 15],
            ['ingredient_name' => 'รากผักชี', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 100.00, 'minimum_quantity' => 10],
            ['ingredient_name' => 'พริกขี้หนู', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 300.00, 'minimum_quantity' => 30],
            ['ingredient_name' => 'ใบมะกรูด', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 150.00, 'minimum_quantity' => 15],
            ['ingredient_name' => 'ใบโหระพา', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 200.00, 'minimum_quantity' => 20],
            ['ingredient_name' => 'ลูกผักชี', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 100.00, 'minimum_quantity' => 10],
            ['ingredient_name' => 'ผงกะหรี่', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 250.00, 'minimum_quantity' => 25],
            ['ingredient_name' => 'ผงชูรส', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 300.00, 'minimum_quantity' => 30],
            ['ingredient_name' => 'ใบกระวาน', 'ingredient_type_id' => 2, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 80.00, 'minimum_quantity' => 8],

            // ประเภทที่ 3: พริกแกง (Curry Paste)
            ['ingredient_name' => 'พริกแกงเขียวหวาน', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงแดง', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงส้ม', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงมัสมั่น', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงเผ็ด', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงหวาน', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงปักษ์ใต้', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงต้มยำ', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงส้มโอ', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงกะปิ', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงปลาหมึก', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงใบเตย', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงกุ้งสด', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงกะทิ', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],
            ['ingredient_name' => 'พริกแกงแดงทะเล', 'ingredient_type_id' => 3, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10.00, 'minimum_quantity' => 5],

            // ประเภทที่ 4: เครื่องปรุงรส (Seasoning)
            ['ingredient_name' => 'น้ำปลา', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 20, 'minimum_quantity' => 5],
            ['ingredient_name' => 'กะปิ', 'ingredient_type_id' => 4, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 10, 'minimum_quantity' => 5],
            ['ingredient_name' => 'น้ำตาลปี๊บ', 'ingredient_type_id' => 4, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 15, 'minimum_quantity' => 5],
            ['ingredient_name' => 'น้ำมะขามเปียก', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 10, 'minimum_quantity' => 5],
            ['ingredient_name' => 'มะนาว', 'ingredient_type_id' => 4, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 20, 'minimum_quantity' => 5],
            ['ingredient_name' => 'น้ำมันพืช', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 15, 'minimum_quantity' => 5],
            ['ingredient_name' => 'ซีอิ๊วขาว', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 10, 'minimum_quantity' => 5],
            ['ingredient_name' => 'ซีอิ๊วดำ', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 8, 'minimum_quantity' => 5],
            ['ingredient_name' => 'น้ำมันหอย', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 12, 'minimum_quantity' => 5],
            ['ingredient_name' => 'น้ำมะนาวคั้นสด', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 10, 'minimum_quantity' => 5],
            ['ingredient_name' => 'น้ำส้มสายชูขาว', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 10, 'minimum_quantity' => 5],
            ['ingredient_name' => 'น้ำมันงา', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 5, 'minimum_quantity' => 2],
            ['ingredient_name' => 'ซอสปรุงรส', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 6, 'minimum_quantity' => 3],
            ['ingredient_name' => 'น้ำผึ้ง', 'ingredient_type_id' => 4, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 5, 'minimum_quantity' => 2],
            ['ingredient_name' => 'ซอสพริก', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 8, 'minimum_quantity' => 4],
            ['ingredient_name' => 'ซอสกระเทียม', 'ingredient_type_id' => 4, 'ingredient_unit' => 'ลิตร', 'ingredient_stock' => 7, 'minimum_quantity' => 3],

            // ประเภทที่ 5: ข้าวสาร (Rice)
            ['ingredient_name' => 'ข้าวหอมมะลิ', 'ingredient_type_id' => 5, 'ingredient_unit' => 'กิโลกรัม', 'ingredient_stock' => 50, 'minimum_quantity' => 10],
        ]);
    }
}
