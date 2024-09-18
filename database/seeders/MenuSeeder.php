<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Insert menus
        DB::table('menus')->insert([
            // ทอด
            ['menu_name' => 'ไก่ทอดกระเทียมพริกไทย', 'menu_detail' => 'ไก่ทอดกรอบกับกระเทียมและพริกไทย', 'menu_type_id' => 1, 'menu_price' => 50.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'หมูทอดน้ำปลา', 'menu_detail' => 'หมูทอดกรอบราดน้ำปลา', 'menu_type_id' => 1, 'menu_price' => 55.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'ปลาทอดขมิ้น', 'menu_detail' => 'ปลาทอดกรอบกับเครื่องเทศ', 'menu_type_id' => 1, 'menu_price' => 60.00, 'menu_status' => true, 'menu_image' => null],

            // ผัด
            ['menu_name' => 'ผัดเผ็ดเนื้อ', 'menu_detail' => 'ผัดเผ็ดเนื้อกับพริกแกงเผ็ด', 'menu_type_id' => 2, 'menu_price' => 65.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'ผัดกระเพราหมู', 'menu_detail' => 'หมูผัดกระเพราใส่กระเทียมและพริก', 'menu_type_id' => 2, 'menu_price' => 50.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'ผัดฉ่ากุ้ง', 'menu_detail' => 'กุ้งผัดฉ่ารสจัดจ้าน', 'menu_type_id' => 2, 'menu_price' => 70.00, 'menu_status' => true, 'menu_image' => null],

            // แกง
            ['menu_name' => 'แกงเขียวหวานไก่', 'menu_detail' => 'แกงเขียวหวานไก่ใส่กะทิและพริกแกงเขียวหวาน', 'menu_type_id' => 3, 'menu_price' => 60.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'แกงส้มปลาชะอม', 'menu_detail' => 'แกงส้มใส่ปลากับชะอม', 'menu_type_id' => 3, 'menu_price' => 65.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'แกงมัสมั่นเนื้อ', 'menu_detail' => 'แกงมัสมั่นเนื้อรสเข้มข้น', 'menu_type_id' => 3, 'menu_price' => 80.00, 'menu_status' => true, 'menu_image' => null],

            // ต้ม
            ['menu_name' => 'ต้มยำกุ้ง', 'menu_detail' => 'ต้มยำกุ้งน้ำข้น', 'menu_type_id' => 4, 'menu_price' => 70.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'ต้มข่าไก่', 'menu_detail' => 'ต้มข่าไก่ใส่กะทิ', 'menu_type_id' => 4, 'menu_price' => 55.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'แกงจืดเต้าหู้หมูสับ', 'menu_detail' => 'แกงจืดใส่เต้าหู้และหมูสับ', 'menu_type_id' => 4, 'menu_price' => 45.00, 'menu_status' => true, 'menu_image' => null],

            // ยำ
            ['menu_name' => 'ยำวุ้นเส้น', 'menu_detail' => 'ยำวุ้นเส้นรสจัดจ้าน', 'menu_type_id' => 5, 'menu_price' => 55.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'ยำหมูยอ', 'menu_detail' => 'ยำหมูยอใส่พริกและน้ำมะนาว', 'menu_type_id' => 5, 'menu_price' => 50.00, 'menu_status' => true, 'menu_image' => null],
            ['menu_name' => 'ยำไข่ดาว', 'menu_detail' => 'ยำไข่ดาวทอดกรอบ', 'menu_type_id' => 5, 'menu_price' => 45.00, 'menu_status' => true, 'menu_image' => null],
        ]);

        // Insert menu recipes
        DB::table('menu_recipes')->insert([
            // ไก่ทอดกระเทียมพริกไทย
            ['menu_id' => 1, 'ingredient_id' => 27, 'Amount' => 0.5], // เนื้อไก่
            ['menu_id' => 1, 'ingredient_id' => 32, 'Amount' => 0.05], // กระเทียม
            ['menu_id' => 1, 'ingredient_id' => 8, 'Amount' => 0.02], // พริกไทยดำ

            // หมูทอดน้ำปลา
            ['menu_id' => 2, 'ingredient_id' => 2, 'Amount' => 0.5], // เนื้อหมู
            ['menu_id' => 2, 'ingredient_id' => 16, 'Amount' => 0.05], // น้ำปลา

            // ปลาทอดขมิ้น
            ['menu_id' => 3, 'ingredient_id' => 4, 'Amount' => 0.5], // ข่า
            ['menu_id' => 3, 'ingredient_id' => 7, 'Amount' => 0.05], // ใบมะกรูด
            ['menu_id' => 3, 'ingredient_id' => 8, 'Amount' => 0.05], // พริกไทยดำ

            // ผัดเผ็ดเนื้อ
            ['menu_id' => 4, 'ingredient_id' => 3, 'Amount' => 0.3], // เนื้อวัว
            ['menu_id' => 4, 'ingredient_id' => 12, 'Amount' => 0.1], // พริกแกงเผ็ด
            ['menu_id' => 4, 'ingredient_id' => 19, 'Amount' => 0.1], // กะทิ

            // ผัดกระเพราหมู
            ['menu_id' => 5, 'ingredient_id' => 2, 'Amount' => 0.3], // เนื้อหมู
            ['menu_id' => 5, 'ingredient_id' => 6, 'Amount' => 0.03], // กระเทียม
            ['menu_id' => 5, 'ingredient_id' => 7, 'Amount' => 0.02], // ใบมะกรูด

            // ผัดฉ่ากุ้ง
            ['menu_id' => 6, 'ingredient_id' => 5, 'Amount' => 0.3], // กุ้ง
            ['menu_id' => 6, 'ingredient_id' => 11, 'Amount' => 0.05], // พริกแกงฉ่า
            ['menu_id' => 6, 'ingredient_id' => 8, 'Amount' => 0.02], // พริกไทยดำ

            // แกงเขียวหวานไก่
            ['menu_id' => 7, 'ingredient_id' => 1, 'Amount' => 0.3], // เนื้อไก่
            ['menu_id' => 7, 'ingredient_id' => 11, 'Amount' => 0.1], // พริกแกงเขียวหวาน
            ['menu_id' => 7, 'ingredient_id' => 19, 'Amount' => 0.2], // กะทิ

            // แกงส้มปลาชะอม
            ['menu_id' => 8, 'ingredient_id' => 4, 'Amount' => 0.3], // ข่า
            ['menu_id' => 8, 'ingredient_id' => 1, 'Amount' => 0.3], // เนื้อปลา
            ['menu_id' => 8, 'ingredient_id' => 17, 'Amount' => 0.05], // น้ำมะขามเปียก

            // แกงมัสมั่นเนื้อ
            ['menu_id' => 9, 'ingredient_id' => 3, 'Amount' => 0.3], // เนื้อวัว
            ['menu_id' => 9, 'ingredient_id' => 14, 'Amount' => 0.1], // พริกแกงมัสมั่น
            ['menu_id' => 9, 'ingredient_id' => 19, 'Amount' => 0.2], // กะทิ

            // ต้มยำกุ้ง
            ['menu_id' => 10, 'ingredient_id' => 5, 'Amount' => 0.3], // กุ้ง
            ['menu_id' => 10, 'ingredient_id' => 6, 'Amount' => 0.03], // กระเทียม
            ['menu_id' => 10, 'ingredient_id' => 7, 'Amount' => 0.03], // ใบมะกรูด
            ['menu_id' => 10, 'ingredient_id' => 17, 'Amount' => 0.05], // น้ำมะขามเปียก

            // ต้มข่าไก่
            ['menu_id' => 11, 'ingredient_id' => 1, 'Amount' => 0.3], // เนื้อไก่
            ['menu_id' => 11, 'ingredient_id' => 4, 'Amount' => 0.05], // ข่า
            ['menu_id' => 11, 'ingredient_id' => 7, 'Amount' => 0.03], // ใบมะกรูด

            // แกงจืดเต้าหู้หมูสับ
            ['menu_id' => 12, 'ingredient_id' => 2, 'Amount' => 0.2], // เนื้อหมู
            ['menu_id' => 12, 'ingredient_id' => 15, 'Amount' => 0.1], // เต้าหู้
            ['menu_id' => 12, 'ingredient_id' => 6, 'Amount' => 0.02], // กระเทียม

            // ยำวุ้นเส้น
            ['menu_id' => 13, 'ingredient_id' => 18, 'Amount' => 0.1], // วุ้นเส้น
            ['menu_id' => 13, 'ingredient_id' => 6, 'Amount' => 0.02], // กระเทียม
            ['menu_id' => 13, 'ingredient_id' => 17, 'Amount' => 0.05], // น้ำมะขามเปียก

            // ยำหมูยอ
            ['menu_id' => 14, 'ingredient_id' => 2, 'Amount' => 0.2], // เนื้อหมู
            ['menu_id' => 14, 'ingredient_id' => 15, 'Amount' => 0.1], // หมูยอ
            ['menu_id' => 14, 'ingredient_id' => 17, 'Amount' => 0.05], // น้ำมะขามเปียก

            // ยำไข่ดาว
            ['menu_id' => 15, 'ingredient_id' => 19, 'Amount' => 0.1], // ไข่ดาว
            ['menu_id' => 15, 'ingredient_id' => 6, 'Amount' => 0.02], // กระเทียม
            ['menu_id' => 15, 'ingredient_id' => 17, 'Amount' => 0.05], // น้ำมะขามเปียก

            // เพิ่มสูตรสำหรับเมนูอื่นๆ
            // ...
        ]);
    }
}

