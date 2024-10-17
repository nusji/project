<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class ProductionandProductionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productions = [];
        $productionDetails = [];

        // กำหนดไอดีพนักงานที่ต้องการใช้
        $employeeIds = [1, 3, 6, 10, 11];

        // กำหนดไอดีเมนูที่มีอยู่ในตาราง 'menus'
        $menuIds = range(1, 44); // สมมติว่ามีเมนู 50 รายการ ตั้งแต่ id 1 ถึง 50

        // สร้างข้อมูลสำหรับตาราง 'sales' จำนวน 100 รายการ
        for ($i = 4; $i <= 200; $i++) {
            $productions[] = [
                'id' => $i,
                'production_date' => Carbon::now()->subDays(rand(0, 365)),
                'production_detail' => 'ทำเมนู',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // สำหรับแต่ละ sale สร้าง sale_details จำนวนสุ่ม 1 ถึง 5 รายการ
            $numberOfDetails = rand(1, 15);
            for ($j = 0; $j < $numberOfDetails; $j++) {
                $productionDetails[] = [
                    'production_id' => $i,
                    'menu_id' => $menuIds[array_rand($menuIds)],
                    'quantity' => rand(1, 3),
                    'is_sold_out' => rand(0, 1),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
        }

        // แทรกข้อมูลลงในตาราง 'sales' และ 'sale_details'
        DB::table('productions')->insert($productions);
        DB::table('production_details')->insert($productionDetails);
    }
}
