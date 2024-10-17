<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesAndSaleDetailsTableSeeder extends Seeder
{
    public function run()
    {
        $sales = [];
        $saleDetails = [];

        // กำหนดไอดีพนักงานที่ต้องการใช้
        $employeeIds = [1, 3, 6, 10, 11]; // ปรับตามที่คุณต้องการหรือมีอยู่ในตาราง employees

        // กำหนดไอดีเมนูที่มีอยู่ในตาราง 'menus'
        $menuIds = range(1, 44); // สมมติว่ามีเมนู 50 รายการ ตั้งแต่ id 1 ถึง 50

        // สร้างข้อมูลสำหรับตาราง 'sales' จำนวน 100 รายการ
        for ($i = 501; $i <= 1000; $i++) {
            $sales[] = [
                'id' => $i,
                'sale_date' => Carbon::now()->subDays(rand(0, 365)),
                'employee_id' => $employeeIds[array_rand($employeeIds)],
                'payment_type' => ['เงินสด', 'โอนเงิน'][array_rand(['เงินสด', 'โอนเงิน'])],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // สำหรับแต่ละ sale สร้าง sale_details จำนวนสุ่ม 1 ถึง 5 รายการ
            $numberOfDetails = rand(1, 5);
            for ($j = 0; $j < $numberOfDetails; $j++) {
                $saleDetails[] = [
                    'sale_id' => $i,
                    'menu_id' => $menuIds[array_rand($menuIds)],
                    'quantity' => rand(1, 10),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // แทรกข้อมูลลงในตาราง 'sales' และ 'sale_details'
        DB::table('sales')->insert($sales);
        DB::table('sale_details')->insert($saleDetails);
    }
}
